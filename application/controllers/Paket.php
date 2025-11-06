<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paket extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'admin') {
            redirect('auth/login');
        }
        $this->load->model('Paket_model');
    }

    public function index() {
        $this->load->library('pagination');

        $keyword = $this->input->get('q');
        $page = $this->input->get('per_page');
        $limit = 10;
        $offset = $page ? $page : 0;

        $total = $this->Paket_model->count_all($keyword);

        $config['base_url'] = site_url('paket/index?q=' . urlencode($keyword));
        $config['total_rows'] = $total;
        $config['per_page'] = $limit;
        $config['page_query_string'] = TRUE;

        $this->pagination->initialize($config);

        $data['title'] = "Paket Layanan - ERP ISP";
        $data['paket'] = $this->Paket_model->get_all($limit, $offset, $keyword);
        $data['keyword'] = $keyword;
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('backend/templates/header', $data);
        
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('paket/index', $data);
        $this->load->view('backend/templates/footer');
    }

    public function create() {
        if ($this->input->post()) {
            $data = [
                'nama_paket' => $this->input->post('nama_paket'),
                'deskripsi'  => $this->input->post('deskripsi'),
                'harga'      => $this->input->post('harga'),
                'kecepatan'  => $this->input->post('kecepatan'),
                'status'     => $this->input->post('status')
            ];
            $this->Paket_model->insert($data);
            $this->session->set_flashdata('success', 'Paket berhasil ditambahkan!');
            redirect('paket');
        } else {
            $data['title'] = "Tambah Paket - ERP ISP";
            $this->load->view('backend/templates/header', $data);
            
            $this->load->view('backend/templates/sidebar', $data);
            $this->load->view('paket/create', $data);
            $this->load->view('backend/templates/footer');
        }
    }

    public function edit($id) {
        $data['paket'] = $this->Paket_model->get_by_id($id);
        if (!$data['paket']) {
            redirect('paket');
        }

        if ($this->input->post()) {
            $update = [
                'nama_paket' => $this->input->post('nama_paket'),
                'deskripsi'  => $this->input->post('deskripsi'),
                'harga'      => $this->input->post('harga'),
                'kecepatan'  => $this->input->post('kecepatan'),
                'status'     => $this->input->post('status')
            ];
            $this->Paket_model->update($id, $update);
            $this->session->set_flashdata('success', 'Paket berhasil diupdate!');
            redirect('paket');
        }

        $data['title'] = "Edit Paket - ERP ISP";
        $this->load->view('backend/templates/header', $data);
        
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('paket/edit', $data);
        $this->load->view('backend/templates/footer');
    }

    public function delete($id) {
        $this->Paket_model->delete($id);
        $this->session->set_flashdata('success', 'Paket berhasil dihapus!');
        redirect('paket');
    }
}
