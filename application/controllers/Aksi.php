<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aksi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (
            !$this->session->userdata('logged_in') ||
            !in_array($this->session->userdata('role'), ['admin', 'sales'])
        ) {
            redirect('auth/login');
        }

        $this->load->model('Paket_model');
        $this->load->model('Customer_model');
        $this->load->model('Histori_model');
    }

    // Halaman form request instalasi
    public function reques() {
        $data['title'] = 'Form Pengajuan Customer';
        $data['paket'] = $this->Paket_model->get_all_paket();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar');
        $this->load->view('subscriptions/reques_form', $data);
        $this->load->view('backend/templates/footer');
    }

    // Proses tambah request
    public function tambah_request() {
        $id_admin = $this->session->userdata('id_user');
        $id_sales = $this->session->userdata('id_user');

        // Ambil input dari form
        $nama_pelanggan    = $this->input->post('nama_pelanggan');
        $alamat            = $this->input->post('alamat');
        $no_hp             = $this->input->post('no_hp');
        $email             = $this->input->post('email');
        $instansi          = $this->input->post('instansi');
        $pic               = $this->input->post('pic');
        $id_paket          = $this->input->post('id_paket');
        $tgl_mulai         = $this->input->post('tgl_mulai');
        $lama_langganan    = $this->input->post('lama_langganan');
        

        // Data akun customer
        $username = $this->input->post('username');
        $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);         

        // 1️⃣ Simpan akun customer
        $data_user = [
            'username' => $username,
            'password' => $password,
            'role'     => 'customer',
            'status'   => 'active'
        ];

        $this->db->insert('users', $data_user);
        $id_user_customer = $this->db->insert_id(); // ambil id_user baru

        // 2️⃣ Generate kode customer
        $kode_customer = $this->Customer_model->generate_kode_customer();

        // 3️⃣ Simpan data customer
        $data_customer = [
            'id_user'       => $id_user_customer, // FK ke users.id_user
            'modified_by'   => $id_admin,
            'kode_customer' => $kode_customer,
            'nama'          => $nama_pelanggan,
            'alamat'        => $alamat,
            'no_hp'         => $no_hp,
            'email'         => $email,
            'status'        => 'pending',
            'created_at'    => date('Y-m-d H:i:s'),
            'instansi'      => $instansi,
            'pic'           => $pic
        ];

        $this->Customer_model->insert_reques_customer($data_customer);
        $id_customer = $this->db->insert_id();

        // 4️⃣ Simpan histori request
        $data_histori = [
            'modified_by'    => $id_admin,       // admin yang menambahkan
            'id_customer'    => $id_customer,    // FK customer
            'id_user'        => $id_user_customer, // FK ke users.id_user milik customer
            'id_paket'       => $id_paket,
            'id_sales'       => $id_sales,
            'lama_langganan' => $lama_langganan,
            'status'         => 'pending',
            'tgl_langganan'  => $tgl_mulai
        ];

        $this->Histori_model->insert_histori($data_histori);

        $this->session->set_flashdata('success', 'Request instalasi berhasil diajukan.');
        redirect('aksi/reques');
    }

}
