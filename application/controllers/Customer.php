<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (
            !$this->session->userdata('logged_in') ||
            !in_array($this->session->userdata('role'), ['admin', 'sales'])
        ) {
            redirect('auth/login');
        }
        $this->load->model('Customer_model');
        $this->load->model('Paket_model');
    }

    public function index() {
    $data['title'] = "Customer - ERP ISP";

    $this->load->library('pagination');
    $keyword = $this->input->get('keyword');

    // Pagination configuration
    $config['base_url'] = site_url('customer/index');
    $config['total_rows'] = $this->Customer_model->count_all($keyword);
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

    $data['customer'] = $this->Customer_model->get_all($config['per_page'], $page, $keyword);
    $data['pagination'] = $this->pagination->create_links();

    $this->load->view('backend/templates/header', $data);
    
    $this->load->view('backend/templates/sidebar', $data);
    $this->load->view('customer/index', $data);
    $this->load->view('backend/templates/footer');
}


    public function create() {
        if ($this->input->post()) {
            $data_user = [
                'username' => $this->input->post('username'),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'role'     => 'customer'
            ];

            $data_customer = [
                'kode_customer' => $this->input->post('kode_customer'),
                'nama'          => $this->input->post('nama'),
                'alamat'        => $this->input->post('alamat'),
                'no_hp'         => $this->input->post('no_hp'),
                'email'         => $this->input->post('email'),
                'status'        => 'pending'
            ];

            $this->db->trans_start();

            // Insert user & customer
            $this->Customer_model->insert($data_user, $data_customer);

            // Ambil id_customer terbaru
            $id_customer = $this->db->insert_id(); // asumsi insert customer terakhir

            // Data berlangganan
            $id_paket = $this->input->post('id_paket');
            $tgl_mulai = $this->input->post('tgl_mulai');
            $lama_langganan = (int)$this->input->post('lama_langganan');

            $this->Customer_model->insert_berlangganan($id_customer, $id_paket, $tgl_mulai, $lama_langganan);

            $this->db->trans_complete();

            $this->session->set_flashdata('success', 'Customer berhasil ditambahkan beserta berlangganannya!');
            redirect('customer');

        } else {
            $data['kode_customer'] = $this->Customer_model->generate_kode_customer();
            $data['paket'] = $this->Paket_model->get_all();
            $data['title'] = "Tambah Customer - ERP ISP";
            $this->load->view('backend/templates/header', $data);
            
            $this->load->view('backend/templates/sidebar', $data);
            $this->load->view('customer/create', $data);
            $this->load->view('backend/templates/footer');
        }
    }


    public function delete($id) {
        $this->Customer_model->delete($id);
        $this->session->set_flashdata('success', 'Customer berhasil dihapus!');
        redirect('customer');
    }

}
