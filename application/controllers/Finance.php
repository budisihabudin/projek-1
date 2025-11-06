<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance extends CI_Controller {

     public function __construct() {
        parent::__construct();
        if (
            !$this->session->userdata('logged_in') ||
            !in_array($this->session->userdata('role'), ['admin', 'finance'])
        ) {
            redirect('auth/login');
        }

        $this->load->model('Approval_model');
        $this->load->model('Customer_model');
        $this->load->dbforge();
    }

    public function invoice() {
        $data['title'] = "Generate Invoice Customer";

        $this->load->library('pagination');
        $keyword = $this->input->get('keyword');

        $config['base_url'] = site_url('finance/invoice');
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

     // Approve
    // PERBAIKAN: Mengubah d_histori menjadi id_histori
    public function generate_inv($id_request) {
        // Load required models
        $this->load->model('Model_request');
        $this->load->model('Invoice_model');

        // Get request data
        $request = $this->Model_request->get_request_by_id($id_request);

        if (!$request) {
            $this->session->set_flashdata('error', 'Request tidak ditemukan');
            redirect('request');
        }

        // Check if all required approvals are complete
        if ($request->approval_sales != 'approved' ||
            $request->approval_survei != 'approved' ||
            $request->approval_noc != 'approved') {
            $this->session->set_flashdata('error', 'Request harus melalui approval Sales, Survey, dan NOC terlebih dahulu');
            redirect('request');
        }

        // Check if already approved by finance
        if ($request->approval_finance == 'approved') {
            $this->session->set_flashdata('info', 'Request ini sudah di-approve oleh Finance');
            redirect('request');
        }

        try {
            // Generate invoice menggunakan Invoice_model
            $invoice_result = $this->Invoice_model->generate_invoice_from_request($id_request);

            if ($invoice_result) {
                // Update status customer
                $this->db->where('id_customer', $request->id_customer)
                         ->update('customer', ['status_customer' => 'aktif']);

                // Update request finance approval
                $this->db->where('id_request', $id_request)
                         ->update('tb_request_pemasangan', ['approval_finance' => 'approved']);

                $this->session->set_flashdata('success',
                    'Invoice berhasil digenerate! Invoice No: ' . $invoice_result['invoice_no'] .
                    ' untuk ' . $invoice_result['customer_name']);

                // Log activity
                log_message('info', 'Finance approved request and generated invoice: ' . $id_request . ', Invoice: ' . $invoice_result['invoice_no']);

            } else {
                $this->session->set_flashdata('error', 'Gagal generate invoice');
            }

        } catch (Exception $e) {
            log_message('error', 'Exception in generate_inv: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        // Redirect based on role
        if ($this->session->userdata('role') == "finance" || $this->session->userdata('role') == "admin") {
            redirect('request');
        }
    }

    // Reject
    // PERBAIKAN: Mengubah d_histori menjadi id_histori
    public function reject($id_histori) {
        $data = $this->Approval_model->get_by_id($id_histori);
        if ($data) {
            $this->Approval_model->update_status($id_histori, 'rejected');

            // PERBAIKAN: Logikanya, jika ditolak, status customer harus nonaktif
            $this->db->where('id_customer', $data->id_customer)
                     ->update('customer', ['status' => 'nonaktif']);

            $this->session->set_flashdata('error', 'Langganan ditolak.');
        } else {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        }
        redirect('approval_langganan');
    }

    /**
     * Check if id_invoice column exists in berlangganan table, add if not exists
     */
    private function check_and_add_invoice_column() {
        // Check if column exists
        $query = $this->db->query("SHOW COLUMNS FROM `berlangganan` LIKE 'id_invoice'");

        if ($query->num_rows() == 0) {
            // Column doesn't exist, add it
            $this->dbforge->add_column('berlangganan', [
                'id_invoice' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => TRUE,
                    'after' => 'id_transaksi'
                ]
            ]);

            // Add index for performance
            $this->db->query("CREATE INDEX idx_id_invoice ON berlangganan (id_invoice)");

            // Update existing records with invoice format
            $this->db->query("
                UPDATE berlangganan
                SET id_invoice = CONCAT('INV-', DATE_FORMAT(tgl_mulai, '%Y%m'), '-', LPAD(id_customer, 4, '0'), '-', LPAD(id_berlangganan, 3, '0'))
                WHERE id_invoice IS NULL
            ");
        }
    }


}