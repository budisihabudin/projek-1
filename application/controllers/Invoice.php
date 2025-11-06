<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // Load helper
        $this->load->helper('auth');

        // Check login
        require_login();

        // Load model and library
        $this->load->model('Invoice_model');
        $this->load->model('Model_request');
        $this->load->library('pagination');

        // Validate role
        check_role_access(['admin', 'finance']);
    }

    /**
     * Generate invoice dari request pemasangan
     * Dipanggil ketika finance approve request
     */
    public function generate_from_request($id_request) {
        header('Content-Type: application/json');

        if (!$id_request) {
            echo json_encode(['success' => false, 'message' => 'ID Request tidak valid']);
            return;
        }

        try {
            // Check apakah request exists dan sudah approved semua tahapan sebelum finance
            $request = $this->Model_request->get_request_by_id($id_request);

            if (!$request) {
                echo json_encode(['success' => false, 'message' => 'Request tidak ditemukan']);
                return;
            }

            // Check apakah semua tahapan sebelum finance sudah approved
            if ($request->approval_sales != 'approved' ||
                $request->approval_survei != 'approved' ||
                $request->approval_noc != 'approved') {
                echo json_encode(['success' => false, 'message' => 'Request harus melalui approval Sales, Survey, dan NOC terlebih dahulu']);
                return;
            }

            if ($request->approval_finance == 'approved') {
                echo json_encode(['success' => false, 'message' => 'Request ini sudah di-approve oleh Finance']);
                return;
            }

            // Generate invoice
            $result = $this->Invoice_model->generate_invoice_from_request($id_request);

            if ($result) {
                log_message('info', 'Invoice generated successfully: ' . $result['invoice_no'] .
                           ' for customer: ' . $result['customer_name']);

                echo json_encode([
                    'success' => true,
                    'message' => 'Invoice berhasil digenerate!',
                    'invoice_no' => $result['invoice_no'],
                    'id_berlangganan' => $result['id_berlangganan'],
                    'customer_name' => $result['customer_name'],
                    'package_name' => $result['package_name'],
                    'amount' => $result['amount']
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal generate invoice']);
            }

        } catch (Exception $e) {
            log_message('error', 'Exception in generate_from_request: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Manual approve request dan generate invoice (untuk testing)
     */
    public function approve_and_generate($id_request) {
        // Cek role - hanya finance yang bisa approve
        check_role_access(['admin', 'finance']);

        if (!$id_request) {
            show_error('ID Request tidak valid', 400);
            return;
        }

        try {
            // Get request data
            $request = $this->Model_request->get_request_by_id($id_request);

            if (!$request) {
                show_error('Request tidak ditemukan', 404);
                return;
            }

            // Check apakah semua tahapan sebelum finance sudah approved
            if ($request->approval_sales != 'approved' ||
                $request->approval_survei != 'approved' ||
                $request->approval_noc != 'approved') {
                $this->session->set_flashdata('error', 'Request harus melalui approval Sales, Survey, dan NOC terlebih dahulu');
                    redirect('request/pemasangan');
                    return;
            }

            if ($request->approval_finance == 'approved') {
                $this->session->set_flashdata('info', 'Request ini sudah di-approve oleh Finance');
                redirect('request/pemasangan');
                return;
            }

            // Update finance approval
            $this->db->where('id_request', $id_request);
            $this->db->update('tb_request_pemasangan', [
                'approval_finance' => 'approved'
            ]);

            // Generate invoice
            $result = $this->Invoice_model->generate_invoice_from_request($id_request);

            if ($result) {
                $this->session->set_flashdata('success',
                    'Invoice berhasil digenerate! Invoice No: ' . $result['invoice_no'] .
                    ' untuk ' . $result['customer_name']);
            } else {
                $this->session->set_flashdata('error', 'Gagal generate invoice');
            }

            redirect('request/pemasangan');

        } catch (Exception $e) {
            log_message('error', 'Exception in approve_and_generate: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            redirect('request/pemasangan');
        }
    }

    /**
     * List all invoices
     */
    public function index() {
        $data['title'] = 'Daftar Invoice';

        $keyword = $this->input->get('keyword');

        // Pagination
        $config['base_url'] = site_url('invoice/index');
        $config['total_rows'] = $this->Invoice_model->count_all_invoices($keyword);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['invoices'] = $this->Invoice_model->get_all_invoices($config['per_page'], $page, $keyword);
        $data['pagination'] = $this->pagination->create_links();
        $data['keyword'] = $keyword;

        // Get statistics
        $data['stats'] = $this->Invoice_model->get_invoice_statistics();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/invoice/index', $data);
        $this->load->view('backend/templates/footer');
    }

    /**
     * View invoice details
     */
    public function detail($id_berlangganan) {
        $data['title'] = 'Detail Invoice';

        // Get invoice data
        $invoice = $this->Invoice_model->get_invoice_by_number($id_berlangganan);

        if (!$invoice) {
            show_error('Invoice tidak ditemukan', 404);
            return;
        }

        $data['invoice'] = $invoice;

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/invoice/detail', $data);
        $this->load->view('backend/templates/footer');
    }

    /**
     * Print invoice
     */
    public function print_invoice($id_berlangganan) {
        $data['title'] = 'Cetak Invoice';

        // Get invoice data
        $invoice = $this->Invoice_model->get_invoice_by_number($id_berlangganan);

        if (!$invoice) {
            show_error('Invoice tidak ditemukan', 404);
            return;
        }

        $data['invoice'] = $invoice;

        // Load print view (without header/footer)
        $this->load->view('backend/invoice/print', $data);
    }

    /**
     * API untuk auto-generate invoice ketika finance approve
     * Bisa dipanggil via AJAX
     */
    public function auto_generate($id_request) {
        header('Content-Type: application/json');

        if (!$id_request) {
            echo json_encode(['success' => false, 'message' => 'ID Request tidak valid']);
            return;
        }

        try {
            // Check if request is ready for invoice generation
            $request = $this->Model_request->get_request_by_id($id_request);

            if (!$request) {
                echo json_encode(['success' => false, 'message' => 'Request tidak ditemukan']);
                return;
            }

            // Verify all required approvals
            $required_approvals = ['approved', 'approved', 'approved', 'pending']; // sales, survey, noc, finance

            if ($request->approval_sales != $required_approvals[0] ||
                $request->approval_survei != $required_approvals[1] ||
                $request->approval_noc != $required_approvals[2]) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Request belum melalui semua approval yang diperlukan',
                    'current_status' => [
                        'sales' => $request->approval_sales,
                        'survey' => $request->approval_survei,
                        'noc' => $request->approval_noc,
                        'finance' => $request->approval_finance
                    ]
                ]);
                return;
            }

            // Generate invoice
            $result = $this->Invoice_model->generate_invoice_from_request($id_request);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Invoice berhasil di-generate otomatis!',
                    'invoice_no' => $result['invoice_no'],
                    'id_berlangganan' => $result['id_berlangganan'],
                    'data' => $result
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal generate invoice']);
            }

        } catch (Exception $e) {
            log_message('error', 'Exception in auto_generate: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}