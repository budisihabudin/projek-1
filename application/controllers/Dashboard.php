<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // Cek apakah user sudah login menggunakan helper
        require_login();

        // Load model yang dibutuhkan
        $this->load->model('Paket_model');
    }

    
    public function index() {
        $data['title'] = "Dashboard - ERP ISP";
        $role = $this->session->userdata('role');

        if ($role == 'customer') {
            $keyword = $this->input->get('keyword');
            $data['keyword'] = $keyword;
            $data['paket'] = $this->Paket_model->get_all(null, null, $keyword);
        }

        // Load header dan sidebar
        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);

        // Load konten dashboard sesuai role
        if ($role == 'customer') {
            $this->load->view('dashboard/pelanggan', $data);
        }elseif($this->session->userdata('role') =='noc' || $this->session->userdata('role') =='thd') {
            $this->load->view('dashboard/tiketing', $data);
        }elseif($this->session->userdata('role') =='admin') {
            $this->load->view('dashboard/admin', $data);
        }

        // Load footer
        $this->load->view('backend/templates/footer');
    }


}
