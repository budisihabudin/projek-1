<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survei_model extends CI_Model {

    protected $table = 'histori_berlangganan';

    public function get_all($limit, $offset, $keyword = null) {
    $id_user       = $this->session->userdata('id_user');
    $id_employee   = $this->session->userdata('id_employee');
    $role          = $this->session->userdata('role');
    $nama_jabatan  = $this->session->userdata('nama_jabatan');

    $this->db->select('
        h.id_histori, 
        h.tgl_langganan, 
        h.lama_langganan, 
        h.status, 
        h.aktivitas, 
        h.foto_survei, 
        c.nama AS nama_customer, 
        p.nama_paket AS nama_paket, 
        e.nama_lengkap AS nama_teknisi
    ');
    $this->db->from($this->table . ' h');
    $this->db->join('customer c', 'c.id_customer = h.id_customer', 'left');
    $this->db->join('paket p', 'p.id_paket = h.id_paket', 'left');
    $this->db->join('employee e', 'e.id_employee = h.id_employee', 'left');

    // 🔍 Filter pencarian
    if (!empty($keyword)) {
        $this->db->group_start();
        $this->db->like('h.id_histori', $keyword);
        $this->db->or_like('h.aktivitas', $keyword);
        $this->db->or_like('c.nama', $keyword);
        $this->db->or_like('p.nama_paket', $keyword);
        $this->db->or_like('e.nama_lengkap', $keyword);
        $this->db->group_end();
    }

    
    if ($role == "surveyor" && strtolower($nama_jabatan) == "staff surveyor") {
        $this->db->where('h.id_employee', $id_employee);
        $this->db->where('e.nama_lengkap IS NOT NULL');
        $this->db->where('e.nama_lengkap !=', '');
    } elseif ($role == "sales") {
        $this->db->where('h.id_sales', $id_user);
    }
    // Admin tidak difilter

    $this->db->order_by('h.tgl_langganan', 'DESC');
    $this->db->limit($limit, $offset);

    return $this->db->get()->result();
}



public function count_all($keyword = null) {
    $id_user       = $this->session->userdata('id_user');
    $role          = $this->session->userdata('role');
    $nama_jabatan  = $this->session->userdata('nama_jabatan');

    $this->db->from($this->table.' h');
    $this->db->join('customer c', 'c.id_customer = h.id_customer', 'left');
    $this->db->join('paket p', 'p.id_paket = h.id_paket', 'left');
    $this->db->join('employee e', 'e.id_employee = h.id_employee', 'left');

    if($keyword) {
        $this->db->group_start();
        $this->db->like('h.id_histori', $keyword);
        $this->db->or_like('h.aktivitas', $keyword);
        $this->db->or_like('c.nama', $keyword);
        $this->db->or_like('p.nama_paket', $keyword);
        $this->db->or_like('e.nama_lengkap', $keyword);
        $this->db->group_end();
    }

    if ($role == "surveyor" && strtolower($nama_jabatan) == "staff surveyor") {
        $this->db->where('h.id_employee', $id_user);
        $this->db->where("TRIM(e.nama_lengkap) <>", "");
    } elseif ($role == "sales") {
        $this->db->where('h.modified_by', $id_user);
    }

    return $this->db->count_all_results();
}


    public function get_by_id($id_histori) {
        $this->db->select('h.*, c.nama AS nama_customer, p.nama_paket AS nama_paket, e.nama_lengkap AS nama_teknisi');
        $this->db->from($this->table.' h');
        $this->db->join('customer c', 'c.id_customer = h.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = h.id_paket', 'left');
        $this->db->join('employee e', 'e.id_employee = h.id_employee', 'left');
        $this->db->where('h.id_histori', $id_histori);
        return $this->db->get()->row();
    }

    public function update($id_histori, $data) {
        $this->db->where('id_histori', $id_histori);
        return $this->db->update($this->table, $data);
    }

    public function delete($id_histori) {
        $this->db->where('id_histori', $id_histori);
        return $this->db->delete($this->table);
    }


}
