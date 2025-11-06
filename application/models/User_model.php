<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    private $table = "users";
    private $table_customer = "customer";
    private $table_jabatan = "jabatan";
    private $table_employee = "employee";

    

     public function get_by_username_user_employee($username) {
        $this->db->select('u.*, e.id_employee');
        $this->db->from($this->table . ' u');
        $this->db->join($this->table_employee . ' e', 'e.id_employee = u.id_employee', 'left');
        $this->db->join($this->table_jabatan . ' j', 'j.id_jabatan = e.id_jabatan', 'left');
        $this->db->where('u.username', $username);
        return $this->db->get()->row();
    }

    public function get_by_username_jabatan($username) {
        $this->db->select('u.*, e.nama_lengkap, j.nama_jabatan');
        $this->db->from($this->table . ' u');
        $this->db->join($this->table_employee . ' e', 'e.id_employee = u.id_employee', 'left');
        $this->db->join($this->table_jabatan . ' j', 'j.id_jabatan = e.id_jabatan', 'left');
        $this->db->where('u.username', $username);
        return $this->db->get()->row();
    }



    public function get_by_username_customer($username) {
        $this->db->select('u.*, c.modified_by');
        $this->db->from($this->table . ' u');
        $this->db->join($this->table_customer . ' c', 'c.modified_by = u.id_user', 'left');
        $this->db->where('u.id_user', $username);
        return $this->db->get()->row();
    }


    // Ambil user berdasarkan username
    public function get_by_username($username) {
        return $this->db->get_where($this->table, ['username' => $username])->row();
    }



    

    // Ambil semua data user + pagination + search
    public function get_all($limit, $offset, $keyword = null) {
        $this->db->from($this->table);
        if ($keyword) {
            $this->db->like('username', $keyword);
            $this->db->or_like('role', $keyword);
        }
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    // Hitung total user untuk pagination
    public function count_all($keyword = null) {
        $this->db->from($this->table);
        if ($keyword) {
            $this->db->like('username', $keyword);
            $this->db->or_like('role', $keyword);
        }
        return $this->db->count_all_results();
    }

    // Ambil 1 user berdasarkan id
    public function get($id) {
        return $this->db->get_where($this->table, ['id_user' => $id])->row();
    }

    // Tambah user
    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    // Update user
    public function update($id_user, $data) {
        $this->db->where('id_user', $id_user);
        return $this->db->update($this->table, $data);
    }


    // Hapus user
    public function delete($id) {
        $this->db->where('id_user', $id);
        return $this->db->delete($this->table);
    }

    // Ganti status aktif/nonaktif
    public function toggle_status($id) {
        $user = $this->get($id);
        if ($user) {
            $new_status = ($user->status == 'active') ? 'nonaktif' : 'active';
            $this->db->where('id_user', $id);
            return $this->db->update($this->table, ['status' => $new_status]);
        }
        return false;
    }
}
