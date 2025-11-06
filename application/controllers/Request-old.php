<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Model_request');
        $this->load->model('Customer_model');
        $this->load->model('Paket_model');

        $role = $this->session->userdata('role');
        $is_logged_in = $this->session->userdata('logged_in');

        if (!$is_logged_in || !in_array($role, ['admin', 'sales','surveyor','noc','finance'])) {
            redirect('auth/login');
        }
    }

    // reques pemasangan
    public function index() {
        $data['title'] = "Request Pemasangan";
        $this->load->library('pagination');

        $keyword = $this->input->get('keyword');
        $config['base_url'] = site_url('request/index');
        $config['total_rows'] = $this->Model_request->count_all_request_customer($keyword);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['requests'] = $this->Model_request->get_all_request_customer($config['per_page'], $page, $keyword);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/index', $data);
        $this->load->view('backend/templates/footer');
    }

    public function add() {
        $data['title'] = 'Request Pemasangan';
        $data['paket'] = $this->Paket_model->get_all_paket();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar');
        $this->load->view('backend/request/form', $data);
        $this->load->view('backend/templates/footer');
    }

    public function tambah_request() {
        $id_user = $this->session->userdata('id_user');

        $nama_pelanggan = $this->input->post('nama_pelanggan');
        $alamat = $this->input->post('alamat');
        $no_hp = $this->input->post('no_hp');
        $email = $this->input->post('email');
        $instansi = $this->input->post('instansi');
        $pic = $this->input->post('pic');
        $id_paket = $this->input->post('id_paket');
        $tgl_mulai = $this->input->post('tgl_mulai');
        $lama_bulan = $this->input->post('lama_bulan');

        // 🔹 Buat kode customer berurutan (CUST-001, CUST-002, dst)
        $this->db->select('MAX(RIGHT(kode_customer,6)) as max_kode');
        $this->db->from('customer');
        $query = $this->db->get()->row();
        $kode_urut = $query ? (int)$query->max_kode + 1 : 1;
        $kode_customer = 'CUST-' . str_pad($kode_urut, 6, '0', STR_PAD_LEFT);

        // 🔹 Simpan data customer baru
        $data_customer = [
            'id_user'       => $id_user,
            'kode_customer' => $kode_customer,
            'nama'          => $nama_pelanggan,
            'alamat'        => $alamat,
            'no_hp'         => $no_hp,
            'email'         => $email,
            'status_customer'        => 'pending',
            'created_at'    => date('Y-m-d H:i:s'),
            'instansi'      => $instansi,
            'pic'           => $pic
        ];
        $this->Customer_model->insert_reques_customer($data_customer);
        $id_customer = $this->db->insert_id();

        // 🔹 Simpan ke tabel request pemasangan
        $data_request = [
            'id_customer'      => $id_customer,
            'id_paket'         => $id_paket,
            'tgl_langganan'    => $tgl_mulai,
            'lama_bulan'       => $lama_bulan,
            'approval_sales'   => 'pending',
            'approval_survei'  => 'pending',
            'approval_noc'     => 'pending',
            'approval_finance' => 'pending',
            'created_at'       => date('Y-m-d H:i:s')
        ];
        $this->Model_request->insert_request($data_request);

        $this->session->set_flashdata('success', 'Request pemasangan berhasil diajukan.');
        redirect('request');
    }

    public function detail_request() {
        $id = $this->input->get('id');
        $request = $this->Model_request->get_request_by_id($id);
        echo json_encode($request);
    }

    // enf reques pemasangan

