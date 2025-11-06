<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Model_request');
        $this->load->model('Customer_model');
        $this->load->model('Paket_model');
        $this->load->model('Invoice_model');

        $role = $this->session->userdata('role');
        $is_logged_in = $this->session->userdata('logged_in');

        if (!$is_logged_in || !in_array($role, ['admin', 'sales','surveyor','noc','finance'])) {
            redirect('auth/login');
        }
    }

    // reques pemasangan
    public function index() {
        $data['title'] = "Request Pemasangan";
        $this->load->library('pagination');

        $keyword = $this->input->get('keyword');
        $config['base_url'] = site_url('request/index');
        $config['total_rows'] = $this->Model_request->count_all_request_customer($keyword);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['requests'] = $this->Model_request->get_all_request_customer($config['per_page'], $page, $keyword);
        $data['pagination'] = $this->pagination->create_links();

        // Check if user is customer
        if ($this->session->userdata('role') == 'customer') {
            // Load customer-specific view
            $this->load->view('backend/templates/header', $data);
            $this->load->view('backend/templates/sidebar', $data);
            $this->load->view('backend/request/customer_index', $data);
            $this->load->view('backend/templates/footer');
        } else {
            // Load admin/employee view
            $this->load->view('backend/templates/header', $data);
            $this->load->view('backend/templates/sidebar', $data);
            $this->load->view('backend/request/index', $data);
            $this->load->view('backend/templates/footer');
        }
    }

    public function add() {
        $data['title'] = 'Request Pemasangan';
        $data['paket'] = $this->Paket_model->get_all_paket();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/form', $data);
        $this->load->view('backend/templates/footer');
    }

    public function tambah_request() {
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
            'created_at'       => date('Y-m-d H:i:s')
        ];
        $this->Model_request->insert_request($data_request);

        $this->session->set_flashdata('success', 'Request pemasangan berhasil diajukan.');
        redirect('request');
    }

    public function detail_request() {
        $id = $this->input->get('id');
        $request = $this->Model_request->get_request_by_id($id);
        echo json_encode($request);
    }

    // enf reques pemasangan

