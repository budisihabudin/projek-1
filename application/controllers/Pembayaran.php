<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Model_tagihan');
        $this->load->helper(['form', 'url']);
        $this->load->library('upload');

        $role = $this->session->userdata('role');
        if (!$this->session->userdata('logged_in') || !in_array($role, ['finance', 'customer', 'admin'])) {
            redirect('auth/login');
        }
    }

    // BAYAR MANUAL (upload bukti pembayaran)
    public function bayar_manual($id_berlangganan)
    {
        $config['upload_path']   = './uploads/bukti_pembayaran/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size']      = 2048; // 2MB
        $config['encrypt_name']  = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('bukti_pembayaran')) {
            $this->session->set_flashdata('error', 'Gagal upload bukti pembayaran: ' . $this->upload->display_errors());
            redirect('tagihan/bulanan');
        } else {
            $upload_data = $this->upload->data();
            $bukti_path = 'uploads/bukti_pembayaran/' . $upload_data['file_name'];

            $data_update = [
                'bukti_bayar'  => $bukti_path,
                'status_bayar' => 'menunggu konfirmasi',
                'payment_status' => 'pending',
                'payment_method' => 'manual',
                'payment_time' => date('Y-m-d H:i:s'),
                'status'       => 'aktif',
            ];

            $this->Model_tagihan->update_berlangganan($id_berlangganan, $data_update);

            $this->session->set_flashdata('success', 'Bukti pembayaran berhasil diunggah dan menunggu verifikasi.');
            redirect('tagihan/bulanan');
        }
    }

    // BAYAR OTOMATIS (tanpa upload bukti)
    public function bayar_otomatis($id_berlangganan)
    {
        // Get berlangganan data to get package amount
        $berlangganan = $this->Model_tagihan->get_berlangganan_by_id($id_berlangganan);

        $data_update = [
            'status_bayar' => 'sudah bayar',
            'payment_status' => 'lunas',
            'payment_method' => 'cash', // atau 'transfer' untuk pembayaran otomatis non-tunai
            'payment_time' => date('Y-m-d H:i:s'),
            'payment_amount' => $berlangganan->harga ?? 0,
            'bukti_bayar' => 'PEMBAYARAN-OTOMATIS-' . date('Y-m-d-H-i-s'),
            'status'       => 'aktif',
        ];

        $this->Model_tagihan->update_berlangganan($id_berlangganan, $data_update);

        $this->session->set_flashdata('success', 'Pembayaran otomatis berhasil. Tagihan dinyatakan lunas.');
        redirect('tagihan/bulanan');
    }
}