// ============================================================================================

    // reques survei
    public function survei_index() {
        $data['title'] = "Request Survei";
        $this->load->library('pagination');

        $keyword = $this->input->get('keyword');
        $config['base_url'] = site_url('request/survei_index');
        $config['total_rows'] = $this->Model_request->count_all_survei($keyword);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['surveis'] = $this->Model_request->get_all_survei($config['per_page'], $page, $keyword);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/survei_index', $data);
        $this->load->view('backend/templates/footer');
    }

    public function add_survei($id_request = null) {
        $data['title'] = "Tambah Request Survei";
        $data['id_request'] = $id_request;

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar');
        $this->load->view('backend/request/form_survei', $data);
        $this->load->view('backend/templates/footer');
    }

    public function tambah_survei() {
        $id_request = $this->input->post('id_request');
        $tgl_survei = $this->input->post('tgl_survei');
        $catatan = $this->input->post('catatan');

        $data = [
            'id_request' => $id_request,
            'tgl_survei' => $tgl_survei,
            'catatan'    => $catatan,
            'status'     => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->Model_request->insert_survei($data);
        $this->session->set_flashdata('success', 'Request survei berhasil ditambahkan.');
        redirect('request/survei_index');
    }

    public function detail_survei() {
        $id = $this->input->get('id');
        $survei = $this->Model_request->get_survei_by_id($id);
        echo json_encode($survei);
    }


    // lakukan survei
    public function pilih_surveyor($id_survei)
    {
        if ($this->session->userdata('nama_jabatan') != 'Manager Surveyor') {
            redirect('request/survei_index');
        }

        $data['title'] = "Tunjuk Surveyor";
        
        // Ambil detail request survei
        $data['survei'] = $this->Model_request->get_survei_by_id($id_survei);

        $data['surveyor_list'] = $this->Model_request->get_surveyor_list(); 
        
        // Jika detail survei tidak ditemukan
        if (!$data['survei']) {
            $this->session->set_flashdata('error', 'Data Request Survei tidak ditemukan.');
            redirect('request/survei_index');
        }

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/form_pilih_surveyor', $data); // View baru
        $this->load->view('backend/templates/footer');
    }

    public function proses_pilih_surveyor()
    {
        // Pastikan hanya Admin yang bisa menugaskan
        if ($this->session->userdata('nama_jabatan') != 'Manager Surveyor') {
            redirect('request/survei_index');
        }

        $id_survei = $this->input->post('id_survei');
        $id_employee = $this->input->post('id_employee');

        if (empty($id_survei) || empty($id_employee)) {
             $this->session->set_flashdata('error', 'Data tidak lengkap.');
             redirect('request/survei_index');
        }

        $data_update = [
            'id_surveyor' => $id_employee,
            'status' => "proses"
        ];

        $this->Model_request->update_survei($id_survei, $data_update);

        $this->session->set_flashdata('success', 'Surveyor berhasil ditugaskan.');
        redirect('request/survei_index');
    }

    public function approval_survei($id_survei)
    {
        $where = $id_survei;
        $this->db->where('tb_request_survei.id_survei', $where);
        $data = array('status' => "done");
        $this->db->update('tb_request_survei', $data);
        $this->session->set_flashdata('success', 'Survei berhasil disetujui.');
        redirect('request/survei_index');
    }

    // end reques survei


// =========================================================================================
    // ===================== Upload Dokumen Survei =====================

    public function survei_dokumen() {
        $data['title'] = "Daftar Dokumen Survei";

        $this->load->library('pagination');
        $keyword = $this->input->get('keyword');

        $config['base_url'] = site_url('request/survei_dokumen');
        $config['total_rows'] = $this->Model_request->count_all_dokumen($keyword);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['dokumens'] = $this->Model_request->get_all_dokumen_paginate($config['per_page'], $page, $keyword);
        $data['pagination'] = $this->pagination->create_links();
        $data['keyword'] = $keyword;

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/dokumen_survei_index', $data);
        $this->load->view('backend/templates/footer');
    }


    public function dokumen_survei($id_survei) {
        $data['title'] = "Dokumen Survei";
        $data['id_survei'] = $id_survei;
        $data['dokumens'] = $this->Model_request->get_dokumen_by_survei($id_survei);

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/form_upload_survei', $data);
        $this->load->view('backend/templates/footer');
    }

    // Upload dokumen tunggal
    public function tambah_dokumen() {
        $id_survei = $this->input->post('id_survei');
        $keterangan = $this->input->post('keterangan_foto');

        $config['upload_path'] = './uploads/survei/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size'] = 5000; // 5MB
        $config['encrypt_name'] = true;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('foto_survei')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
        } else {
            $upload_data = $this->upload->data();
            $data = [
                'id_survei' => $id_survei,
                'foto_survei' => $upload_data['file_name'],
                'keterangan_foto' => $keterangan
            ];
            $this->Model_request->insert_dokumen($data);
            $this->session->set_flashdata('success', 'Dokumen survei berhasil diupload.');
        }
        redirect('request/dokumen_survei/'.$id_survei);
    }

    // Upload multi-file sekaligus
    public function tambah_dokumen_multi() {
        $id_survei = $this->input->post('id_survei');
        $keterangan_all = $this->input->post('keterangan_foto'); // pisahkan dengan "|"
        $keterangan_array = explode('|', $keterangan_all);

        if (!empty($_FILES['foto_survei']['name'])) {
            $files = $_FILES['foto_survei'];
            $count = count($files['name']);
            for ($i = 0; $i < $count; $i++) {
                $_FILES['file']['name']     = $files['name'][$i];
                $_FILES['file']['type']     = $files['type'][$i];
                $_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['file']['error']    = $files['error'][$i];
                $_FILES['file']['size']     = $files['size'][$i];

                $config['upload_path'] = './uploads/survei/';
                $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                $config['max_size'] = 5000;
                $config['encrypt_name'] = true;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) {
                    $upload_data = $this->upload->data();
                    $ses = $this->session->userdata('id_employee');
                    $data = [
                        'id_survei' => $id_survei,
                        'id_surveyor' => $ses,
                        'foto_survei' => $upload_data['file_name'],
                        'keterangan_foto' => $keterangan_array[$i] ?? ''
                    ];
                    $this->Model_request->insert_dokumen($data);
                }
            }
            $this->session->set_flashdata('success', 'Semua dokumen berhasil diupload.');
        } else {
            $this->session->set_flashdata('error', 'Tidak ada file yang dipilih.');
        }

        redirect('request/dokumen_survei/'.$id_survei);
    }

    // Edit dokumen
    public function edit_dokumen($id_dokumen) {
        $dokumen = $this->Model_request->get_dokumen($id_dokumen);
        if (!$dokumen) {
            $this->session->set_flashdata('error', 'Dokumen tidak ditemukan.');
            redirect('request/survei_index');
        }

        $id_survei = $dokumen->id_survei;
        $keterangan = $this->input->post('keterangan_foto');

        $config['upload_path'] = './uploads/survei/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size'] = 5000; 
        $config['encrypt_name'] = true;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('foto_survei')) {
            if ($dokumen->foto_survei && file_exists('./uploads/survei/'.$dokumen->foto_survei)) {
                unlink('./uploads/survei/'.$dokumen->foto_survei);
            }
            $upload_data = $this->upload->data();
            $data = [
                'foto_survei' => $upload_data['file_name'],
                'keterangan_foto' => $keterangan
            ];
        } else {
            $data = ['keterangan_foto' => $keterangan];
        }

        $this->Model_request->update_dokumen($id_dokumen, $data);
        $this->session->set_flashdata('success', 'Dokumen survei berhasil diperbarui.');
        redirect('request/dokumen_survei/'.$id_survei);
    }

    // Hapus dokumen
    public function hapus_dokumen($id_dokumen) {
        $dokumen = $this->Model_request->get_dokumen($id_dokumen);
        if ($dokumen) {
            if ($dokumen->foto_survei && file_exists('./uploads/survei/'.$dokumen->foto_survei)) {
                unlink('./uploads/survei/'.$dokumen->foto_survei);
            }
            $this->Model_request->delete_dokumen($id_dokumen);
            $this->session->set_flashdata('success', 'Dokumen survei berhasil dihapus.');
            redirect('request/dokumen_survei/'.$dokumen->id_survei);
        } else {
            $this->session->set_flashdata('error', 'Dokumen tidak ditemukan.');
            redirect('request/survei_index');
        }
    }

    // end dokumen survei

    // ===================== Dokumen Customer =====================

    // Index dokumen customer dengan pagination dan search
    public function dokumen_customer() {
        $data['title'] = "Dokumen Customer";
        $this->load->library('pagination');

        $keyword = $this->input->get('keyword');

        $config['base_url'] = site_url('request/dokumen_customer');
        $config['total_rows'] = $this->Model_request->count_all_dokumen_customer($keyword);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['dokumens'] = $this->Model_request->get_all_dokumen_customer_paginate($config['per_page'], $page, $keyword);
        $data['pagination'] = $this->pagination->create_links();
        $data['keyword'] = $keyword;

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/dokumen_customer_index', $data);
        $this->load->view('backend/templates/footer');
    }

    // Form upload dokumen customer
    public function tambah_dokumen_customer() {
        $data['title'] = "Upload Dokumen Customer";
        $data['customer_done_survei_reques'] = $this->Model_request->get_customer_done_survei_reques();
 
        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/form_upload_customer', $data);
        $this->load->view('backend/templates/footer');
    }

    // Simpan dokumen customer
    public function simpan_dokumen_customer() {
        $id_customer = $this->input->post('id_customer');
        $keterangan = $this->input->post('keterangan_foto');

        $config['upload_path'] = './uploads/customer/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size'] = 5000; // 5MB
        $config['encrypt_name'] = true;

        $this->load->library('upload', $config);

        if (!empty($_FILES['foto_customer']['name'])) {
            $files = $_FILES['foto_customer'];
            $count = count($files['name']);
            for ($i = 0; $i < $count; $i++) {
                $_FILES['file']['name']     = $files['name'][$i];
                $_FILES['file']['type']     = $files['type'][$i];
                $_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['file']['error']    = $files['error'][$i];
                $_FILES['file']['size']     = $files['size'][$i];

                $this->upload->initialize($config);
                if ($this->upload->do_upload('file')) {
                    $upload_data = $this->upload->data();
                    $data = [
                        'id_customer' => $id_customer,
                        'foto_customer' => $upload_data['file_name'],
                        'keterangan_foto' => $keterangan[$i] ?? ''
                    ];
                    $this->Model_request->insert_dokumen_customer($data);
                }
            }
            $this->session->set_flashdata('success', 'Dokumen berhasil diupload.');
        } else {
            $this->session->set_flashdata('error', 'Tidak ada file yang dipilih.');
        }

        redirect('request/dokumen_customer');
    }

      // Form edit dokumen customer
    public function edit_dokumen_customer($id_dokumen_customer) {
        $dokumen = $this->Model_request->get_dokumen_customer_by_id($id_dokumen_customer);
        
        if (!$dokumen) {
            $this->session->set_flashdata('error', 'Dokumen tidak ditemukan.');
            redirect('request/dokumen_customer');
        }

        $data['title'] = "Edit Dokumen Customer";
        $data['dokumen'] = $dokumen;

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/form_edit_upload_customer', $data);
        $this->load->view('backend/templates/footer');
    }

    // Update dokumen customer
    public function update_dokumen_customer($id_dokumen_customer) {
        $dokumen = $this->Model_request->get_dokumen_customer_by_id($id_dokumen_customer);
        if (!$dokumen) {
            $this->session->set_flashdata('error', 'Dokumen tidak ditemukan.');
            redirect('request/dokumen_customer');
        }

        $keterangan = $this->input->post('keterangan_foto');

        $config['upload_path'] = './uploads/customer/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size'] = 5000;
        $config['encrypt_name'] = true;

        $this->load->library('upload', $config);

        if (!empty($_FILES['foto_customer']['name']) && $this->upload->do_upload('foto_customer')) {
            if ($dokumen->foto_customer && file_exists('./uploads/customer/'.$dokumen->foto_customer)) {
                unlink('./uploads/customer/'.$dokumen->foto_customer);
            }
            $upload_data = $this->upload->data();
            $data = [
                'foto_customer' => $upload_data['file_name'],
                'keterangan_foto' => $keterangan
            ];
        } else {
            $data = ['keterangan_foto' => $keterangan];
        }

        $this->Model_request->update_dokumen_customer($id_dokumen_customer, $data);
        $this->session->set_flashdata('success', 'Dokumen berhasil diperbarui.');
        redirect('request/dokumen_customer');
    }

    // Hapus dokumen customer
    public function hapus_dokumen_customer($id_dokumen_customer) {
        $dokumen = $this->Model_request->get_dokumen_customer_by_id($id_dokumen_customer);
        if ($dokumen) {
            if ($dokumen->foto_customer && file_exists('./uploads/customer/'.$dokumen->foto_customer)) {
                unlink('./uploads/customer/'.$dokumen->foto_customer);
            }
            $this->Model_request->delete_dokumen_customer($id_dokumen_customer);
            $this->session->set_flashdata('success', 'Dokumen berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Dokumen tidak ditemukan.');
        }
        redirect('request/dokumen_customer');
    }
    /*end dokumen customer*/

