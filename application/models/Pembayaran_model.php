<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran_model extends CI_Model {

    private $table = 'pembayaran';

    // Ambil data pembayaran berdasarkan order_id
    public function get_by_order_id($order_id) {
        return $this->db->get_where($this->table, ['order_id' => $order_id])->row();
    }

    // Ambil data pembayaran berdasarkan id_tagihan
    public function get_by_id_tagihan($id_tagihan) {
        return $this->db->get_where($this->table, ['id_tagihan' => $id_tagihan])->result();
    }

    // Insert pembayaran baru
    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    // Update pembayaran
    public function update($id_pembayaran, $data) {
        $this->db->where('id_pembayaran', $id_pembayaran);
        return $this->db->update($this->table, $data);
    }

    // Update pembayaran berdasarkan order_id
    public function update_by_order_id($order_id, $data) {
        $this->db->where('order_id', $order_id);
        return $this->db->update($this->table, $data);
    }

    // Ambil pembayaran dengan join ke tagihan dan customer
    public function get_pembayaran_detail($order_id) {
        $this->db->select('p.*, t.jumlah, t.periode, c.nama as nama_customer, p.nama_paket');
        $this->db->from($this->table . ' p');
        $this->db->join('penagihan t', 't.id_tagihan = p.id_tagihan', 'left');
        $this->db->join('berlangganan b', 'b.id_berlangganan = t.id_berlangganan', 'left');
        $this->db->join('customer c', 'c.id_customer = b.id_customer', 'left');
        $this->db->join('paket pak', 'pak.id_paket = b.id_paket', 'left');
        $this->db->where('p.order_id', $order_id);
        return $this->db->get()->row();
    }

    // Ambil semua pembayaran dengan pagination
    public function get_all($limit = 10, $offset = 0, $keyword = null) {
        $this->db->select('p.*, t.jumlah, t.periode, c.nama as nama_customer, pak.nama_paket');
        $this->db->from($this->table . ' p');
        $this->db->join('penagihan t', 't.id_tagihan = p.id_tagihan', 'left');
        $this->db->join('berlangganan b', 'b.id_berlangganan = t.id_berlangganan', 'left');
        $this->db->join('customer c', 'c.id_customer = b.id_customer', 'left');
        $this->db->join('paket pak', 'pak.id_paket = b.id_paket', 'left');

        if ($keyword) {
            $this->db->group_start();
            $this->db->like('p.order_id', $keyword);
            $this->db->or_like('c.nama', $keyword);
            $this->db->or_like('p.status', $keyword);
            $this->db->group_end();
        }

        $this->db->order_by('p.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    // Hitung total pembayaran
    public function count_all($keyword = null) {
        $this->db->from($this->table . ' p');
        $this->db->join('penagihan t', 't.id_tagihan = p.id_tagihan', 'left');
        $this->db->join('berlangganan b', 'b.id_berlangganan = t.id_berlangganan', 'left');
        $this->db->join('customer c', 'c.id_customer = b.id_customer', 'left');

        if ($keyword) {
            $this->db->group_start();
            $this->db->like('p.order_id', $keyword);
            $this->db->or_like('c.nama', $keyword);
            $this->db->or_like('p.status', $keyword);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    // Ambil pembayaran berdasarkan ID
    public function get($id_pembayaran) {
        return $this->db->get_where($this->table, ['id_pembayaran' => $id_pembayaran])->row();
    }

    // Hapus pembayaran
    public function delete($id_pembayaran) {
        return $this->db->delete($this->table, ['id_pembayaran' => $id_pembayaran]);
    }

    // Get statistics pembayaran
    public function get_statistics() {
        $stats = [];

        // Total pembayaran
        $this->db->select('COUNT(*) as total, SUM(jumlah) as total_amount');
        $query = $this->db->get($this->table);
        $stats['total'] = $query->row();

        // Pembayaran per status
        $this->db->select('status, COUNT(*) as count');
        $this->db->group_by('status');
        $query = $this->db->get($this->table);
        $stats['by_status'] = $query->result();

        // Pembayaran bulan ini
        $this->db->select('COUNT(*) as this_month');
        $this->db->where('MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())');
        $query = $this->db->get($this->table);
        $stats['this_month'] = $query->row()->this_month;

        return $stats;
    }
}