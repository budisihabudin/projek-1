<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'../vendor/autoload.php';

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction;

class Midtrans_lib {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->config('midtrans');

        // Konfigurasi Midtrans
        Config::$serverKey = $this->CI->config->item('server_key', 'midtrans');
        Config::$clientKey = $this->CI->config->item('client_key', 'midtrans');
        Config::$isSanitized = $this->CI->config->item('sanitized', 'midtrans');
        Config::$is3ds = $this->CI->config->item('3ds_secure', 'midtrans');

        // Set environment
        $environment = $this->CI->config->item('environment', 'midtrans');
        if ($environment === 'production') {
            Config::$isProduction = true;
        } else {
            Config::$isProduction = false;
        }
    }

    /**
     * Create Snap Token untuk pembayaran
     * @param array $transaction_data
     * @return string Snap Token
     */
    public function get_snap_token($transaction_data) {
        try {
            // Log the transaction data before sending
            log_message('debug', 'Midtrans Request Data: ' . json_encode($transaction_data));

            // Get current config
            log_message('debug', 'Midtrans Config - Server Key: ' . substr(Config::$serverKey, 0, 10) . '...');
            log_message('debug', 'Midtrans Config - Is Production: ' . (Config::$isProduction ? 'YES' : 'NO'));
            log_message('debug', 'Midtrans Config - Environment: ' . (Config::$isProduction ? 'production' : 'sandbox'));

            $snap_token = Snap::getSnapToken($transaction_data);

            if ($snap_token) {
                log_message('debug', 'Midtrans Snap Token generated successfully');
            } else {
                log_message('error', 'Midtrans Snap Token returned empty/null');
            }

            return $snap_token;
        } catch (Exception $e) {
            log_message('error', 'Midtrans Snap Token Exception: ' . $e->getMessage());
            log_message('error', 'Midtrans Exception Trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Prepare transaction data
     * @param array $params
     * @return array
     */
    public function prepare_transaction_data($params) {
        $default_params = [
            'enabled_payments' => $this->CI->config->item('enabled_payments', 'midtrans'),
            'expiry' => [
                'unit' => 'minutes',
                'duration' => $this->CI->config->item('expiry_duration', 'midtrans')
            ],
            'custom_field1' => $this->CI->config->item('custom_field1', 'midtrans'),
            'custom_field2' => $this->CI->config->item('custom_field2', 'midtrans'),
            'custom_field3' => $this->CI->config->item('custom_field3', 'midtrans'),
            'callbacks' => [
                'finish' => base_url('payment/finish'),
                'error' => base_url('payment/error'),
                'pending' => base_url('payment/pending'),
                'notification' => base_url('payment/notification')
            ]
        ];

        return array_merge($default_params, $params);
    }

    /**
     * Handle notification dari Midtrans
     * @return array|false
     */
    public function handle_notification() {
        try {
            $notification = new Notification();
            return [
                'status' => $notification->transaction_status,
                'order_id' => $notification->order_id,
                'fraud_status' => $notification->fraud_status,
                'payment_type' => $notification->payment_type,
                'gross_amount' => $notification->gross_amount,
                'transaction_id' => $notification->transaction_id,
                'transaction_time' => $notification->transaction_time,
                'approval_code' => $notification->approval_code,
                'signature_key' => $notification->signature_key
            ];
        } catch (Exception $e) {
            log_message('error', 'Midtrans Notification Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get transaction status
     * @param string $order_id
     * @return object|false
     */
    public function get_transaction_status($order_id) {
        try {
            return Transaction::status($order_id);
        } catch (Exception $e) {
            log_message('error', 'Midtrans Status Check Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Approve transaction
     * @param string $order_id
     * @return bool
     */
    public function approve_transaction($order_id) {
        try {
            Transaction::approve($order_id);
            return true;
        } catch (Exception $e) {
            log_message('error', 'Midtrans Approve Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cancel transaction
     * @param string $order_id
     * @return bool
     */
    public function cancel_transaction($order_id) {
        try {
            Transaction::cancel($order_id);
            return true;
        } catch (Exception $e) {
            log_message('error', 'Midtrans Cancel Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Refund transaction
     * @param string $order_id
     * @param array $params
     * @return bool
     */
    public function refund_transaction($order_id, $params = []) {
        try {
            Transaction::refund($order_id, $params);
            return true;
        } catch (Exception $e) {
            log_message('error', 'Midtrans Refund Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if notification is valid
     * @param array $data
     * @return bool
     */
    public function verify_notification_signature($data) {
        if (!isset($data['order_id']) || !isset($data['status_code']) ||
            !isset($data['gross_amount']) || !isset($data['signature_key'])) {
            return false;
        }

        $server_key = Config::$serverKey;
        $input = $data['order_id'] . $data['status_code'] . $data['gross_amount'] . $server_key;
        $signature = openssl_digest($input, 'sha512');

        return $signature === $data['signature_key'];
    }
}