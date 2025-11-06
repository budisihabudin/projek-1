<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subscriptions extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // Cek login menggunakan helper
        require_login();

        // Load model yang dibutuhkan
        $this->load->model('Paket_model');
        $this->load->model('Customer_model');
        $this->load->model('Histori_model');
        $this->load->model('Model_request');
        $this->load->model('Employee_model');
        $this->load->library('midtrans_lib');
    }

    public function index()
    {
        // Validasi role - hanya customer yang boleh akses halaman ini
        check_role_access(['customer', 'admin']);

        $keyword = $this->input->get('keyword');
        $data['title'] = "Subscriptions - ERP ISP";
        $data['keyword'] = $keyword;
        $data['paket'] = $this->Paket_model->get_all(null, null, $keyword);

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('subscriptions/index', $data);
        $this->load->view('backend/templates/footer');
    }


    public function add($id_paket)
    {
        $id = $id_paket;
        $data['title'] = 'Request Pemasangan';
        $data['paket'] = $this->Paket_model->get_by_id($id);
        $data['sales'] = $this->Employee_model->get_sales();

        // Get current user role
        $role = $this->session->userdata('role');

        // Debug: Check role and session data
        log_message('debug', 'Current role: ' . $role);
        log_message('debug', 'Session data: ' . json_encode($this->session->userdata()));

        // Load different form based on role
        if ($role === 'reseller') {
            // For resellers: show customer selection form
            $data['customers'] = $this->Customer_model->get_registered_customers();
            $this->load->view('backend/templates/header', $data);
            $this->load->view('backend/templates/sidebar');
            $this->load->view('backend/request/form_reseller_request', $data);
            $this->load->view('backend/templates/footer');
        } elseif ($role === 'customer') {
            // For customers: show existing customer form
            $id_user = $this->session->userdata('id_user');
            $data['customer'] = $this->Customer_model->get_by_user($id_user);

            // Debug customer data
            log_message('debug', 'Customer data: ' . json_encode($data['customer']));

            $this->load->view('backend/templates/header', $data);
            $this->load->view('backend/templates/sidebar');
            $this->load->view('backend/request/form_existing_customer_request', $data);
            $this->load->view('backend/templates/footer');
        } else {
            // For other roles: show original form
            log_message('debug', 'Loading original form for role: ' . $role);
            $this->load->view('backend/templates/header', $data);
            $this->load->view('backend/templates/sidebar');
            $this->load->view('backend/request/form_customer_request', $data);
            $this->load->view('backend/templates/footer');
        }
    }

    public function tambah_request()
    {
        $id_user = $this->session->userdata('id_user');
        $role = $this->session->userdata('role');

        if ($role === 'reseller') {
            // Handle reseller request (existing customer)
            $this->tambah_request_reseller();
        } elseif ($role === 'customer') {
            // Handle existing customer request
            $this->tambah_request_existing_customer();
        } else {
            // Handle original request (new customer)
            $this->tambah_request_new_customer();
        }
    }

    private function tambah_request_new_customer()
    {
        $id_user = $this->session->userdata('id_user');

        $nama_pelanggan = $this->input->post('nama_pelanggan');
        $alamat = $this->input->post('alamat');
        $no_hp = $this->input->post('no_hp');
        $email = $this->input->post('email');
        $instansi = $this->input->post('instansi');
        $pic = $this->input->post('pic');
        $id_paket = $this->input->post('id_paket');
        $tgl_mulai = $this->input->post('tgl_mulai');
        $lama_bulan = $this->input->post('lama_bulan');
        $id_sales = $this->input->post('id_sales');

        // 🔹 Buat kode customer berurutan (CUST-001, CUST-002, dst)
        $this->db->select('MAX(RIGHT(kode_customer,6)) as max_kode');
        $this->db->from('customer');
        $query = $this->db->get()->row();
        $kode_urut = $query ? (int)$query->max_kode + 1 : 1;
        $kode_customer = 'CUST-' . str_pad($kode_urut, 6, '0', STR_PAD_LEFT);

        // 🔹 Simpan data customer baru
        $data_customer = [
            'id_user'       => $id_user,
            'kode_customer' => $kode_customer,
            'nama'          => $nama_pelanggan,
            'alamat'        => $alamat,
            'no_hp'         => $no_hp,
            'email'         => $email,
            'status_customer'        => 'pending',
            'created_at'    => date('Y-m-d H:i:s'),
            'instansi'      => $instansi,
            'pic'           => $pic
        ];
        $this->Customer_model->insert_reques_customer($data_customer);
        $id_customer = $this->db->insert_id();

        // 🔹 Simpan ke tabel request pemasangan
        $data_request = [
            'id_customer'      => $id_customer,
            'id_paket'         => $id_paket,
            'tgl_langganan'    => $tgl_mulai,
            'lama_bulan'       => $lama_bulan,
            'approval_sales'   => 'pending',
            'approval_survei'  => 'pending',
            'approval_noc'     => 'pending',
            'approval_finance' => 'pending',
            'created_at'       => date('Y-m-d H:i:s'),
            'created_by'       => $id_sales
        ];
        $this->Model_request->insert_request($data_request);

        $this->session->set_flashdata('success', 'Request pemasangan berhasil diajukan.');
        redirect('dashboard');
    }

    private function tambah_request_reseller()
    {
        $id_user = $this->session->userdata('id_user');

        $id_customer = $this->input->post('id_customer');
        $id_paket = $this->input->post('id_paket');
        $tgl_mulai = $this->input->post('tgl_mulai');
        $lama_bulan = $this->input->post('lama_bulan');
        $id_sales = $this->input->post('id_sales');

        // 🔹 Simpan ke tabel request pemasangan (existing customer)
        $data_request = [
            'id_customer'      => $id_customer,
            'id_paket'         => $id_paket,
            'tgl_langganan'    => $tgl_mulai,
            'lama_bulan'       => $lama_bulan,
            'approval_sales'   => 'pending',
            'approval_survei'  => 'pending',
            'approval_noc'     => 'pending',
            'approval_finance' => 'pending',
            'created_at'       => date('Y-m-d H:i:s'),
            'created_by'       => $id_sales
        ];
        $this->Model_request->insert_request($data_request);

        $this->session->set_flashdata('success', 'Request pemasangan untuk customer berhasil diajukan.');
        redirect('dashboard');
    }

    private function tambah_request_existing_customer()
    {
        $id_user = $this->session->userdata('id_user');

        // Get customer data from current user
        $customer = $this->Customer_model->get_by_user($id_user);
        if (!$customer) {
            $this->session->set_flashdata('error', 'Data customer tidak ditemukan.');
            redirect('subscriptions');
        }

        $id_paket = $this->input->post('id_paket');
        $tgl_mulai = $this->input->post('tgl_mulai');
        $lama_bulan = $this->input->post('lama_bulan');
        $id_sales = $this->input->post('id_sales');

        // 🔹 Simpan ke tabel request pemasangan (existing customer)
        $data_request = [
            'id_customer'      => $customer->id_customer,
            'id_paket'         => $id_paket,
            'tgl_langganan'    => $tgl_mulai,
            'lama_bulan'       => $lama_bulan,
            'approval_sales'   => 'pending',
            'approval_survei'  => 'pending',
            'approval_noc'     => 'pending',
            'approval_finance' => 'pending',
            'created_at'       => date('Y-m-d H:i:s'),
            'created_by'       => $id_sales
        ];
        $this->Model_request->insert_request($data_request);

        $this->session->set_flashdata('success', 'Request pemasangan berhasil diajukan.');
        redirect('dashboard');
    }

    /**
     * Pembayaran langsung via Midtrans untuk paket langganan
     */
    public function bayar_langganan($id_paket) {
        // Validasi input
        if (!$id_paket) {
            $this->session->set_flashdata('error', 'ID Paket tidak valid.');
            redirect('subscriptions');
        }

        // Get data paket
        $paket = $this->Paket_model->get_by_id($id_paket);
        if (!$paket) {
            $this->session->set_flashdata('error', 'Paket tidak ditemukan.');
            redirect('subscriptions');
        }

        // Get user data
        $id_user = $this->session->userdata('id_user');
        $customer = $this->Customer_model->get_by_user($id_user);

        // Debug: Log data
        log_message('debug', 'DEBUG: id_user=' . $id_user);
        log_message('debug', 'DEBUG: customer=' . json_encode($customer));
        log_message('debug', 'DEBUG: paket=' . json_encode($paket));

        if (!$customer) {
            $this->session->set_flashdata('error', 'Data customer tidak ditemukan. Silakan lengkapi data customer terlebih dahulu.');
            redirect('subscriptions/form_customer/' . $id_paket);
        }

        // Generate order ID
        $order_id = 'LANGGANAN-' . $id_paket . '-' . time();

        // Prepare transaction details
        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => (int)$paket->harga
        ];

        // Prepare customer details
        $customer_details = [
            'first_name' => $customer->nama,
            'email' => $customer->email,
            'phone' => $customer->no_hp,
            'billing_address' => [
                'first_name' => $customer->nama,
                'address' => $customer->alamat,
                'city' => '',
                'postal_code' => '',
                'country_code' => 'IDN'
            ]
        ];

        // Prepare item details
        $item_details = [[
            'id' => 'PAKET-' . $id_paket,
            'price' => (int)$paket->harga,
            'quantity' => 1,
            'name' => 'Paket Langganan - ' . $paket->nama_paket,
            'category' => 'Internet Service'
        ]];

        // Prepare transaction data
        $transaction_data = $this->midtrans_lib->prepare_transaction_data([
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details
        ]);

        // Get snap token
        $snap_token = $this->midtrans_lib->get_snap_token($transaction_data);

        // Debug: Log snap token
        log_message('debug', 'DEBUG: transaction_data=' . json_encode($transaction_data));
        log_message('debug', 'DEBUG: snap_token=' . $snap_token);

        if (!$snap_token) {
            $this->session->set_flashdata('error', 'Gagal membuat token pembayaran. Silakan coba lagi.');
            log_message('error', 'ERROR: Failed to get snap token from Midtrans');
            redirect('subscriptions');
        }

        // Save payment pending to database
        $payment_data = [
            'id_user' => $id_user,
            'id_customer' => $customer->id_customer,
            'id_paket' => $id_paket,
            'order_id' => $order_id,
            'snap_token' => $snap_token,
            'jumlah' => $paket->harga,
            'status' => 'pending',
            'payment_type' => 'payment_gateway',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('pembayaran_langganan', $payment_data);
        $payment_id = $this->db->insert_id();

        $data = [
            'title' => 'Pembayaran Langganan',
            'paket' => $paket,
            'customer' => $customer,
            'snap_token' => $snap_token,
            'order_id' => $order_id,
            'payment_id' => $payment_id
        ];

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('subscriptions/payment', $data);
        $this->load->view('backend/templates/footer');
    }


    // public function berlangganan($id_paket) {
    // $id_user = $this->session->userdata('id_user');

    // // cek data customer
    // $customer = $this->Customer_model->get_by_user($id_user);

    // if ($customer) {
    //     $data_histori = [
    //         'id_user'        => $id_user,
    //         'id_customer'    => $customer->id_customer,
    //         'id_paket'       => $id_paket,
    //         'lama_langganan' => $customer->lama_langganan ?? 0,
    //         'status'         => 'pending',
    //         'tgl_langganan'  => date('Y-m-d H:i:s')
    //     ];

    //     $this->Histori_model->insert_histori($data_histori);

    //     $this->session->set_flashdata('success', 'Permintaan langganan berhasil dikirim. Menunggu approval admin.');
    //     redirect('subscriptions/history');
    // } else {
    //     redirect('subscriptions/form_customer/' . $id_paket);
    // }
    // }


    public function form_customer($id_paket)
    {
        $data['title'] = "Isi Data Customer Baru";
        $data['kode_customer'] = $this->Customer_model->generate_kode_customer();
        $data['paket'] = $this->Paket_model->get_by_id($id_paket);

        $this->load->view('backend/templates/header', $data);

        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('subscriptions/form_customer', $data);
        $this->load->view('backend/templates/footer');
    }

    public function simpan_customer()
    {
        $id_user = $this->session->userdata('id_user');
        $id_paket = $this->input->post('id_paket');

        // Cek apakah sudah terdaftar
        $existing = $this->Customer_model->get_by_user($id_user);
        if ($existing) {
            $this->session->set_flashdata('error', 'Anda sudah terdaftar sebagai customer.');
            redirect('subscriptions/history');
        }

        $data_customer = [
            'id_user'       => $id_user,
            'kode_customer' => $this->input->post('kode_customer'),
            'nama'          => $this->input->post('nama'),
            'alamat'        => $this->input->post('alamat'),
            'no_hp'         => $this->input->post('no_hp'),
            'email'         => $this->input->post('email'),
            'id_paket'      => $id_paket,
            'lama_langganan' => $this->input->post('lama_langganan'),
            'status'        => 'pending',
            'tgl_daftar'    => date('Y-m-d H:i:s')
        ];

        $this->db->insert('customer', $data_customer);
        $this->session->set_flashdata('success', 'Pendaftaran customer berhasil, menunggu approval admin.');
        redirect('subscriptions/history');
    }

    public function history()
    {
        // Validasi role - hanya customer yang boleh akses halaman ini
        check_role_access(['customer', 'admin']);

        $this->load->library('pagination');

        $id_user = $this->session->userdata('id_user');
        $keyword = $this->input->get('keyword');

        // Konfigurasi pagination
        $config['base_url'] = site_url('subscriptions/history');
        $config['total_rows'] = $this->Customer_model->count_riwayat($id_user, $keyword);
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;

        // Styling Bootstrap
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);

        $page = ($this->input->get('page')) ? $this->input->get('page') : 0;

        $data['title'] = 'Riwayat Langganan Saya';
        $data['keyword'] = $keyword;
        $data['riwayat'] = $this->Customer_model->get_riwayat_paginate($id_user, $config['per_page'], $page, $keyword);
        $data['pagination'] = $this->pagination->create_links();

        // Load view
        $this->load->view('backend/templates/header', $data);

        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('subscriptions/history', $data);
        $this->load->view('backend/templates/footer');
    }


    // === Request Instalasi ===



    public function batal_request($id_histori)
    {
        $this->db->where('id_histori', $id_histori);
        $this->db->delete('histori_berlangganan');
        $this->session->set_flashdata('success', 'Request berhasil dibatalkan.');
        redirect('subcriptions/reques');
    }

    // === CRUD LENGKAPI DOKUMEN ===
    public function dokumen()
    {
        $data['title'] = 'Lengkapi Dokumen';
        $data['dokumen'] = $this->Subcriptions_model->get_all_dokumen();
        $this->load->view('templates/backend/header', $data);
        $this->load->view('templates/backend/sidebar');
        $this->load->view('templates/backend/navbar');
        $this->load->view('backend/subcriptions/dokumen_index', $data);
        $this->load->view('templates/backend/footer');
    }

    public function add_dokumen()
    {
        $data['title'] = 'Tambah Dokumen';
        if ($this->input->post()) {
            $this->Subcriptions_model->insert_dokumen($this->input->post());
            redirect('subcriptions/dokumen');
        }
        $this->load->view('templates/backend/header', $data);
        $this->load->view('templates/backend/sidebar');
        $this->load->view('templates/backend/navbar');
        $this->load->view('backend/subcriptions/dokumen_form', $data);
        $this->load->view('templates/backend/footer');
    }

    public function edit_dokumen($id)
    {
        $data['title'] = 'Edit Dokumen';
        $data['dokumen'] = $this->Subcriptions_model->get_dokumen_by_id($id);
        if ($this->input->post()) {
            $this->Subcriptions_model->update_dokumen($id, $this->input->post());
            redirect('subcriptions/dokumen');
        }
        $this->load->view('templates/backend/header', $data);
        $this->load->view('templates/backend/sidebar');
        $this->load->view('templates/backend/navbar');
        $this->load->view('backend/subcriptions/dokumen_form', $data);
        $this->load->view('templates/backend/footer');
    }

    public function delete_dokumen($id)
    {
        $this->Subcriptions_model->delete_dokumen($id);
        redirect('subcriptions/dokumen');
    }

    /**
     * API endpoint untuk mendapatkan snap token via AJAX untuk langganan
     */
    public function token($id_paket = null) {
        if (!$id_paket) {
            echo json_encode(['error' => 'ID Paket tidak valid']);
            return;
        }

        // Get data paket
        $paket = $this->Paket_model->get_by_id($id_paket);
        if (!$paket) {
            echo json_encode(['error' => 'Paket tidak ditemukan']);
            return;
        }

        // Get user data
        $id_user = $this->session->userdata('id_user');
        $customer = $this->Customer_model->get_by_user($id_user);

        if (!$customer) {
            echo json_encode(['error' => 'Data customer tidak ditemukan. Silakan lengkapi data customer terlebih dahulu.']);
            return;
        }

        // Generate order ID
        $order_id = 'LANGGANAN-' . $id_paket . '-' . time();

        // Prepare transaction details
        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => (int)$paket->harga
        ];

        // Prepare customer details
        $customer_details = [
            'first_name' => $customer->nama,
            'email' => $customer->email,
            'phone' => $customer->no_hp,
            'billing_address' => [
                'first_name' => $customer->nama,
                'address' => $customer->alamat,
                'city' => '',
                'postal_code' => '',
                'country_code' => 'IDN'
            ]
        ];

        // Prepare item details
        $item_details = [[
            'id' => 'PAKET-' . $id_paket,
            'price' => (int)$paket->harga,
            'quantity' => 1,
            'name' => 'Paket Langganan - ' . $paket->nama_paket,
            'category' => 'Internet Service'
        ]];

        // Prepare transaction data
        $transaction_data = $this->midtrans_lib->prepare_transaction_data([
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details
        ]);

        // Get snap token
        $snap_token = $this->midtrans_lib->get_snap_token($transaction_data);

        if ($snap_token) {
            // Save payment pending to database
            $payment_data = [
                'id_user' => $id_user,
                'id_customer' => $customer->id_customer,
                'id_paket' => $id_paket,
                'order_id' => $order_id,
                'snap_token' => $snap_token,
                'jumlah' => $paket->harga,
                'status' => 'pending',
                'payment_type' => 'payment_gateway',
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->db->insert('pembayaran_langganan', $payment_data);

            echo json_encode(['token' => $snap_token, 'order_id' => $order_id]);
        } else {
            echo json_encode(['error' => 'Gagal membuat token pembayaran']);
        }
    }

    /**
     * Halaman sukses pembayaran langganan
     */
    public function payment_success() {
        $order_id = $this->input->get('order_id');
        $status_code = $this->input->get('status_code');
        $transaction_status = $this->input->get('transaction_status');

        // Update status pembayaran
        $this->db->where('order_id', $order_id);
        $this->db->update('pembayaran_langganan', [
            'status' => 'lunas',
            'payment_time' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Ambil data pembayaran
        $payment = $this->db->get_where('pembayaran_langganan', ['order_id' => $order_id])->row();

        if ($payment) {
            // Buat berlangganan baru
            $berlangganan_data = [
                'id_customer' => $payment->id_customer,
                'id_paket' => $payment->id_paket,
                'tgl_mulai' => date('Y-m-d'),
                'tgl_berakhir' => date('Y-m-d', strtotime('+1 month')),
                'status' => 'aktif',
                'status_bayar' => 'sudah bayar',
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->db->insert('berlangganan', $berlangganan_data);
        }

        $data = [
            'title' => 'Pembayaran Berhasil!',
            'status' => 'success',
            'message' => 'Pembayaran langganan Anda berhasil! Paket internet akan segera aktif.',
            'order_id' => $order_id
        ];

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('subscriptions/payment_status', $data);
        $this->load->view('backend/templates/footer');
    }

    /**
     * Halaman pending pembayaran langganan
     */
    public function payment_pending() {
        $data = [
            'title' => 'Pembayaran Pending',
            'status' => 'pending',
            'message' => 'Pembayaran Anda sedang diproses. Status akan diperbarui setelah pembayaran dikonfirmasi.',
            'order_id' => $this->input->get('order_id')
        ];

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('subscriptions/payment_status', $data);
        $this->load->view('backend/templates/footer');
    }

    /**
     * Halaman error pembayaran langganan
     */
    public function payment_error() {
        $data = [
            'title' => 'Pembayaran Gagal',
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.',
            'order_id' => $this->input->get('order_id')
        ];

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('subscriptions/payment_status', $data);
        $this->load->view('backend/templates/footer');
    }
}
