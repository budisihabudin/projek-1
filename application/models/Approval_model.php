<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval_model extends CI_Model {

    // Ambil semua request pending dari histori_berlangganan
    public function get_all($limit, $offset, $keyword = null) {
        $this->db->select('h.*, c.nama AS nama_customer, c.kode_customer, p.nama_paket, p.kecepatan');
        $this->db->from('histori_berlangganan h');
        $this->db->join('customer c', 'c.id_customer = h.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = h.id_paket', 'left');
        if ($this->session->userdata('role') =="noc") {
            // $this->db->where('h.instalasi', 'pending');
        }else{
            $this->db->where('h.status', 'pending');
        }

        if ($keyword) {
            $this->db->group_start();
            $this->db->like('c.nama', $keyword);
            $this->db->or_like('p.nama_paket', $keyword);
            $this->db->group_end();
        }

        // PERBAIKAN: Mengubah 'h.d_histori' menjadi 'h.id_histori' (sesuai asumsi kolom ID yang benar)
        $this->db->order_by('h.id_histori', 'DESC'); 
        
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    // Hitung total data untuk pagination
    public function count_all($keyword = null) {
        $this->db->from('histori_berlangganan h');
        $this->db->join('customer c', 'c.id_customer = h.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = h.id_paket', 'left');
        $this->db->where('h.status', 'pending');

        if ($keyword) {
            $this->db->group_start();
            $this->db->like('c.nama', $keyword);
            $this->db->or_like('p.nama_paket', $keyword);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    // Ambil satu data berdasarkan id_histori
    // PERBAIKAN: Mengubah parameter dan kolom WHERE menjadi id_histori
    public function get_by_id($id_histori) {
        $this->db->select('h.*, c.nama AS nama_customer, c.kode_customer, p.nama_paket, p.kecepatan');
        $this->db->from('histori_berlangganan h');
        $this->db->join('customer c', 'c.id_customer = h.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = h.id_paket', 'left');
        $this->db->where('h.id_histori', $id_histori);
        return $this->db->get()->row();
    }

    // Update status (approve/reject)
    // PERBAIKAN: Mengubah parameter dan kolom WHERE menjadi id_histori
    public function update_status($id_histori, $status) {
        return $this->db->where('id_histori', $id_histori)
                         ->update('histori_berlangganan', ['status' => $status]);
    }
}