// ============================================================================================

    // reques survei
    public function survei_index() {
        $data['title'] = "Request Survei";
        $this->load->library('pagination');

        $keyword = $this->input->get('keyword');
        $config['base_url'] = site_url('request/survei_index');
        $config['total_rows'] = $this->Model_request->count_all_survei($keyword);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['surveis'] = $this->Model_request->get_all_survei($config['per_page'], $page, $keyword);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/survei_index', $data);
        $this->load->view('backend/templates/footer');
    }

    public function add_survei($id_request = null) {
        $data['title'] = "Tambah Request Survei";
        $data['id_request'] = $id_request;

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/form_survei', $data);
        $this->load->view('backend/templates/footer');
    }

    public function tambah_survei() {
        $id_request = $this->input->post('id_request');
        $tgl_survei = $this->input->post('tgl_survei');
        $catatan = $this->input->post('catatan');

        $data = [
            'id_request' => $id_request,
            'tgl_survei' => $tgl_survei,
            'catatan'    => $catatan,
            'status'     => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->Model_request->insert_survei($data);
        $this->session->set_flashdata('success', 'Request survei berhasil ditambahkan.');
        redirect('request/survei_index');
    }

    public function detail_survei() {
        $id = $this->input->get('id');
        $survei = $this->Model_request->get_survei_by_id($id);
        echo json_encode($survei);
    }


    // lakukan survei
    public function pilih_surveyor($id_survei) {
        $data['title'] = "Pilih Surveyor";
        $data['id_survei'] = $id_survei;
        $data['survei'] = $this->Model_request->get_survei_by_id($id_survei);
        $data['surveyor_list'] = $this->Model_request->get_surveyor_list();

        if (!$data['survei']) {
            $this->session->set_flashdata('error', 'Data Request Survei tidak ditemukan.');
            redirect('request/survei_index');
        }

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/form_pilih_surveyor', $data);
        $this->load->view('backend/templates/footer');
    }

    public function pilih_surveyors($id_survei) {
        $id_surveyor = $this->input->post('id_surveyor');
        $data = [
            'id_surveyor' => $id_surveyor
        ];

        $this->Model_request->update_survei($id_survei, $data);
        $this->session->set_flashdata('success', 'Surveyor berhasil dipilih.');
        redirect('request/survei_index');
    }

    // Proses penugasan surveyor (untuk handle form submit)
    public function proses_pilih_surveyor() {
        if ($this->session->userdata('nama_jabatan') != 'Manager Surveyor') {
            redirect('request/survei_index');
        }

        $id_survei = $this->input->post('id_survei');
        $id_employee = $this->input->post('id_employee');

        if (empty($id_survei) || empty($id_employee)) {
             $this->session->set_flashdata('error', 'Data tidak lengkap.');
             redirect('request/survei_index');
        }

        $data_update = [
            'id_surveyor' => $id_employee,
            'status' => "proses"
        ];

        $this->Model_request->update_survei($id_survei, $data_update);
        $this->session->set_flashdata('success', 'Surveyor berhasil ditugaskan.');
        redirect('request/survei_index');
    }

    // delete sur
    public function delete_survei($id_survei) {
        $this->Model_request->delete_survei($id_survei);
        $this->session->set_flashdata('success', 'Survei berhasil dihapus.');
        redirect('request/survei_index');
    }

    // approve sur
    public function approval_survei($id_survei) {
        // Get data survei untuk dapatkan id_request
        $this->db->select('id_request');
        $this->db->where('id_survei', $id_survei);
        $survei = $this->db->get('tb_request_survei')->row();

        if (!$survei) {
            $this->session->set_flashdata('error', 'Data survei tidak ditemukan.');
            redirect('request/survei_index');
            return;
        }

        // Update tb_request_survei - status jadi done, approved_by_manager jadi approved
        $this->db->where('id_survei', $id_survei);
        $this->db->update('tb_request_survei', [
            'status' => 'done',
            'approved_by_manager' => 'approved',
            'manager_approval_time' => date('Y-m-d H:i:s')
        ]);

        // Update tb_request_pemasangan - approval_survei jadi approved
        $this->db->where('id_request', $survei->id_request);
        $this->db->update('tb_request_pemasangan', [
            'approval_survei' => 'approved'
        ]);

        $this->session->set_flashdata('success', 'Survei berhasil disetujui dan approval manager telah dicatat.');
        redirect('request/survei_index');
    }

    
    // cancel sur
    public function cancel_survei($id_survei) {
        $data = [
            'status' => 'cancel'
        ];

        $this->Model_request->update_survei($id_survei, $data);
        $this->session->set_flashdata('success', 'Survei berhasil dibatalkan.');
        redirect('request/survei_index');
    }


    // ============================================================================================

    // reques instalasi
    public function instalasi() {
        $data['title'] = "Request Instalasi";
        $this->load->library('pagination');

        $keyword = $this->input->get('keyword');
        $config['base_url'] = site_url('request/instalasi');
        $config['total_rows'] = $this->Model_request->count_all_instalasi($keyword);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['requests_instalasi'] = $this->Model_request->get_all_instalasi($config['per_page'], $page, $keyword);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/instalasi', $data);
        $this->load->view('backend/templates/footer');
    }

    public function add_instalasi($id_request = null)
    {
        $data['title'] = "Tambah Request Instalasi";
        $data['id_request'] = $id_request;

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/form_instalasi', $data);
        $this->load->view('backend/templates/footer');
    }

    public function tambah_instalasi()
    {
        $id_request = $this->input->post('id_request');
        $id_paket = $this->input->post('id_paket');
        $id_customer = $this->input->post('id_customer');
        $ip_customer = $this->input->post('ip_customer');

        // Update request approval status
        $this->db->where('id_request', $id_request);
        $this->db->update('tb_request_pemasangan', [
            'approval_noc' => 'approved'
        ]);

        // Check if record already exists in instalasi customer
        $this->db->where('id_request', $id_request);
        $existing = $this->db->get('tb_instalasi_customer')->row();

        if ($existing) {
            // Update existing record
            $this->db->where('id_request', $id_request);
            $this->db->update('tb_instalasi_customer', [
                'ip_customer' => $ip_customer
            ]);
        } else {
            // Create new record (fallback for existing requests)
            $this->Model_request->insert_instalasi_customer([
                'id_customer' => $id_customer,
                'id_request' => $id_request,
                'id_paket'    => $id_paket,
                'ip_customer' => $ip_customer,
                'created_by'  => $this->session->userdata('id_employee'),
                'created_at'  => date('Y-m-d H:i:s')
            ]);
        }

        $this->session->set_flashdata('success', 'Request instalasi berhasil ditambahkan.');
        redirect('request/instalasi');
    }

    public function detail_instalasi() {
        $id = $this->input->get('id');
        $request = $this->Model_request->get_instalasi_by_id($id);
        echo json_encode($request);
    }

    public function update_instalasi($id_request)
    {
        // Ambil inputan form
        $ip_customer = $this->input->post('ip_customer');

        // Ambil data request
        $request = $this->Model_request->get_request_by_id($id_request);
        if (!$request) {
            $this->session->set_flashdata('error', 'Request instalasi tidak ditemukan.');
            redirect('request/instalasi');
        }

        // Update approval status di tb_request_pemasangan
        $this->db->where('id_request', $id_request);
        $this->db->update('tb_request_pemasangan', [
            'approval_noc' => 'approved'
        ]);

        // Update existing record di tb_instalasi_customer (hanya IP customer)
        $this->db->where('id_request', $id_request);
        $this->db->update('tb_instalasi_customer', [
            'ip_customer' => $ip_customer
        ]);

        $this->session->set_flashdata('success', 'Data instalasi berhasil diperbarui.');
        redirect('request/instalasi');
    }

    /**
     * Edit instalasi request
     */
    public function edit_instalasi($id_request) {
        // Ambil data request instalasi
        $request = $this->Model_request->get_request_by_id($id_request);
        if (!$request) {
            $this->session->set_flashdata('error', 'Request instalasi tidak ditemukan.');
            redirect('request/instalasi');
        }

        $data['title'] = "Instalasi Customer";
        $data['request'] = $request;

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/form_edit_instalasi', $data);
        $this->load->view('backend/templates/footer');
    }

    /**
     * Finance approval - generate invoice
     */
    public function approve_finance($id_request) {
        // Hanya finance yang bisa approve
        $role = $this->session->userdata('role');
        if ($role != 'finance' && $role != 'admin') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk approve finance');
            redirect('request');
        }

        if (!$id_request) {
            $this->session->set_flashdata('error', 'ID Request tidak valid');
            redirect('request');
        }

        // Get request data
        $request = $this->Model_request->get_request_by_id($id_request);
        if (!$request) {
            $this->session->set_flashdata('error', 'Request tidak ditemukan');
            redirect('request');
        }

        // Check apakah semua tahapan sebelum finance sudah approved
        if ($request->approval_sales != 'approved' ||
            $request->approval_survei != 'approved' ||
            $request->approval_noc != 'approved') {
            $this->session->set_flashdata('error', 'Request harus melalui approval Sales, Survey, dan NOC terlebih dahulu');
            redirect('request');
        }

        if ($request->approval_finance == 'approved') {
            $this->session->set_flashdata('info', 'Request ini sudah di-approve oleh Finance');
            redirect('request');
        }

        try {
            // Generate invoice
            $invoice_result = $this->Invoice_model->generate_invoice_from_request($id_request);

            if ($invoice_result) {
                // Update finance approval
                $this->db->where('id_request', $id_request);
                $this->db->update('tb_request_pemasangan', [
                    'approval_finance' => 'approved'
                ]);

                $this->session->set_flashdata('success',
                    'Request berhasil di-approve dan invoice ' . $invoice_result['invoice_no'] .
                    ' berhasil digenerate untuk ' . $invoice_result['customer_name']);

                log_message('info', 'Finance approved request: ' . $id_request . ', Invoice: ' . $invoice_result['invoice_no']);

            } else {
                $this->session->set_flashdata('error', 'Gagal generate invoice');
            }

        } catch (Exception $e) {
            log_message('error', 'Exception in approve_finance: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        redirect('request');
    }

    /**
     * AJAX endpoint untuk finance approval
     */
    public function ajax_approve_finance() {
        header('Content-Type: application/json');

        // Check role
        $role = $this->session->userdata('role');
        if ($role != 'finance' && $role != 'admin') {
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            return;
        }

        $id_request = $this->input->post('id_request');
        if (!$id_request) {
            echo json_encode(['success' => false, 'message' => 'ID Request tidak valid']);
            return;
        }

        try {
            // Get request data
            $request = $this->Model_request->get_request_by_id($id_request);
            if (!$request) {
                echo json_encode(['success' => false, 'message' => 'Request tidak ditemukan']);
                return;
            }

            // Check prerequisites
            if ($request->approval_sales != 'approved' ||
                $request->approval_survei != 'approved' ||
                $request->approval_noc != 'approved') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Request harus melalui approval Sales, Survey, dan NOC terlebih dahulu',
                    'current_status' => [
                        'sales' => $request->approval_sales,
                        'survey' => $request->approval_survei,
                        'noc' => $request->approval_noc
                    ]
                ]);
                return;
            }

            if ($request->approval_finance == 'approved') {
                echo json_encode(['success' => false, 'message' => 'Request sudah di-approve']);
                return;
            }

            // Generate invoice
            $invoice_result = $this->Invoice_model->generate_invoice_from_request($id_request);

            if ($invoice_result) {
                // Update finance approval
                $this->db->where('id_request', $id_request);
                $this->db->update('tb_request_pemasangan', [
                    'approval_finance' => 'approved'
                ]);

                echo json_encode([
                    'success' => true,
                    'message' => 'Request berhasil di-approve dan invoice digenerate!',
                    'invoice_no' => $invoice_result['invoice_no'],
                    'id_berlangganan' => $invoice_result['id_berlangganan'],
                    'redirect_url' => site_url('tagihan/bulanan')
                ]);

            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal generate invoice']);
            }

        } catch (Exception $e) {
            log_message('error', 'Exception in ajax_approve_finance: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /*end reques instalasi noc*/

    // ===================== Upload Dokumen Survei =====================

    public function survei_dokumen() {
        $data['title'] = "Daftar Dokumen Survei";

        $this->load->library('pagination');
        $keyword = $this->input->get('keyword');

        $config['base_url'] = site_url('request/survei_dokumen');
        $config['total_rows'] = $this->Model_request->count_all_dokumen($keyword);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['dokumens'] = $this->Model_request->get_all_dokumen_paginate($config['per_page'], $page, $keyword);
        $data['pagination'] = $this->pagination->create_links();
        $data['keyword'] = $keyword;

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/dokumen_survei_index', $data);
        $this->load->view('backend/templates/footer');
    }

    public function dokumen_survei($id_survei) {
        $data['title'] = "Dokumen Survei";
        $data['id_survei'] = $id_survei;
        $data['dokumens'] = $this->Model_request->get_dokumen_by_survei($id_survei);

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/form_upload_survei', $data);
        $this->load->view('backend/templates/footer');
    }

    public function tambah_dokumen() {
        $id_survei = $this->input->post('id_survei');
        $keterangan = $this->input->post('keterangan_foto');

        $config['upload_path'] = './uploads/survei/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size'] = 5000; // 5MB
        $config['encrypt_name'] = true;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('foto_survei')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
        } else {
            $upload_data = $this->upload->data();
            $data = [
                'id_survei' => $id_survei,
                'foto_survei' => $upload_data['file_name'],
                'keterangan_foto' => $keterangan
            ];
            $this->Model_request->insert_dokumen($data);
            $this->session->set_flashdata('success', 'Dokumen survei berhasil diupload.');
        }
        redirect('request/dokumen_survei/'.$id_survei);
    }

    public function tambah_dokumen_multi() {
        $id_survei = $this->input->post('id_survei');
        $keterangan_all = $this->input->post('keterangan_foto');
        $keterangan_array = explode('|', $keterangan_all);

        if (!empty($_FILES['foto_survei']['name'])) {
            $files = $_FILES['foto_survei'];
            $count = count($files['name']);
            for ($i = 0; $i < $count; $i++) {
                $_FILES['file']['name']     = $files['name'][$i];
                $_FILES['file']['type']     = $files['type'][$i];
                $_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['file']['error']    = $files['error'][$i];
                $_FILES['file']['size']     = $files['size'][$i];

                $config['upload_path'] = './uploads/survei/';
                $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                $config['max_size'] = 5000;
                $config['encrypt_name'] = true;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) {
                    $upload_data = $this->upload->data();
                    $ses = $this->session->userdata('id_employee');
                    $data = [
                        'id_survei' => $id_survei,
                        'id_surveyor' => $ses,
                        'foto_survei' => $upload_data['file_name'],
                        'keterangan_foto' => $keterangan_array[$i] ?? ''
                    ];
                    $this->Model_request->insert_dokumen($data);
                }
            }
            $this->session->set_flashdata('success', 'Semua dokumen berhasil diupload.');
        } else {
            $this->session->set_flashdata('error', 'Tidak ada file yang dipilih.');
        }

        redirect('request/dokumen_survei/'.$id_survei);
    }

    public function edit_dokumen($id_dokumen) {
        $dokumen = $this->Model_request->get_dokumen($id_dokumen);
        if (!$dokumen) {
            $this->session->set_flashdata('error', 'Dokumen tidak ditemukan.');
            redirect('request/survei_index');
        }

        $id_survei = $dokumen->id_survei;
        $keterangan = $this->input->post('keterangan_foto');

        $config['upload_path'] = './uploads/survei/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size'] = 5000;
        $config['encrypt_name'] = true;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('foto_survei')) {
            if ($dokumen->foto_survei && file_exists('./uploads/survei/'.$dokumen->foto_survei)) {
                unlink('./uploads/survei/'.$dokumen->foto_survei);
            }
            $upload_data = $this->upload->data();
            $data = [
                'foto_survei' => $upload_data['file_name'],
                'keterangan_foto' => $keterangan
            ];
        } else {
            $data = ['keterangan_foto' => $keterangan];
        }

        $this->Model_request->update_dokumen($id_dokumen, $data);
        $this->session->set_flashdata('success', 'Dokumen survei berhasil diperbarui.');
        redirect('request/dokumen_survei/'.$id_survei);
    }

    public function hapus_dokumen($id_dokumen) {
        $dokumen = $this->Model_request->get_dokumen($id_dokumen);
        if ($dokumen) {
            if ($dokumen->foto_survei && file_exists('./uploads/survei/'.$dokumen->foto_survei)) {
                unlink('./uploads/survei/'.$dokumen->foto_survei);
            }
            $this->Model_request->delete_dokumen($id_dokumen);
            $this->session->set_flashdata('success', 'Dokumen survei berhasil dihapus.');
            redirect('request/dokumen_survei/'.$dokumen->id_survei);
        } else {
            $this->session->set_flashdata('error', 'Dokumen tidak ditemukan.');
            redirect('request/survei_index');
        }
    }

    // ===================== Dokumen Customer =====================

    public function dokumen_customer() {
        $data['title'] = "Dokumen Customer";
        $this->load->library('pagination');

        $keyword = $this->input->get('keyword');

        $config['base_url'] = site_url('request/dokumen_customer');
        $config['total_rows'] = $this->Model_request->count_all_dokumen_customer($keyword);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['dokumens'] = $this->Model_request->get_all_dokumen_customer_paginate($config['per_page'], $page, $keyword);
        $data['pagination'] = $this->pagination->create_links();
        $data['keyword'] = $keyword;

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/dokumen_customer_index', $data);
        $this->load->view('backend/templates/footer');
    }

    public function tambah_dokumen_customer() {
        $data['title'] = "Upload Dokumen Customer";
        $data['customer_done_survei_reques'] = $this->Model_request->get_customer_done_survei_reques();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/form_upload_customer', $data);
        $this->load->view('backend/templates/footer');
    }

    public function simpan_dokumen_customer() {
        $id_customer = $this->input->post('id_customer');
        $keterangan = $this->input->post('keterangan_foto');

        $config['upload_path'] = './uploads/customer/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size'] = 5000; // 5MB
        $config['encrypt_name'] = true;

        $this->load->library('upload', $config);

        if (!empty($_FILES['foto_customer']['name'])) {
            $files = $_FILES['foto_customer'];
            $count = count($files['name']);
            for ($i = 0; $i < $count; $i++) {
                $_FILES['file']['name']     = $files['name'][$i];
                $_FILES['file']['type']     = $files['type'][$i];
                $_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['file']['error']    = $files['error'][$i];
                $_FILES['file']['size']     = $files['size'][$i];

                $this->upload->initialize($config);
                if ($this->upload->do_upload('file')) {
                    $upload_data = $this->upload->data();
                    $data = [
                        'id_customer' => $id_customer,
                        'foto_customer' => $upload_data['file_name'],
                        'keterangan_foto' => $keterangan[$i] ?? ''
                    ];
                    $this->Model_request->insert_dokumen_customer($data);
                }
            }
            $this->session->set_flashdata('success', 'Dokumen berhasil diupload.');
        } else {
            $this->session->set_flashdata('error', 'Tidak ada file yang dipilih.');
        }

        redirect('request/dokumen_customer');
    }

    public function edit_dokumen_customer($id_dokumen_customer) {
        $dokumen = $this->Model_request->get_dokumen_customer_by_id($id_dokumen_customer);

        if (!$dokumen) {
            $this->session->set_flashdata('error', 'Dokumen tidak ditemukan.');
            redirect('request/dokumen_customer');
        }

        $data['title'] = "Edit Dokumen Customer";
        $data['dokumen'] = $dokumen;

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/form_edit_upload_customer', $data);
        $this->load->view('backend/templates/footer');
    }

    public function update_dokumen_customer($id_dokumen_customer) {
        $dokumen = $this->Model_request->get_dokumen_customer_by_id($id_dokumen_customer);
        if (!$dokumen) {
            $this->session->set_flashdata('error', 'Dokumen tidak ditemukan.');
            redirect('request/dokumen_customer');
        }

        $keterangan = $this->input->post('keterangan_foto');

        $config['upload_path'] = './uploads/customer/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size'] = 5000;
        $config['encrypt_name'] = true;

        $this->load->library('upload', $config);

        if (!empty($_FILES['foto_customer']['name']) && $this->upload->do_upload('foto_customer')) {
            if ($dokumen->foto_customer && file_exists('./uploads/customer/'.$dokumen->foto_customer)) {
                unlink('./uploads/customer/'.$dokumen->foto_customer);
            }
            $upload_data = $this->upload->data();
            $data = [
                'foto_customer' => $upload_data['file_name'],
                'keterangan_foto' => $keterangan
            ];
        } else {
            $data = ['keterangan_foto' => $keterangan];
        }

        $this->Model_request->update_dokumen_customer($id_dokumen_customer, $data);
        $this->session->set_flashdata('success', 'Dokumen berhasil diperbarui.');
        redirect('request/dokumen_customer');
    }

    public function hapus_dokumen_customer($id_dokumen_customer) {
        $dokumen = $this->Model_request->get_dokumen_customer_by_id($id_dokumen_customer);
        if ($dokumen) {
            if ($dokumen->foto_customer && file_exists('./uploads/customer/'.$dokumen->foto_customer)) {
                unlink('./uploads/customer/'.$dokumen->foto_customer);
            }
            $this->Model_request->delete_dokumen_customer($id_dokumen_customer);
            $this->session->set_flashdata('success', 'Dokumen berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Dokumen tidak ditemukan.');
        }
        redirect('request/dokumen_customer');
    }

    /*end dokumen customer*/

    // Dashboard untuk masing-masing role
    public function dashboard_sales() {
        $data['title'] = "Dashboard Sales";

        // Get requests yang perlu dihandle sales
        $keyword = $this->input->get('keyword');
        $data['requests'] = $this->Model_request->get_all_request_customer(50, 0, $keyword);

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/dashboard_sales', $data);
        $this->load->view('backend/templates/footer');
    }

    public function dashboard_surveyor() {
        $data['title'] = "Dashboard Surveyor";

        // Get survei yang perlu dihandle surveyor
        $keyword = $this->input->get('keyword');
        $data['surveis'] = $this->Model_request->get_all_survei(50, 0, $keyword);

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/dashboard_surveyor', $data);
        $this->load->view('backend/templates/footer');
    }

    public function dashboard_noc() {
        $data['title'] = "Dashboard NOC";

        // Get instalasi yang perlu dihandle NOC
        $keyword = $this->input->get('keyword');
        $data['requests'] = $this->Model_request->get_all_instalasi(50, 0, $keyword);

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/dashboard_noc', $data);
        $this->load->view('backend/templates/footer');
    }

    public function dashboard_finance() {
        $data['title'] = "Dashboard Finance";

        // Get requests yang siap di-generate invoice
        $keyword = $this->input->get('keyword');
        $data['requests'] = $this->Model_request->get_all_request_customer(50, 0, $keyword);

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/request/dashboard_finance', $data);
        $this->load->view('backend/templates/footer');
    }

    // Approval Methods
    public function approve_sales_req($id_request) {
        if ($this->session->userdata('role') != 'sales') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk approve request.');
            redirect('request');
        }

        // Get request data to create instalasi customer record
        $request = $this->Model_request->get_request_by_id($id_request);
        if (!$request) {
            $this->session->set_flashdata('error', 'Request tidak ditemukan.');
            redirect('request');
        }

        // Update sales approval
        $this->db->where('id_request', $id_request);
        $this->db->update('tb_request_pemasangan', [
            'approval_sales' => 'approved'
        ]);

        // Create record in tb_instalasi_customer (auto-create after sales approval)
        $instalasi_data = [
            'id_customer' => $request->id_customer,
            'id_paket'    => $request->id_paket,
            'id_request'  => $id_request,
            'ip_customer' => null, // Will be filled by NOC later
            'created_by'  => $this->session->userdata('id_employee') ?: $this->session->userdata('id_user'),
            'created_at'  => date('Y-m-d H:i:s')
        ];

        // Check if record already exists to avoid duplicates
        $this->db->where('id_request', $id_request);
        $existing = $this->db->get('tb_instalasi_customer')->row();

        if (!$existing) {
            $this->Model_request->insert_instalasi_customer($instalasi_data);
        }

        $this->session->set_flashdata('success', 'Request berhasil di-approve oleh Sales! Data instalasi telah dibuat.');
        redirect('request');
    }

    public function reject_sales_req($id_request) {
        if ($this->session->userdata('role') != 'sales') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk reject request.');
            redirect('request');
        }

        $this->db->where('id_request', $id_request);
        $this->db->update('tb_request_pemasangan', [
            'approval_sales' => 'rejected'
        ]);

        $this->session->set_flashdata('error', 'Request di-reject oleh Sales.');
        redirect('request');
    }

    public function approve_survei_req($id_request) {
        if ($this->session->userdata('nama_jabatan') != 'Manager Surveyor') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk approve survei.');
            redirect('request');
        }

        // Cek apakah sudah ada record di tb_request_survei
        $this->db->where('id_request', $id_request);
        $existing_survei = $this->db->get('tb_request_survei')->row();

        if (!$existing_survei) {
            // Create record di tb_request_survei untuk consistency
            $survei_data = [
                'id_request' => $id_request,
                'tgl_survei' => date('Y-m-d'),
                'catatan' => 'Auto-created by direct approval',
                'status' => 'done',
                'approved_by_manager' => 'approved',
                'manager_approval_time' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('tb_request_survei', $survei_data);
        } else {
            // Update existing record
            $this->db->where('id_request', $id_request);
            $this->db->update('tb_request_survei', [
                'status' => 'done',
                'approved_by_manager' => 'approved',
                'manager_approval_time' => date('Y-m-d H:i:s')
            ]);
        }

        // Update tb_request_pemasangan
        $this->db->where('id_request', $id_request);
        $this->db->update('tb_request_pemasangan', [
            'approval_survei' => 'approved'
        ]);

        $this->session->set_flashdata('success', 'Survei berhasil di-approve!');
        redirect('request');
    }

    public function reject_survei_req($id_request) {
        if ($this->session->userdata('nama_jabatan') != 'Manager Surveyor') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk reject survei.');
            redirect('request');
        }

        // Cek apakah sudah ada record di tb_request_survei
        $this->db->where('id_request', $id_request);
        $existing_survei = $this->db->get('tb_request_survei')->row();

        if (!$existing_survei) {
            // Create record di tb_request_survei untuk consistency
            $survei_data = [
                'id_request' => $id_request,
                'tgl_survei' => date('Y-m-d'),
                'catatan' => 'Auto-created by direct rejection',
                'status' => 'cancel',
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('tb_request_survei', $survei_data);
        } else {
            // Update existing record
            $this->db->where('id_request', $id_request);
            $this->db->update('tb_request_survei', [
                'status' => 'cancel'
            ]);
        }

        // Update tb_request_pemasangan
        $this->db->where('id_request', $id_request);
        $this->db->update('tb_request_pemasangan', [
            'approval_survei' => 'rejected'
        ]);

        $this->session->set_flashdata('error', 'Survei di-reject!');
        redirect('request');
    }

    public function approve_noc_req($id_request) {
        if ($this->session->userdata('role') != 'noc') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk approve instalasi.');
            redirect('request');
        }

        $this->db->where('id_request', $id_request);
        $this->db->update('tb_request_pemasangan', [
            'approval_noc' => 'approved'
        ]);

        $this->session->set_flashdata('success', 'Instalasi berhasil di-approve oleh NOC!');
        redirect('request');
    }

    public function reject_noc_req($id_request) {
        if ($this->session->userdata('role') != 'noc') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk reject instalasi.');
            redirect('request');
        }

        $this->db->where('id_request', $id_request);
        $this->db->update('tb_request_pemasangan', [
            'approval_noc' => 'rejected'
        ]);

        $this->session->set_flashdata('error', 'Instalasi di-reject oleh NOC!');
        redirect('request');
    }

  
}