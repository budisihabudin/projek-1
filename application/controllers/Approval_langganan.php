<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval_langganan extends CI_Controller {

     public function __construct() {
        parent::__construct();
        if (
            !$this->session->userdata('logged_in') ||
            !in_array($this->session->userdata('role'), ['admin', 'sales','noc','surveyor'])
        ) {
            redirect('auth/login');
        }

        $this->load->model('Approval_model');
        $this->load->model('Customer_model');
        $this->load->model('Survei_model');
    }

    
    public function approve_online($id_histori)
    {
        $data = [
            'instalasi'   => 'done',
            'modified_by' => $this->session->userdata('id_user')
        ];

        $this->Survei_model->update($id_histori, $data);
        $this->session->set_flashdata('success', 'Berhasil approved instalasi.');
        redirect('approval_langganan','refresh');
    }


    public function approve_dokumen($id_histori)
    {
        $data = [
            'aktivitas'   => 'approval',
            'modified_by' => $this->session->userdata('id_user')
        ];

        $this->Survei_model->update($id_histori, $data);
        $this->session->set_flashdata('success', 'Berhasil approved dokumen customer.');
        redirect('survei','refresh');
    }

    public function index() {
        $data['title'] = "Approval Pengajuan Pemasangan";

        $this->load->library('pagination');
        $keyword = $this->input->get('keyword');

        $config['base_url'] = site_url('approval_langganan/index');
        $config['total_rows'] = $this->Approval_model->count_all($keyword);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['reuse_query_string'] = true;

        // Pagination bootstrap style
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['requests'] = $this->Approval_model->get_all($config['per_page'], $page, $keyword);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('approval_langganan/index', $data);
        $this->load->view('backend/templates/footer');
    }

  
}