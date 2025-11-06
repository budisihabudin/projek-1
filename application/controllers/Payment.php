<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // Load library dan model
        $this->load->library('midtrans_lib');
        $this->load->model('Model_tagihan');
    }

    /**
     * Create tagihan dari berlangganan lalu redirect ke pembayaran
     */
    public function create_from_berlangganan($id_berlangganan = null) {
        // Cek login untuk method ini
        require_login();
        // Set JSON content type untuk AJAX request
        $is_ajax = $this->input->is_ajax_request();
        if ($is_ajax) {
            $this->output->set_content_type('application/json');
        }

        if (!$id_berlangganan) {
            $error_msg = 'ID Berlangganan tidak valid.';
            if ($is_ajax) {
                echo json_encode(['error' => $error_msg]);
                return;
            }
            $this->session->set_flashdata('error', $error_msg);
            redirect('tagihan/bulanan');
        }

        // Get data berlangganan
        $berlangganan = $this->db->get_where('berlangganan', ['id_berlangganan' => $id_berlangganan])->row();
        if (!$berlangganan) {
            $error_msg = 'Data berlangganan tidak ditemukan.';
            if ($is_ajax) {
                echo json_encode(['error' => $error_msg]);
                return;
            }
            $this->session->set_flashdata('error', $error_msg);
            redirect('tagihan/bulanan');
        }

        // Cek akses user
        $user_role = $this->session->userdata('role');
        if ($user_role == 'customer') {
            // Gunakan session id_customer untuk customer access (new schema)
            $customer_id = $this->session->userdata('id_customer');
            if (!$customer_id || $customer_id != $berlangganan->id_customer) {
                $error_msg = 'Anda tidak memiliki akses ke berlangganan ini.';
                if ($is_ajax) {
                    echo json_encode(['error' => $error_msg]);
                    return;
                }
                $this->session->set_flashdata('error', $error_msg);
                redirect('tagihan/bulanan');
            }
        }

        // Cek apakah berlangganan sudah lunas (status_bayar atau payment_status)
        if ($berlangganan->status_bayar == 'sudah bayar' || $berlangganan->payment_status == 'lunas') {
            $error_msg = 'Berlangganan ini sudah lunas. Tidak dapat melakukan pembayaran duplikat.';
            if ($is_ajax) {
                echo json_encode(['error' => $error_msg, 'already_paid' => true]);
                return;
            }
            $this->session->set_flashdata('info', $error_msg);
            redirect('tagihan/bulanan');
        }

        // Langsung gunakan id_berlangganan sebagai ID tagihan
        $id_tagihan = $id_berlangganan;
        $harga = $this->db->get_where('paket', ['id_paket' => $berlangganan->id_paket])->row()->harga;

        if ($is_ajax) {
            echo json_encode(['tagihan_id' => $id_tagihan]);
            return;
        }

        $this->session->set_flashdata('success', 'Tagihan berhasil dibuat.');
        redirect('payment/index/' . $id_tagihan);
    }

    /**
     * Debug endpoint untuk testing
     */
    public function debug() {
        header('Content-Type: application/json');

        // Debug session data
        $session_data = $this->session->userdata();
        log_message('debug', 'Session data: ' . json_encode($session_data));

        // Debug Midtrans config
        $this->load->config('midtrans');
        $midtrans_config = [
            'server_key' => $this->config->item('server_key', 'midtrans'),
            'client_key' => $this->config->item('client_key', 'midtrans'),
            'environment' => $this->config->item('environment', 'midtrans'),
            'is_production' => $this->config->item('environment', 'midtrans') === 'production'
        ];

        echo json_encode([
            'status' => 'success',
            'message' => 'Payment controller is working',
            'session' => $session_data,
            'midtrans_config' => $midtrans_config,
            'midtrans_lib_loaded' => class_exists('Midtrans_lib'),
            'user_role' => $this->session->userdata('role'),
            'user_id' => $this->session->userdata('id_user'),
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * API endpoint untuk mendapatkan snap token via AJAX
     */
    public function token($id_tagihan = null) {
        // Set JSON header FIRST
        $this->output->set_content_type('application/json');

        // Check login untuk method ini - return JSON error if not logged in
        if (!is_logged_in()) {
            $this->output->set_output(json_encode([
                'error' => 'Session expired. Silakan login kembali.',
                'redirect' => site_url('auth/login')
            ]));
            return;
        }

        if (!$id_tagihan) {
            $this->output->set_output(json_encode(['error' => 'ID Tagihan tidak valid']));
            return;
        }

        log_message('debug', 'Payment token requested for tagihan ID: ' . $id_tagihan);

        // Get data berlangganan sebagai tagihan
        try {
            $berlangganan = $this->Model_tagihan->get_berlangganan_by_id($id_tagihan);
            if (!$berlangganan) {
                log_message('error', 'Berlangganan not found for ID: ' . $id_tagihan);
                $this->output->set_output(json_encode(['error' => 'Data berlangganan tidak ditemukan']));
                return;
            }
        } catch (Exception $e) {
            log_message('error', 'Database error getting berlangganan ID: ' . $id_tagihan . ' - ' . $e->getMessage());
            $this->output->set_output(json_encode(['error' => 'Terjadi kesalahan database. Silakan coba lagi.']));
            return;
        }

        log_message('debug', 'Berlangganan found: ' . json_encode($berlangganan));

        // Debug session dan akses user
        $user_role = $this->session->userdata('role');
        $user_id = $this->session->userdata('id_user');

        log_message('debug', 'User role: ' . $user_role . ', User ID: ' . $user_id);
        log_message('debug', 'Berlangganan customer ID: ' . $berlangganan->id_customer);

        // Implement proper access control
        if ($user_role == 'customer') {
            // Get customer ID from session (id_customer for customers - new schema)
            $customer_id = $this->session->userdata('id_customer');
            if (!$customer_id || $customer_id != $berlangganan->id_customer) {
                log_message('debug', 'Customer access denied - Session Customer ID: ' . ($customer_id ?? 'null') . ', Berlangganan Customer ID: ' . $berlangganan->id_customer);
                $this->output->set_output(json_encode(['error' => 'Anda tidak memiliki akses ke berlangganan ini']));
                return;
            }
        }
        // Admin, finance, and other roles can access all berlangganan data

        // Check if already has pending payment with valid snap token (allow regeneration after cancel/expire)
        if ($berlangganan->payment_status == 'pending' && !empty($berlangganan->snap_token) && !empty($berlangganan->id_invoice)) {
            // Check if the last payment attempt was more than 30 minutes ago (token likely expired)
            $last_update = !empty($berlangganan->updated_at) ? strtotime($berlangganan->updated_at) : 0;
            $current_time = time();
            $time_diff = ($current_time - $last_update) / 60; // in minutes

            if ($time_diff > 30) {
                // Token is likely expired, allow regeneration
                log_message('info', 'Token expired for berlangganan ID: ' . $berlangganan->id_berlangganan . ', allowing regeneration');
            } else {
                // Return existing snap token instead of generating new one
                $this->output->set_output(json_encode([
                    'token' => $berlangganan->snap_token,
                    'order_id' => $berlangganan->id_invoice,
                    'existing' => true
                ]));
                return;
            }
        }

        // Generate unique order_id untuk setiap pembayaran attempt
        $order_id = 'INV-' . date('Ym') . '-' . str_pad($berlangganan->id_customer, 4, '0', STR_PAD_LEFT) . '-' . time() . '-' . $id_tagihan;

        // Prepare transaction details
        $gross_amount = (int)($berlangganan->harga ?? 0);
        if ($gross_amount === 0) {
            log_message('error', 'Package price is 0 for berlangganan ID: ' . $berlangganan->id_berlangganan);
            echo json_encode(['error' => 'Harga paket tidak valid. Silakan hubungi admin.']);
            return;
        }

        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => $gross_amount
        ];

        // Prepare customer details
        $customer_details = [
            'first_name' => $berlangganan->nama,
            'email' => $berlangganan->email ?? 'customer@example.com',
            'phone' => $berlangganan->no_hp ?? '08123456789'
        ];

        // Prepare item details
        $item_details = [[
            'id' => 'BRG-' . $id_tagihan,
            'price' => $gross_amount,
            'quantity' => 1,
            'name' => 'Pembayaran Langganan Internet - ' . ($berlangganan->nama_paket ?? 'Paket Internet'),
            'category' => 'Internet Service'
        ]];

        // Prepare transaction data
        $transaction_data = $this->midtrans_lib->prepare_transaction_data([
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details
        ]);

        log_message('debug', 'Transaction data prepared: ' . json_encode($transaction_data));

        // Get snap token
        try {
            log_message('debug', 'Getting snap token for order_id: ' . $order_id);
            $snap_token = $this->midtrans_lib->get_snap_token($transaction_data);
            log_message('debug', 'Snap token result: ' . ($snap_token ? 'SUCCESS' : 'FAILED'));

            if ($snap_token) {
                // Always update the snap token and id_invoice for existing berlangganan to allow regeneration
                $payment_data = [
                    'snap_token' => $snap_token,
                    'id_invoice' => $order_id, // Set id_invoice untuk webhook lookup
                    'payment_status' => 'pending',
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $this->db->where('id_berlangganan', $berlangganan->id_berlangganan);
                $update_result = $this->db->update('berlangganan', $payment_data);

                log_message('debug', 'Snap token and id_invoice updated for berlangganan ID: ' . $berlangganan->id_berlangganan .
                           ' with order_id: ' . $order_id . ' - Update result: ' . ($update_result ? 'SUCCESS' : 'FAILED'));

                $this->output->set_output(json_encode([
                    'token' => $snap_token,
                    'order_id' => $order_id,
                    'gross_amount' => $gross_amount
                ]));
            } else {
                log_message('error', 'Failed to generate snap token for tagihan: ' . $id_tagihan);
                $this->output->set_output(json_encode(['error' => 'Gagal membuat token pembayaran. Silakan coba lagi.']));
            }
        } catch (Exception $e) {
            log_message('error', 'Exception generating snap token: ' . $e->getMessage());
            $this->output->set_output(json_encode(['error' => 'Terjadi kesalahan saat membuat token pembayaran: ' . $e->getMessage()]));
        }
    }

    /**
     * Callback saat pembayaran selesai (user di-redirect ke sini)
     */
    public function finish() {
        // Cek login untuk method ini
        require_login();
        $order_id = $this->input->get('order_id');
        $status_code = $this->input->get('status_code');
        $transaction_status = $this->input->get('transaction_status');

        // Log payment attempt
        log_message('info', 'Payment Finish - Order ID: ' . $order_id . ', Status: ' . $transaction_status);

        // Get transaction data from Midtrans
        $transaction = $this->midtrans_lib->get_transaction_status($order_id);

        if ($transaction && $transaction->transaction_status === 'settlement') {
            // Update payment status
            $this->update_payment_status($order_id, 'lunas', $transaction);

            // Update berlangganan status
            $this->update_berlangganan_status($order_id, 'lunas');

            $data = [
                'title' => 'Pembayaran Berhasil',
                'status' => 'success',
                'message' => 'Pembayaran Anda telah berhasil diproses.',
                'transaction' => $transaction
            ];
        } elseif ($transaction && $transaction->transaction_status === 'pending') {
            $data = [
                'title' => 'Pembayaran Pending',
                'status' => 'pending',
                'message' => 'Pembayaran Anda sedang diproses. Silakan menunggu konfirmasi.',
                'transaction' => $transaction
            ];
        } else {
            $data = [
                'title' => 'Pembayaran Gagal',
                'status' => 'error',
                'message' => 'Pembayaran gagal. Silakan coba lagi.',
                'transaction' => $transaction ?? null
            ];
        }

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/payment/status', $data);
        $this->load->view('backend/templates/footer');
    }

    /**
     * Callback saat pembayaran error
     */
    public function error() {
        // Cek login untuk method ini
        require_login();
        $data = [
            'title' => 'Pembayaran Error',
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.'
        ];

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/payment/status', $data);
        $this->load->view('backend/templates/footer');
    }

    /**
     * Callback saat pembayaran pending
     */
    public function pending() {
        // Cek login untuk method ini
        require_login();
        $data = [
            'title' => 'Pembayaran Pending',
            'status' => 'pending',
            'message' => 'Pembayaran Anda sedang diproses. Status akan diperbarui setelah pembayaran dikonfirmasi.'
        ];

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/payment/status', $data);
        $this->load->view('backend/templates/footer');
    }

    /**
     * Webhook untuk notifikasi dari Midtrans (server-to-server)
     */
    public function notification() {
        try {
            // Enable CORS for Midtrans
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type');

            // Handle preflight OPTIONS request
            if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
                http_response_code(200);
                exit();
            }

            // Get raw notification data
            $raw_notification = file_get_contents('php://input');

            if (empty($raw_notification)) {
                log_message('error', 'Empty notification data received from Midtrans');
                http_response_code(400);
                echo 'Bad Request: Empty data';
                return;
            }

            // Log raw notification for debugging
            log_message('info', 'Midtrans Raw Notification: ' . $raw_notification);

            $notification_json = json_decode($raw_notification, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                log_message('error', 'Invalid JSON received from Midtrans: ' . json_last_error_msg());
                http_response_code(400);
                echo 'Bad Request: Invalid JSON';
                return;
            }

            // MANUAL NOTIFICATION HANDLER - Parse directly from JSON
            log_message('info', 'Processing notification manually...');

            $order_id = $notification_json['order_id'] ?? null;
            $transaction_status = $notification_json['transaction_status'] ?? $notification_json['status'] ?? null;
            $payment_type = $notification_json['payment_type'] ?? null;
            $transaction_id = $notification_json['transaction_id'] ?? null;
            $gross_amount = $notification_json['gross_amount'] ?? null;
            $transaction_time = $notification_json['transaction_time'] ?? date('Y-m-d H:i:s');

            if (!$order_id || !$transaction_status) {
                log_message('error', 'Missing required notification data: order_id=' . $order_id . ', status=' . $transaction_status);
                http_response_code(400);
                echo 'Bad Request: Missing required data';
                return;
            }

            // Log notification
            log_message('info', 'Midtrans Notification - Order: ' . $order_id . ', Status: ' . $transaction_status . ', Amount: ' . $gross_amount);

            // Process based on transaction status
            switch ($transaction_status) {
                case 'capture':
                    if (isset($notification_json['fraud_status']) && $notification_json['fraud_status'] == 'challenge') {
                        $this->update_payment_status_direct($order_id, 'challenge', $notification_json);
                    } else if (isset($notification_json['fraud_status']) && $notification_json['fraud_status'] == 'accept') {
                        $this->update_payment_status_direct($order_id, 'lunas', $notification_json);
                        $this->update_berlangganan_status_direct($order_id, 'lunas');
                    }
                    break;

                case 'settlement':
                    $this->update_payment_status_direct($order_id, 'lunas', $notification_json);
                    $this->update_berlangganan_status_direct($order_id, 'lunas');
                    log_message('info', 'Payment settled for order: ' . $order_id);
                    break;

                case 'pending':
                    $this->update_payment_status_direct($order_id, 'pending', $notification_json);
                    break;

                case 'deny':
                    $this->update_payment_status_direct($order_id, 'gagal', $notification_json);
                    break;

                case 'expire':
                    $this->update_payment_status_direct($order_id, 'kadaluarsa', $notification_json);
                    break;

                case 'cancel':
                    $this->update_payment_status_direct($order_id, 'batal', $notification_json);
                    break;

                default:
                    log_message('warning', 'Unknown transaction status: ' . $transaction_status);
                    break;
            }

            // Send success response to Midtrans
            http_response_code(200);
            header('Content-Type: text/plain');
            echo 'OK';

        } catch (Exception $e) {
            log_message('error', 'Exception in notification handler: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            http_response_code(500);
            echo 'Internal Server Error: ' . $e->getMessage();
        }
    }

    /**
     * Manual trigger payment update - untuk force update dari browser
     */
    public function manual_trigger_update($order_id = null) {
        header('Content-Type: application/json');

        if (!$order_id) {
            echo json_encode(['error' => 'Order ID required']);
            return;
        }

        try {
            log_message('info', 'Manual trigger update for order: ' . $order_id);

            // Get transaction data from Midtrans API
            $transaction = $this->midtrans_lib->get_transaction_status($order_id);

            if (!$transaction) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Failed to get transaction status from Midtrans'
                ]);
                return;
            }

            log_message('info', 'Transaction status from Midtrans: ' . $transaction->transaction_status);

            // Process based on transaction status
            $status = $transaction->transaction_status;
            $message = '';

            switch ($status) {
                case 'settlement':
                case 'capture':
                    $payment_status = 'lunas';
                    $this->update_payment_status($order_id, $payment_status, $transaction);
                    $this->update_berlangganan_status($order_id, $payment_status);
                    $message = 'Payment status updated to: LUNAS';
                    break;

                case 'pending':
                    $payment_status = 'pending';
                    $this->update_payment_status($order_id, $payment_status, $transaction);
                    $this->update_berlangganan_status($order_id, $payment_status);
                    $message = 'Payment status updated to: PENDING';
                    break;

                case 'deny':
                case 'expire':
                case 'cancel':
                    $payment_status = $status;
                    $this->update_payment_status($order_id, $payment_status, $transaction);
                    $this->update_berlangganan_status($order_id, $payment_status);
                    $message = 'Payment status updated to: ' . strtoupper($payment_status);
                    break;

                default:
                    $message = 'Unknown status: ' . $status;
                    break;
            }

            echo json_encode([
                'success' => true,
                'message' => $message,
                'order_id' => $order_id,
                'transaction_status' => $transaction->transaction_status,
                'timestamp' => date('Y-m-d H:i:s')
            ]);

        } catch (Exception $e) {
            log_message('error', 'Exception in manual_trigger_update: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage(),
                'order_id' => $order_id
            ]);
        }
    }

    /**
     * Update payment status in database
     */
    private function update_payment_status($order_id, $status, $transaction_data = null) {
        // Get berlangganan data first to have access to the amount
        $this->db->where('id_invoice', $order_id);
        $berlangganan = $this->db->get('berlangganan')->row();

        if (!$berlangganan) {
            log_message('error', 'No berlangganan found with id_invoice: ' . $order_id);
            return false;
        }

        // Get package amount from database if not provided in transaction_data
        // Handle both array and object format for transaction_data
        $amount = null;
        if ($transaction_data) {
            if (is_array($transaction_data)) {
                $amount = $transaction_data['gross_amount'] ?? null;
            } elseif (is_object($transaction_data)) {
                $amount = $transaction_data->gross_amount ?? null;
            }
        }

        if (!$amount && $berlangganan->id_paket) {
            $paket = $this->db->get_where('paket', ['id_paket' => $berlangganan->id_paket])->row();
            $amount = $paket->harga ?? 0;
        }

        // Extract transaction data properly (handle both array and object)
        $payment_type = 'payment_gateway';
        $transaction_time = date('Y-m-d H:i:s');

        if ($transaction_data) {
            if (is_array($transaction_data)) {
                $payment_type = $transaction_data['payment_type'] ?? 'payment_gateway';
                $transaction_time = $transaction_data['transaction_time'] ?? date('Y-m-d H:i:s');
            } elseif (is_object($transaction_data)) {
                $payment_type = $transaction_data->payment_type ?? 'payment_gateway';
                $transaction_time = $transaction_data->transaction_time ?? date('Y-m-d H:i:s');
            }
        }

        $update_data = [
            'payment_status' => $status,
            'payment_type' => $payment_type,
            'payment_method' => 'midtrans',
            'payment_time' => $transaction_time,
            'payment_amount' => $amount,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id_invoice', $order_id);
        $result = $this->db->update('berlangganan', $update_data);

        log_message('info', 'Updated payment status for order: ' . $order_id .
                   ', status: ' . $status .
                   ', amount: ' . $amount .
                   ', result: ' . ($result ? 'SUCCESS' : 'FAILED'));

        return $result;
    }

    /**
     * Update berlangganan status in database (now using berlangganan table directly)
     */
    private function update_berlangganan_status($order_id, $status) {
        // Find berlangganan record
        $this->db->where('id_invoice', $order_id);
        $berlangganan = $this->db->get('berlangganan')->row();

        if (!$berlangganan) {
            log_message('error', 'No berlangganan found with id_invoice: ' . $order_id);
            return false;
        }

        // Update payment status first
        $this->update_payment_status($order_id, $status);

        // FIXED: Use proper status mapping - 'pending' should use 'menunggu konfirmasi'
        // status that exists in database schema
        $status_mapping = [
            'lunas' => 'sudah bayar',
            'pending' => 'menunggu konfirmasi',  // FIXED: Use status that exists in database
            'gagal' => 'belum bayar',
            'kadaluarsa' => 'belum bayar',
            'batal' => 'belum bayar'
        ];

        $berlangganan_status = $status_mapping[$status] ?? 'belum bayar';

        // Update berlangganan status
        $update_data = [
            'status_bayar' => $berlangganan_status,
            'bukti_bayar' => ($status == 'lunas') ? 'MIDTRANS-' . $order_id : null,
            'id_transaksi' => $order_id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id_berlangganan', $berlangganan->id_berlangganan);
        $result = $this->db->update('berlangganan', $update_data);

        log_message('info', 'Updated berlangganan ID ' . $berlangganan->id_berlangganan .
                   ' status to: ' . $berlangganan_status .
                   ' with order ID: ' . $order_id);

        return $result;
    }

    /**
     * Direct update payment status - simplified version
     */
    private function update_payment_status_direct($order_id, $status, $notification_data) {
        try {
            // Find berlangganan by id_invoice
            $this->db->where('id_invoice', $order_id);
            $berlangganan = $this->db->get('berlangganan')->row();

            if (!$berlangganan) {
                log_message('error', 'No berlangganan found with id_invoice: ' . $order_id);
                return false;
            }

            log_message('info', 'Found berlangganan ID: ' . $berlangganan->id_berlangganan . ' for order: ' . $order_id);

            // Update data
            $update_data = [
                'payment_status' => $status,
                'payment_type' => $notification_data['payment_type'] ?? 'midtrans',
                'payment_method' => 'midtrans',
                'payment_time' => $notification_data['transaction_time'] ?? date('Y-m-d H:i:s'),
                'payment_amount' => $notification_data['gross_amount'] ?? null,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->db->where('id_berlangganan', $berlangganan->id_berlangganan);
            $result = $this->db->update('berlangganan', $update_data);

            log_message('info', 'Payment status update result: ' . ($result ? 'SUCCESS' : 'FAILED'));
            log_message('info', 'Updated payment for berlangganan ID ' . $berlangganan->id_berlangganan .
                       ' to status: ' . $status . ' for order: ' . $order_id);

            return $result;

        } catch (Exception $e) {
            log_message('error', 'Exception in update_payment_status_direct: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Direct update berlangganan status - simplified version
     */
    private function update_berlangganan_status_direct($order_id, $status) {
        try {
            // Find berlangganan record
            $this->db->where('id_invoice', $order_id);
            $berlangganan = $this->db->get('berlangganan')->row();

            if (!$berlangganan) {
                log_message('error', 'No berlangganan found with id_invoice: ' . $order_id);
                return false;
            }

            // FIXED: Use proper status mapping
            $status_mapping = [
                'lunas' => 'sudah bayar',
                'pending' => 'menunggu konfirmasi',  // Use status that exists in database
                'gagal' => 'belum bayar',
                'kadaluarsa' => 'belum bayar',
                'batal' => 'belum bayar'
            ];

            $berlangganan_status = $status_mapping[$status] ?? 'belum bayar';

            // Update berlangganan status
            $update_data = [
                'status_bayar' => $berlangganan_status,
                'bukti_bayar' => ($status == 'lunas') ? 'MIDTRANS-' . $order_id : null,
                'id_transaksi' => $order_id,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->db->where('id_berlangganan', $berlangganan->id_berlangganan);
            $result = $this->db->update('berlangganan', $update_data);

            log_message('info', 'Berlangganan status update result: ' . ($result ? 'SUCCESS' : 'FAILED'));
            log_message('info', 'Updated berlangganan ID ' . $berlangganan->id_berlangganan .
                       ' status to: ' . $berlangganan_status .
                       ' with order ID: ' . $order_id);

            return $result;

        } catch (Exception $e) {
            log_message('error', 'Exception in update_berlangganan_status_direct: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Debug endpoint untuk checking payment status in database
     */
    public function check_status($order_id = null) {
        // Set JSON header
        $this->output->set_content_type('application/json');

        if (!$order_id) {
            $this->output->set_output(json_encode(['error' => 'Order ID required']));
            return;
        }

        // Check berlangganan table
        $this->db->where('id_invoice', $order_id);
        $berlangganan = $this->db->get('berlangganan')->row();

        $response = [
            'order_id' => $order_id,
            'berlangganan_found' => $berlangganan ? true : false,
            'data' => null
        ];

        if ($berlangganan) {
            $response['data'] = [
                'id_berlangganan' => $berlangganan->id_berlangganan,
                'id_customer' => $berlangganan->id_customer,
                'payment_status' => $berlangganan->payment_status,
                'status_bayar' => $berlangganan->status_bayar,
                'payment_method' => $berlangganan->payment_method,
                'payment_time' => $berlangganan->payment_time,
                'payment_amount' => $berlangganan->payment_amount,
                'snap_token' => $berlangganan->snap_token ? substr($berlangganan->snap_token, 0, 20) . '...' : null,
                'bukti_bayar' => $berlangganan->bukti_bayar,
                'id_transaksi' => $berlangganan->id_transaksi,
                'updated_at' => $berlangganan->updated_at
            ];
        }

        // Also check if there are any recent log entries
        $response['recent_logs'] = 'Check application/logs for recent entries';

        $this->output->set_output(json_encode($response, JSON_PRETTY_PRINT));
    }

}