// ===================================================================================================

    /*reques instalasi noc*/
     public function instalasi() {
        $data['title'] = "Request instalasi";
        $this->load->library('pagination');

        $keyword = $this->input->get('keyword');
        $config['base_url'] = site_url('request/instalasi');
        $config['total_rows'] = $this->Model_request->count_all_request_instalasi_customer($keyword);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['requests_instalasi'] = $this->Model_request->get_all_request_instalasi_customer($config['per_page'], $page, $keyword);
    
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/instalasi', $data);
        $this->load->view('backend/templates/footer');
    }

    public function edit_instalasi($id_request)
    {
        // Ambil data request instalasi
        $request = $this->Model_request->get_request_by_id($id_request);
        if (!$request) {
            $this->session->set_flashdata('error', 'Request instalasi tidak ditemukan.');
            redirect('request/instalasi');
        }

        $data['title'] = "Instalasi Customer";
        $data['request'] = $request;

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/form_edit_instalasi', $data);
        $this->load->view('backend/templates/footer');
    }

    public function update_instalasi($id_request)
{
    // Ambil inputan form
    $ip_customer = $this->input->post('ip_customer');

    // Ambil data request
    $request = $this->Model_request->get_request_by_id($id_request);
    if (!$request) {
        $this->session->set_flashdata('error', 'Request instalasi tidak ditemukan.');
        redirect('request/instalasi');
    }

    // Update data request instalasi
    $update = [
        'approval_noc' => "approval"
    ];
    $this->Model_request->update_request_instalasi($id_request, $update);

    // Insert ke tb_instalasi_customer
    $f = [
        'id_customer' => $request->id_customer,
        'id_paket'    => $request->id_paket,
        'ip_customer' => $ip_customer,
        'created_by'  => $this->session->userdata('id_employee'),
        'created_at'  => date('Y-m-d H:i:s')
    ];
    $this->Model_request->insert_instalasi_customer($instalasi_data);

    $this->session->set_flashdata('success', 'Data instalasi berhasil diperbarui.');
    redirect('request/instalasi');
}




    /*end reques instalasi noc*/




}