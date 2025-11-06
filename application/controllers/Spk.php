<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Spk extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'admin') {
            redirect('auth/login');
        }
        $this->load->model('Spk_model');
        $this->load->model('Request_model'); 
        $this->load->model('Teknisi_model');    
        
    }

    public function index()
    {
        $this->load->library('pagination');

        $keyword = $this->input->get('q');
        $page    = $this->input->get('per_page');
        $limit   = 10; // jumlah data per halaman
        $offset  = $page ? $page : 0;

        $total = $this->Spk_model->count_all($keyword);

        $config['base_url']    = site_url('spk/index?q=' . urlencode($keyword));
        $config['total_rows'] = $total;
        $config['per_page']    = $limit;
        $config['page_query_string'] = TRUE;
        
        // Konfigurasi Bootstrap (Opsional, asumsikan Anda pakai styling default CI atau bootstrap)
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');


        $this->pagination->initialize($config);

        $data['title']       = "SPK - ERP ISP";
        $data['spk']         = $this->Spk_model->get_all($limit, $offset, $keyword);
        
        
        $data['keyword']    = $keyword;
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('spk/index', $data);
        $this->load->view('backend/templates/footer');
    }

    public function edit($id)
    {
        $data['spk']       = $this->Spk_model->get_by_id($id);
        $data['requests']  = $this->Request_model->get_pending(); 
        $data['teknisi']   = $this->Teknisi_model->get_teknisi(); 
        $data['title']     = "Edit SPK - ERP ISP";

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('spk/edit', $data);
        $this->load->view('backend/templates/footer');
    }

    public function update($id)
    {
        // Kode SPK tidak diubah di fungsi update
        
        $data = [
            'spk'         => $this->input->post('spk'),
            'tgl_spk'     => $this->input->post('tgl_spk'),
            'keterangan'  => $this->input->post('keterangan')
        ];
        
       

        $this->db->where('id_spk', $id);
        $this->db->update('spk', $data);

        $this->session->set_flashdata('success', 'SPK berhasil dirubah!');
        redirect('spk');
    }


    public function create()
    {
        if ($this->input->post()) {
            
            
            $kode_spk = $this->Spk_model->generate_spk_code(); 
            
            
            $inserted = $this->Spk_model->insert_data($kode_spk); 
            
            if (!$inserted) {
                $this->session->set_flashdata('error', 'Gagal membuat SPK. Cek konfigurasi database.');
                redirect('spk/create');
            }

            $this->session->set_flashdata('success', 'SPK berhasil dibuat dengan Kode: ' . $kode_spk);
            redirect('spk');
        } else {
            $data['title']      = "Tambah SPK - ERP ISP";
            $data['requests']   = $this->Request_model->get_pending();    
            $data['teknisi']    = $this->Teknisi_model->get_teknisi(); 
            
            
            $data['kode_spk_preview'] = $this->Spk_model->generate_spk_code(true); 

            $this->load->view('backend/templates/header', $data);
            $this->load->view('backend/templates/sidebar', $data);
            $this->load->view('spk/create', $data);
            $this->load->view('backend/templates/footer');
        }
    }


    public function delete($id)
    {
        $this->Spk_model->delete($id);
        $this->session->set_flashdata('success', 'SPK berhasil dihapus!');
        redirect('spk');
    }
}