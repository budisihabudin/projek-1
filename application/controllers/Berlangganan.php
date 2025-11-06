<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Berlangganan extends CI_Controller {

    public function __construct() {
        parent::__construct();
         if (
            !$this->session->userdata('logged_in') ||
            !in_array($this->session->userdata('role'), ['admin', 'finance'])
        ) {
            redirect('auth/login');
        }
        $this->load->model('Berlangganan_model');
    }

    public function index() {
        $data['title'] = "Berlangganan - ERP ISP";

        $this->load->library('pagination');
        $keyword = $this->input->get('keyword');

        // Pagination configuration
        $config['base_url'] = site_url('berlangganan/index');
        $config['total_rows'] = $this->Berlangganan_model->count_all($keyword);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['reuse_query_string'] = true;

        // Styling pagination Bootstrap 5
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';

        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';

        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);

        // Hitung offset
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['berlangganan'] = $this->Berlangganan_model->get_all($config['per_page'], $page, $keyword);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('berlangganan/index', $data);
        $this->load->view('backend/templates/footer');
    }


    public function toggle_status($id_berlangganan) {
        $this->Berlangganan_model->toggle_status($id_berlangganan);
        $this->session->set_flashdata('success', 'Status pembayaran berhasil diubah!');
        redirect('berlangganan');
    }

}
