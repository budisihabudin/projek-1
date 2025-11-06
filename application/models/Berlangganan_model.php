<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Berlangganan_model extends CI_Model {

    private $table = 'berlangganan';

    public function insert($data) {
        $this->db->insert($this->table, $data);
    }


    public function get_all($limit, $offset, $keyword = null) {
        $this->db->select('berlangganan.*, customer.nama as nama_customer, paket.nama_paket, paket.kecepatan');
        $this->db->from($this->table);
        $this->db->join('customer', 'customer.id_customer = berlangganan.id_customer', 'left');
        $this->db->join('paket', 'paket.id_paket = berlangganan.id_paket', 'left');

        if ($keyword) {
            $this->db->like('customer.nama', $keyword);
            $this->db->or_like('paket.nama_paket', $keyword);
            $this->db->or_like('berlangganan.status', $keyword);
        }

        $this->db->order_by('berlangganan.tgl_mulai', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_all($keyword = null) {
        $this->db->from($this->table);
        $this->db->join('customer', 'customer.id_customer = berlangganan.id_customer', 'left');
        $this->db->join('paket', 'paket.id_paket = berlangganan.id_paket', 'left');

        if ($keyword) {
            $this->db->like('customer.nama', $keyword);
            $this->db->or_like('paket.nama_paket', $keyword);
            $this->db->or_like('berlangganan.status', $keyword);
        }

        return $this->db->count_all_results();
    }

    public function toggle_status($id_berlangganan) {
        $this->db->set('status', "IF(status='belum bayar','sudah bayar','belum bayar')", false);
        $this->db->where('id_berlangganan', $id_berlangganan);
        $this->db->update($this->table);
    }

    public function get_by_id($id_berlangganan) {
        $this->db->select('b.*, c.nama as nama_customer, p.nama_paket, p.harga');
        $this->db->from($this->table . ' b');
        $this->db->join('customer c', 'c.id_customer = b.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = b.id_paket', 'left');
        $this->db->where('b.id_berlangganan', $id_berlangganan);
        return $this->db->get()->row();
    }

}
