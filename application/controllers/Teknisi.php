<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teknisi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'admin') {
            redirect('auth/login');
        }
        $this->load->model('Teknisi_model');
    }

    public function index() {
        $data['title'] = "Teknisi - ERP ISP";

        $this->load->library('pagination');
        $keyword = $this->input->get('keyword');
        $config['base_url'] = site_url('teknisi/index');
        $config['total_rows'] = $this->Teknisi_model->count_all($keyword);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['teknisi'] = $this->Teknisi_model->get_all($config['per_page'], $page, $keyword);

        $this->load->view('backend/templates/header', $data);
        
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('teknisi/index', $data);
        $this->load->view('backend/templates/footer');
    }

    public function create() {
        if ($this->input->post()) {
            // Data untuk tabel users
            $data_user = [
                'username' => $this->input->post('username'),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'role'     => 'teknisi'
            ];

            // Data untuk tabel teknisi
            $data_teknisi = [
                'kode_teknisi' => $this->Teknisi_model->generate_kode_teknisi(),
                'nama'         => $this->input->post('nama'),
                'alamat'       => $this->input->post('alamat'),
                'no_hp'        => $this->input->post('no_hp'),
                'email'        => $this->input->post('email'),
                'status'       => 'aktif'
            ];

            // Panggil model insert
            $insert = $this->Teknisi_model->insert($data_user, $data_teknisi);

            if ($insert) {
                $this->session->set_flashdata('success', 'Teknisi berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Terjadi kesalahan saat menambahkan teknisi!');
            }

            redirect('teknisi');
        } else {
            // Jika belum submit, tampilkan form
            $data['title'] = "Tambah Teknisi - ERP ISP";
            $this->load->view('backend/templates/header', $data);
            
            $this->load->view('backend/templates/sidebar', $data);
            $this->load->view('teknisi/create', $data);
            $this->load->view('backend/templates/footer');
        }
    }

    public function edit($id) {
        if ($this->input->post()) {
            $data_teknisi = [
                'nama'   => $this->input->post('nama'),
                'email'  => $this->input->post('email'),
                'no_hp'  => $this->input->post('no_hp'),
                'status' => $this->input->post('status')
            ];

            $this->Teknisi_model->update($id, $data_teknisi);
            $this->session->set_flashdata('success', 'Teknisi berhasil diperbarui!');
            redirect('teknisi');
        } else {
            $data['teknisi'] = $this->Teknisi_model->get_by_id($id);
            $data['title'] = "Edit Teknisi - ERP ISP";

            $this->load->view('backend/templates/header', $data);
            
            $this->load->view('backend/templates/sidebar', $data);
            $this->load->view('teknisi/edit', $data);
            $this->load->view('backend/templates/footer');
        }
    }

    public function delete($id) {
        $this->Teknisi_model->delete($id);
        $this->session->set_flashdata('success', 'Teknisi berhasil dihapus!');
        redirect('teknisi');
    }
}
