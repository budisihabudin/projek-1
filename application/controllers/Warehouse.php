<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse extends CI_Controller {

    public function __construct(){
        parent::__construct();
        if(!$this->session->userdata('logged_in')){
            redirect('auth/login');
        }
        $this->load->model('Warehouse_model');
        $this->load->library('pagination');
    }

    // LIST BARANG MASUK
    public function masuk() {
        $data['title'] = "Warehouse - Barang Masuk";

        $keyword = $this->input->get('keyword');

        $config['base_url'] = site_url('warehouse/masuk');
        $config['total_rows'] = $this->Warehouse_model->count_all($keyword);
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $this->pagination->initialize($config);

        $page = $this->input->get('per_page') ?? 0;
        $data['barang'] = $this->Warehouse_model->get_all($config['per_page'], $page, $keyword);

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('warehouse/masuk', $data);
        $this->load->view('backend/templates/footer');
    }

    // CREATE FORM
    public function create_masuk() {
        $data['title'] = "Tambah Barang Masuk";
        $data['action'] = site_url('warehouse/store_masuk');

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('warehouse/form_masuk', $data);
        $this->load->view('backend/templates/footer');
    }

    // STORE
    public function store_masuk() {
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|integer');
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required');

        if($this->form_validation->run() == FALSE){
            $this->create_masuk();
        } else {
            $data = [
                'nama_barang' => $this->input->post('nama_barang'),
                'jumlah' => $this->input->post('jumlah'),
                'tanggal_masuk' => $this->input->post('tanggal_masuk'),
                'keterangan' => $this->input->post('keterangan')
            ];
            $this->Warehouse_model->insert($data);
            $this->session->set_flashdata('success', 'Barang berhasil ditambahkan.');
            redirect('warehouse/masuk');
        }
    }

    // EDIT FORM
    public function edit_masuk($id){
        $data['barang'] = $this->Warehouse_model->get($id);
        if(!$data['barang']) show_404();

        $data['title'] = "Edit Barang Masuk";
        $data['action'] = site_url('warehouse/update_masuk/'.$id);

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('warehouse/form_masuk', $data);
        $this->load->view('backend/templates/footer');
    }

    // UPDATE
    public function update_masuk($id){
        $data = [
            'nama_barang' => $this->input->post('nama_barang'),
            'jumlah' => $this->input->post('jumlah'),
            'tanggal_masuk' => $this->input->post('tanggal_masuk'),
            'keterangan' => $this->input->post('keterangan')
        ];
        $this->Warehouse_model->update($id, $data);
        $this->session->set_flashdata('success', 'Barang berhasil diupdate.');
        redirect('warehouse/masuk');
    }

    // DELETE
    public function delete_masuk($id){
        $this->Warehouse_model->delete($id);
        $this->session->set_flashdata('success', 'Barang berhasil dihapus.');
        redirect('warehouse/masuk');
    }

    // ===================== LIST BARANG KELUAR =====================
    public function keluar() {
        $data['title'] = "Warehouse - Barang Keluar";

        $keyword = $this->input->get('keyword');

        $config['base_url'] = site_url('warehouse/keluar');
        $config['total_rows'] = $this->Warehouse_model->count_all_keluar($keyword);
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $this->pagination->initialize($config);

        $page = $this->input->get('per_page') ?? 0;
        $data['barang'] = $this->Warehouse_model->get_all_keluar($config['per_page'], $page, $keyword);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('warehouse/keluar', $data);
        $this->load->view('backend/templates/footer');
    }

    // CREATE FORM
    public function create_keluar() {
        $data['title'] = "Tambah Barang Keluar";
        $data['action'] = site_url('warehouse/store_keluar');
        $data['barang_masuk'] = $this->Warehouse_model->get_barang_masuk();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('warehouse/form_keluar', $data);
        $this->load->view('backend/templates/footer');
    }

    // STORE
    public function store_keluar() {
        $this->form_validation->set_rules('id_warehouse_masuk', 'Nama Barang', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|integer');
        $this->form_validation->set_rules('tanggal_keluar', 'Tanggal Keluar', 'required');

        if($this->form_validation->run() == FALSE){
            $this->create_keluar();
        } else {
            $data = [
                'id_warehouse_masuk' => $this->input->post('id_warehouse_masuk'),
                'jumlah' => $this->input->post('jumlah'),
                'tanggal_keluar' => $this->input->post('tanggal_keluar'),
                'keterangan' => $this->input->post('keterangan')
            ];
            $this->Warehouse_model->insert_keluar($data);
            $this->session->set_flashdata('success', 'Barang keluar berhasil ditambahkan.');
            redirect('warehouse/keluar');
        }
    }

    // EDIT FORM
    public function edit_keluar($id) {
        $data['barang_keluar'] = $this->Warehouse_model->get_keluar($id);
        if(!$data['barang_keluar']) show_404();

        $data['title'] = "Edit Barang Keluar";
        $data['action'] = site_url('warehouse/update_keluar/'.$id);
        $data['barang_masuk'] = $this->Warehouse_model->get_barang_masuk();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('warehouse/form_keluar', $data);
        $this->load->view('backend/templates/footer');
    }

    // UPDATE
    public function update_keluar($id) {
        $data = [
            'id_warehouse_masuk' => $this->input->post('id_warehouse_masuk'),
            'jumlah' => $this->input->post('jumlah'),
            'tanggal_keluar' => $this->input->post('tanggal_keluar'),
            'keterangan' => $this->input->post('keterangan')
        ];
        $this->Warehouse_model->update_keluar($id, $data);
        $this->session->set_flashdata('success', 'Barang keluar berhasil diupdate.');
        redirect('warehouse/keluar');
    }

    // DELETE
    public function delete_keluar($id) {
        $this->Warehouse_model->delete_keluar($id);
        $this->session->set_flashdata('success', 'Barang keluar berhasil dihapus.');
        redirect('warehouse/keluar');
    }


    // LIST BARANG RUSAK
    public function rusak() {
        $data['title'] = "Warehouse - Barang Rusak";

        $keyword = $this->input->get('keyword');

        $config['base_url'] = site_url('warehouse/rusak');
        $config['total_rows'] = $this->Warehouse_model->count_all_rusak($keyword);
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $this->pagination->initialize($config);

        $page = $this->input->get('per_page') ?? 0;
        $data['barang'] = $this->Warehouse_model->get_all_rusak($config['per_page'], $page, $keyword);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('warehouse/rusak', $data);
        $this->load->view('backend/templates/footer');
    }

    // CREATE FORM
    public function create_rusak() {
        $data['title'] = "Tambah Barang Rusak";
        $data['action'] = site_url('warehouse/store_rusak');
        $data['barang_masuk'] = $this->Warehouse_model->get_barang_masuk();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('warehouse/form_rusak', $data);
        $this->load->view('backend/templates/footer');
    }

    // STORE
    public function store_rusak() {
        $this->form_validation->set_rules('id_warehouse_masuk', 'Nama Barang', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|integer');
        $this->form_validation->set_rules('tanggal_rusak', 'Tanggal Rusak', 'required');

        if($this->form_validation->run() == FALSE){
            $this->create_rusak();
        } else {
            $data = [
                'id_warehouse_masuk' => $this->input->post('id_warehouse_masuk'),
                'jumlah' => $this->input->post('jumlah'),
                'tanggal_rusak' => $this->input->post('tanggal_rusak'),
                'keterangan' => $this->input->post('keterangan')
            ];
            $this->Warehouse_model->insert_rusak($data);
            $this->session->set_flashdata('success', 'Barang rusak berhasil ditambahkan.');
            redirect('warehouse/rusak');
        }
    }

    // EDIT
    public function edit_rusak($id) {
        $data['barang_rusak'] = $this->Warehouse_model->get_rusak($id);
        if(!$data['barang_rusak']) show_404();

        $data['title'] = "Edit Barang Rusak";
        $data['action'] = site_url('warehouse/update_rusak/'.$id);
        $data['barang_masuk'] = $this->Warehouse_model->get_barang_masuk();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('warehouse/form_rusak', $data);
        $this->load->view('backend/templates/footer');
    }

    // UPDATE
    public function update_rusak($id) {
        $data = [
            'id_warehouse_masuk' => $this->input->post('id_warehouse_masuk'),
            'jumlah' => $this->input->post('jumlah'),
            'tanggal_rusak' => $this->input->post('tanggal_rusak'),
            'keterangan' => $this->input->post('keterangan')
        ];
        $this->Warehouse_model->update_rusak($id, $data);
        $this->session->set_flashdata('success', 'Barang rusak berhasil diupdate.');
        redirect('warehouse/rusak');
    }

    // DELETE
    public function delete_rusak($id) {
        $this->Warehouse_model->delete_rusak($id);
        $this->session->set_flashdata('success', 'Barang rusak berhasil dihapus.');
        redirect('warehouse/rusak');
    }



}
