<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jabatan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'admin') {
            redirect('auth/login');
        }
        $this->load->model('Jabatan_model');
    }

    public function index() {
        $data['title'] = "Daftar Jabatan";
        $this->load->library('pagination');

        $keyword = $this->input->get('keyword');
        $config['base_url'] = site_url('jabatan/index');
        $config['total_rows'] = $this->Jabatan_model->count_all($keyword);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['jabatans'] = $this->Jabatan_model->get_all($config['per_page'], $page, $keyword);

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('jabatan/index', $data);
        $this->load->view('backend/templates/footer');
    }

    public function create() {
        $data['title'] = "Tambah Jabatan";
        $data['action'] = site_url('jabatan/store');

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('jabatan/create', $data);
        $this->load->view('backend/templates/footer');
    }

    public function store() {
        $this->form_validation->set_rules('nama_jabatan','Nama Jabatan','required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = [
                'nama_jabatan' => $this->input->post('nama_jabatan', true)
            ];
            $this->Jabatan_model->insert($data);
            $this->session->set_flashdata('success','Jabatan berhasil ditambahkan.');
            redirect('jabatan');
        }
    }

    public function edit($id) {
        $data['jabatan'] = $this->Jabatan_model->get($id);
        if(!$data['jabatan']) show_404();

        $data['title'] = "Edit Jabatan";
        $data['action'] = site_url('jabatan/update/'.$id);

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('jabatan/create', $data);
        $this->load->view('backend/templates/footer');
    }

    public function update($id) {
        $this->form_validation->set_rules('nama_jabatan','Nama Jabatan','required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'nama_jabatan' => $this->input->post('nama_jabatan', true)
            ];
            $this->Jabatan_model->update($id, $data);
            $this->session->set_flashdata('success','Jabatan berhasil diperbarui.');
            redirect('jabatan');
        }
    }

    public function delete($id) {
        $this->Jabatan_model->delete($id);
        $this->session->set_flashdata('success','Jabatan berhasil dihapus.');
        redirect('jabatan');
    }
}
