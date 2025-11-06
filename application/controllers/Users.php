<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'admin') {
            redirect('auth/login');
        }
        $this->load->model('User_model');
    }

    public function index() {
        $data['title'] = "Manajemen Akun";
        $this->load->library('pagination');

        $keyword = $this->input->get('keyword');
        $config['base_url'] = site_url('users/index');
        $config['total_rows'] = $this->User_model->count_all($keyword);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['users'] = $this->User_model->get_all($config['per_page'], $page, $keyword);

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('users/index', $data);
        $this->load->view('backend/templates/footer');
    }


     public function create() {
        $data['title'] = "Tambah User";
        $data['action'] = site_url('users/store');
        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('users/create', $data);
        $this->load->view('backend/templates/footer');
    }

    public function store() {
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('role', 'Role', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = [
                'username' => $this->input->post('username'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role' => $this->input->post('role'),
                'status' => $this->input->post('status')
            ];
            $this->User_model->insert($data);
            $this->session->set_flashdata('success', 'User berhasil ditambahkan.');
            redirect('users');
        }
    }

    public function edit($id) {
        $data['user'] = $this->User_model->get($id);
        if (!$data['user']) show_404();

        $data['title'] = "Edit User";
        $data['action'] = site_url('users/update/'.$id);
         
        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('users/create', $data); // reuse view create
        $this->load->view('backend/templates/footer');
    }

    public function update($id) {
        $this->form_validation->set_rules('username', 'Username', 'required|callback_username_check['.$id.']');
        $this->form_validation->set_rules('role', 'Role', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'username' => $this->input->post('username'),
                'role' => $this->input->post('role'),
                'status' => $this->input->post('status')
            ];
            if ($this->input->post('password')) {
                $data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            }
            $this->User_model->update($id, $data);
            $this->session->set_flashdata('success', 'User berhasil diperbarui.');
            redirect('users');
        }
    }

    // Callback untuk cek unique username saat edit
    public function username_check($username, $id) {
        $user = $this->User_model->get_by_username($username);
        if ($user && $user->id_user != $id) {
            $this->form_validation->set_message('username_check', 'Username sudah digunakan.');
            return FALSE;
        }
        return TRUE;
    }


    public function disable($id) {
        $update = $this->User_model->update($id, ['status' => 'nonaktif']);
        if ($update) {
            $this->session->set_flashdata('success', 'Akun berhasil di-nonaktifkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menonaktifkan akun.');
        }
        redirect('users');
    }

    public function enable($id) {
        $update = $this->User_model->update($id, ['status' => 'active']);
        if ($update) {
            $this->session->set_flashdata('success', 'Akun berhasil diaktifkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengaktifkan akun.');
        }
        redirect('users');
    }

    public function delete($id) {
    $user = $this->User_model->get($id);
    if (!$user) {
        $this->session->set_flashdata('error', 'User tidak ditemukan.');
        redirect('users');
    }

    // Cegah hapus diri sendiri
    if ($this->session->userdata('id_user') == $id) {
        $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        redirect('users');
    }

    $this->db->trans_start();

    // --- 1. Hapus semua teknisi yang terhubung dengan user ini ---
    $this->db->where('id_user', $id)->delete('teknisi');

    // --- 2. Ambil semua customer yang terhubung dengan user ini ---
    $customers = $this->db->get_where('customer', ['id_user' => $id])->result();

    foreach ($customers as $c) {
        $id_customer = $c->id_customer;

        // --- Hapus semua tabel yang memiliki relasi dengan customer ---
        $this->db->where('id_customer', $id_customer)->delete('berlangganan');
        $this->db->where('id_customer', $id_customer)->delete('permintaan_langganan');
        $this->db->where('id_customer', $id_customer)->delete('komplain');
        $this->db->where('id_customer', $id_customer)->delete('spk');
        $this->db->where('id_customer', $id_customer)->delete('histori_berlangganan');
    }

    // --- 3. Hapus data customer-nya sendiri ---
    $this->db->where('id_user', $id)->delete('customer');

    // --- 4. Hapus data user terkait ---
    $this->User_model->delete($id);

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        $this->session->set_flashdata('error', 'Gagal menghapus user karena masih terhubung dengan data lain.');
    } else {
        $this->session->set_flashdata('success', 'User dan semua relasinya berhasil dihapus.');
    }

        redirect('users');
    }

 
}
