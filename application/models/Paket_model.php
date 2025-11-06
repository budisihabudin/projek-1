<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paket_model extends CI_Model {

    private $table = 'paket';

    public function get_all_paket() {
        return $this->db->get('paket')->result();
    }

    public function get_all($limit = null, $offset = null, $keyword = null) {
        $this->db->from($this->table);

        if ($keyword) {
            $this->db->like('nama_paket', $keyword);
            $this->db->or_like('deskripsi', $keyword);
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();
    }

    public function count_all($keyword = null) {
        $this->db->from($this->table);
        if ($keyword) {
            $this->db->like('nama_paket', $keyword);
            $this->db->or_like('deskripsi', $keyword);
        }
        return $this->db->count_all_results();
    }

    public function insert($data) {
        $this->db->insert($this->table, $data);
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id_paket' => $id])->row();
    }

    public function update($id, $data) {
        $this->db->where('id_paket', $id);
        $this->db->update($this->table, $data);
    }

    public function delete($id) {
        $this->db->where('id_paket', $id);
        $this->db->delete($this->table);
    }
}
