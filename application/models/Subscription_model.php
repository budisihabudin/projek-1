<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscription_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function request_subscription($data) {
        $this->db->insert('permintaan_langganan', $data);
    }

    public function get_pending_requests() {
        $this->db->select('pl.*, u.username, c.nama AS nama_customer, pk.nama_paket');
        $this->db->from('permintaan_langganan pl');
        $this->db->join('user u', 'u.id_user = pl.id_user', 'left');
        $this->db->join('customer c', 'c.id_customer = pl.id_customer', 'left');
        $this->db->join('paket pk', 'pk.id_paket = pl.id_paket', 'left');
        $this->db->where('pl.status', 'pending');
        return $this->db->get()->result();
    }

    public function approve_request($id) {
        $permintaan = $this->db->get_where('permintaan_langganan', ['id_permintaan' => $id])->row();
        if ($permintaan) {
            // Update status permintaan
            $this->db->where('id_permintaan', $id);
            $this->db->update('permintaan_langganan', [
                'status' => 'approved'
            ]);

            // Insert ke tabel berlangganan
            $this->db->insert('berlangganan', [
                'id_customer' => $permintaan->id_customer,
                'id_paket' => $permintaan->id_paket,
                'tgl_mulai' => $permintaan->tgl_mulai,
                'lama_langganan' => $permintaan->lama_langganan,
                'status' => 'aktif'
            ]);
        }
    }

    public function reject_request($id) {
        $this->db->where('id_permintaan', $id);
        $this->db->update('permintaan_langganan', [
            'status' => 'rejected'
        ]);
    }


     // ===== Request Instalasi =====
    public function get_all_requests() {
        return $this->db->get('request_instalasi')->result();
    }

    public function insert_request($data) {
        return $this->db->insert('request_instalasi', $data);
    }

    public function get_request_by_id($id) {
        return $this->db->get_where('request_instalasi', ['id_request' => $id])->row();
    }

    public function update_request($id, $data) {
        $this->db->where('id_request', $id);
        return $this->db->update('request_instalasi', $data);
    }

    public function delete_request($id) {
        $this->db->where('id_request', $id);
        return $this->db->delete('request_instalasi');
    }

    // ===== Dokumen =====
    public function get_all_dokumen() {
        return $this->db->get('dokumen_instalasi')->result();
    }

    public function insert_dokumen($data) {
        return $this->db->insert('dokumen_instalasi', $data);
    }

    public function get_dokumen_by_id($id) {
        return $this->db->get_where('dokumen_instalasi', ['id_dokumen' => $id])->row();
    }

    public function update_dokumen($id, $data) {
        $this->db->where('id_dokumen', $id);
        return $this->db->update('dokumen_instalasi', $data);
    }

    public function delete_dokumen($id) {
        $this->db->where('id_dokumen', $id);
        return $this->db->delete('dokumen_instalasi');
    }
    
}
