<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jabatan_model extends CI_Model {

    private $table = 'jabatan';

    // Ambil semua jabatan tanpa pagination (untuk dropdown)
    public function get_all_dropdown() {
        return $this->db->get($this->table)->result();
    }


    // Ambil semua jabatan dengan pagination + pencarian
    public function get_all($limit, $offset, $keyword = null) {
        $this->db->from($this->table);
        if ($keyword) {
            $this->db->like('nama_jabatan', $keyword);
        }
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    // Hitung total jabatan untuk pagination
    public function count_all($keyword = null) {
        $this->db->from($this->table);
        if ($keyword) {
            $this->db->like('nama_jabatan', $keyword);
        }
        return $this->db->count_all_results();
    }

    // Ambil 1 jabatan
    public function get($id) {
        return $this->db->get_where($this->table, ['id_jabatan' => $id])->row();
    }

    // Tambah jabatan
    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    // Update jabatan
    public function update($id, $data) {
        $this->db->where('id_jabatan', $id);
        return $this->db->update($this->table, $data);
    }

    // Hapus jabatan
    public function delete($id) {
        $this->db->where('id_jabatan', $id);
        return $this->db->delete($this->table);
    }
}
