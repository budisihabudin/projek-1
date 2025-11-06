<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request_model extends CI_Model {

    private $table = 'customer';

    // ambil semua request dengan status pending/survey
    public function get_pending() {
        $this->db->from($this->table);
        $this->db->where_in('status', ['pending','survey']);
        return $this->db->get()->result();
    }

    // ambil semua data (optional kalau butuh list lengkap)
    public function get_all() {
        return $this->db->get($this->table)->result();
    }

    // detail request
    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id_customer' => $id])->row();
    }
}
