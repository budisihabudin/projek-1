<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survei extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (
            !$this->session->userdata('logged_in') ||
            !in_array($this->session->userdata('role'), ['admin','sales','teknisi','surveyor'])
        ) {
            redirect('auth/login');
        }

        $this->load->model('Survei_model');
        $this->load->model('Dokumen_model');
        $this->load->library('upload'); 
    }

    // List survei + pagination + search
    public function index() {
        $keyword = $this->input->get('keyword');
        $page    = $this->input->get('page') ?? 1;
        $limit   = 10;
        $offset  = ($page - 1) * $limit;

        $data['title'] = 'Approval Request Survei';
        $data['survei'] = $this->Survei_model->get_all($limit, $offset, $keyword);
        $data['total'] = $this->Survei_model->count_all($keyword);
        $data['page']  = $page;
        $data['limit'] = $limit;
        $data['keyword'] = $keyword;

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar');
        $this->load->view('survei/index', $data);
        $this->load->view('backend/templates/footer');
    }

    // Form edit survei
    public function edit($id_histori) {
        $survei = $this->Survei_model->get_by_id($id_histori);
        if(!$survei) show_404();

        // Ambil daftar teknisi
        $this->db->select('employee.*, users.username, users.role');
        $this->db->from('employee');
        $this->db->join('users', 'users.id_user = employee.id_employee'); 
        $this->db->where('users.role', 'surveyor');
        $employees = $this->db->get()->result();

        $data['title']     = 'Edit Survei';
        $data['survei']    = $survei;
        $data['employees'] = $employees;

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar');
        $this->load->view('survei/edit', $data);
        $this->load->view('backend/templates/footer');
    }

    
     // Proses update survei
    // Proses update survei
public function update($id_histori)
{
    // 1. Cek apakah data histori survei ditemukan
    $survei_lama = $this->Survei_model->get_by_id($id_histori);
    if (!$survei_lama) {
        $this->session->set_flashdata('error', 'Data survei tidak ditemukan.');
        redirect('survei');
        return;
    }

    // --- 2. Data Awal dan Inisialisasi ---
    $id_employee = $this->input->post('id_employee');
    $aktivitas   = $this->input->post('aktivitas');
    
    // Siapkan data untuk update. Default foto_survei adalah foto lama.
    $data = [
        'aktivitas'   => $aktivitas,
        'modified_by' => $this->session->userdata('id_user'),
        'foto_survei' => $survei_lama->foto_survei 
    ];

    // Penanganan id_employee kondisional untuk Manager Surveyor
    if ($this->session->userdata('nama_jabatan') == "Manager Surveyor" && $id_employee !== null) {
        $data['id_employee'] = $id_employee;
    } else {
        $data['id_employee'] = $survei_lama->id_employee;
    }


    // --- 3. Konfigurasi Upload ---
    // DISESUAIKAN: Menggunakan relative path './' seperti pada fungsi upload_dokumen yang berhasil
    $config['upload_path']   = './uploads/survei/'; 
    $config['allowed_types'] = 'jpg|jpeg|png';
    $config['max_size']      = 2048; // 2MB
    $config['file_name']     = 'survei_' . time(); // Nama file unik

    // Buat folder jika belum ada
    if (!is_dir($config['upload_path'])) {
        // Gunakan path yang sama untuk mkdir
        mkdir($config['upload_path'], 0777, true); 
    }

    // Load library (jika belum dimuat) dan Inisialisasi konfigurasi baru
    // DISESUAIKAN: Menggunakan initialize() seperti pada fungsi upload_dokumen
    $this->load->library('upload'); 
    $this->upload->initialize($config);

    // --- 4. Proses Upload File Baru ---
    if (!empty($_FILES['foto_survei']['name'])) {
        if ($this->upload->do_upload('foto_survei')) {
            $upload_data = $this->upload->data();
            $data['foto_survei'] = $upload_data['file_name']; // Timpa dengan nama file baru

            // Hapus foto lama jika ada dan upload berhasil
            if (!empty($survei_lama->foto_survei)) {
                // Gunakan path yang konsisten (relative path) untuk unlink
                $path_lama = $config['upload_path'] . $survei_lama->foto_survei;
                if (file_exists($path_lama)) {
                    unlink($path_lama);
                }
            }
        } else {
            // Jika gagal upload
            $this->session->set_flashdata('error', 'Gagal upload foto: ' . strip_tags($this->upload->display_errors()));
            redirect('survei/edit/' . $id_histori);
            return;
        }
    } 

    // --- 5. Update ke Database ---
    $update_result = $this->Survei_model->update($id_histori, $data);

    if ($update_result) {
        $this->session->set_flashdata('success', 'Data survei berhasil diupdate.');
    } else {
        $this->session->set_flashdata('error', 'Gagal menyimpan perubahan data survei ke database.');
    }
    
    redirect('survei');
}


    // Hapus survei
    public function delete($id_histori) {
        $this->Survei_model->delete($id_histori);
        $this->session->set_flashdata('success', 'Data survei berhasil dihapus.');
        redirect('survei');
    }


     // ------------------------------------------------------------------
    // Metode untuk Menampilkan Halaman Detail Dokumen (Formulir Upload)
    // ------------------------------------------------------------------
    public function dokumen_detail($id_histori) {
        $data['title'] = 'Kelola Dokumen Langganan';
        $data['histori'] = $this->Survei_model->get_by_id($id_histori);
        $data['dokumen'] = $this->Dokumen_model->get_by_histori($id_histori);

        if (!$data['histori']) {
            show_404();
        }

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar');
        $this->load->view('dokumen/detail_dokumen', $data);
        $this->load->view('backend/templates/footer');
    }
    
    // ------------------------------------------------------------------
    // Metode untuk Memproses Upload File
    // ------------------------------------------------------------------
    public function upload_dokumen() {
        $id_histori = $this->input->post('id_histori');
        $tipe_dokumen = $this->input->post('tipe_dokumen');

        if (empty($id_histori) || empty($tipe_dokumen)) {
             $this->session->set_flashdata('error', 'ID Histori atau Tipe Dokumen tidak ditemukan.');
             redirect('approval/dokumen_detail/' . $id_histori);
        }

        // Konfigurasi Upload
        $config['upload_path']      = './uploads/dokumen_langganan/';
        $config['allowed_types']    = 'pdf|doc|docx|jpg|jpeg|png';
        $config['max_size']         = 5000; // 5MB
        $config['file_name']        = $tipe_dokumen . '_' . $id_histori . '_' . time();

        $this->upload->initialize($config);

        // Coba Upload
        if ( ! $this->upload->do_upload('file_dokumen')) {
            // Gagal Upload
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error', 'Gagal upload file: ' . strip_tags($error));
        } else {
            // Berhasil Upload
            $upload_data = $this->upload->data();
            $file_path = $upload_data['file_name']; // Cukup simpan nama file
            
            // Simpan path ke database
            $insert = $this->Dokumen_model->insert_dokumen($id_histori, $tipe_dokumen, $file_path);

            if ($insert) {
                $this->session->set_flashdata('success', 'Dokumen ' . $tipe_dokumen . ' berhasil di-upload dan disimpan.');
            } else {
                 // Jika insert gagal, hapus file yang sudah terupload
                unlink($config['upload_path'] . $file_path);
                $this->session->set_flashdata('error', 'Gagal menyimpan data ke database.');
            }
        }

        redirect('survei/dokumen_detail/' . $id_histori);
    }

}
