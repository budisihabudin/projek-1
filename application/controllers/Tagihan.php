<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tagihan extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // Load helper secara manual
        $this->load->helper('auth');

        // Cek login menggunakan helper
        require_login();

        // Load model dan library
        $this->load->model('Model_tagihan');
        $this->load->model('Model_request');
        $this->load->library('pagination');

        // Validasi role
        check_role_access(['admin', 'customer', 'finance']);
    }

    public function bulanan() {
        $data['title'] = 'Request Paket & Tagihan Bulanan';

        // Get search keyword
        $keyword = $this->input->get('keyword');

        // Get role and customer data for filtering
        $role = $this->session->userdata('role');
        $customer_id = $this->session->userdata('id_customer');

        // Get Request Paket Data
        $config_request['base_url'] = site_url('tagihan/bulanan');
        $config_request['total_rows'] = $this->Model_request->count_all_request_customer($keyword);
        $config_request['per_page'] = 10;
        $config_request['uri_segment'] = 3;
        $config_request['attributes'] = ['class' => 'page-link'];
        $config_request['enable_query_strings'] = TRUE;
        $config_request['page_query_string'] = TRUE;
        $config_request['query_string_segment'] = 'page_request';

        $this->pagination->initialize($config_request);
        $page_request = ($this->input->get('page_request')) ? $this->input->get('page_request') : 0;

        $data['request_paket'] = $this->Model_request->get_all_request_customer($config_request['per_page'], $page_request, $keyword);
        $data['pagination_request'] = $this->pagination->create_links();

        // Reset pagination for tagihan
        $this->pagination->initialize();

        // Get Tagihan Data
        $config_tagihan['base_url'] = site_url('tagihan/bulanan');
        $config_tagihan['total_rows'] = $this->Model_tagihan->count_all_tagihan_bulanan($keyword);
        $config_tagihan['per_page'] = 10;
        $config_tagihan['uri_segment'] = 3;
        $config_tagihan['attributes'] = ['class' => 'page-link'];
        $config_tagihan['enable_query_strings'] = TRUE;
        $config_tagihan['page_query_string'] = TRUE;
        $config_tagihan['query_string_segment'] = 'page_tagihan';

        $this->pagination->initialize($config_tagihan);
        $page_tagihan = ($this->input->get('page_tagihan')) ? $this->input->get('page_tagihan') : 0;

        $data['tagihan_bulanan'] = $this->Model_tagihan->get_all_tagihan_data($config_tagihan['per_page'], $page_tagihan, $keyword);
        $data['pagination_tagihan'] = $this->pagination->create_links();

        // Add search keyword to view
        $data['keyword'] = $keyword;
        $data['user_role'] = $role;
        $data['customer_id'] = $customer_id;

        // Check if user is customer
        if ($role == 'customer') {
            // Load customer-specific view
            $this->load->view('backend/templates/header', $data);
            $this->load->view('backend/templates/sidebar', $data);
            $this->load->view('backend/tagihan/customer_bulanan', $data);
            $this->load->view('backend/templates/footer');
        } else {
            // Load admin/employee view
            $this->load->view('backend/templates/header', $data);
            $this->load->view('backend/templates/sidebar', $data);
            $this->load->view('backend/tagihan/bulanan', $data);
            $this->load->view('backend/templates/footer');
        }
    }
}