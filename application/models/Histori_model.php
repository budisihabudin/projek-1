<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Histori_model extends CI_Model {

 
    public function insert_histori($data) {
        $this->db->insert('histori_berlangganan', $data);
    }

    public function get_by_user($id_user) {
        $this->db->select('h.*, p.nama_paket, p.harga, p.kecepatan');
        $this->db->from('histori_berlangganan h');
        $this->db->join('paket p', 'p.id_paket = h.id_paket', 'left');
        $this->db->where('h.id_user', $id_user);
        $this->db->order_by('h.tgl_langganan', 'DESC');
        return $this->db->get()->result();
    }
}
