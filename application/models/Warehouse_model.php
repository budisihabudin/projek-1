<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Get all barang masuk dengan pagination dan pencarian
    public function get_all($limit, $start, $keyword = null) {
        if($keyword){
            $this->db->like('nama_barang', $keyword);
            $this->db->or_like('keterangan', $keyword);
        }
        $this->db->order_by('tanggal_masuk', 'DESC');
        return $this->db->get('tb_warehouse_masuk', $limit, $start)->result();
    }

    // Hitung total rows
    public function count_all($keyword = null){
        if($keyword){
            $this->db->like('nama_barang', $keyword);
            $this->db->or_like('keterangan', $keyword);
        }
        return $this->db->count_all_results('tb_warehouse_masuk');
    }

    public function get($id){
        return $this->db->get_where('tb_warehouse_masuk', ['id_warehouse_masuk' => $id])->row();
    }

    public function insert($data){
        $this->db->insert('tb_warehouse_masuk', $data);
    }

    public function update($id, $data){
        $this->db->where('id_warehouse_masuk', $id);
        $this->db->update('tb_warehouse_masuk', $data);
    }

    public function delete($id){
        $this->db->where('id_warehouse_masuk', $id);
        $this->db->delete('tb_warehouse_masuk');
    }


    // ===================== BARANG KELUAR =====================
    public function get_all_keluar($limit, $start, $keyword = null) {
        $this->db->select('wk.*, wm.nama_barang');
        $this->db->from('tb_warehouse_keluar wk');
        $this->db->join('tb_warehouse_masuk wm', 'wk.id_warehouse_masuk = wm.id_warehouse_masuk');
        if($keyword){
            $this->db->like('wm.nama_barang', $keyword);
            $this->db->or_like('wk.keterangan', $keyword);
        }
        $this->db->order_by('wk.tanggal_keluar', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    public function count_all_keluar($keyword = null){
        $this->db->from('tb_warehouse_keluar wk');
        $this->db->join('tb_warehouse_masuk wm', 'wk.id_warehouse_masuk = wm.id_warehouse_masuk');
        if($keyword){
            $this->db->like('wm.nama_barang', $keyword);
            $this->db->or_like('wk.keterangan', $keyword);
        }
        return $this->db->count_all_results();
    }

    public function get_keluar($id){
        return $this->db->get_where('tb_warehouse_keluar', ['id_warehouse_keluar' => $id])->row();
    }

    public function insert_keluar($data){
        $this->db->insert('tb_warehouse_keluar', $data);
        // kurangi stok
        $this->db->set('jumlah', 'jumlah-'.$data['jumlah'], FALSE)
                 ->where('id_warehouse_masuk', $data['id_warehouse_masuk'])
                 ->update('tb_warehouse_masuk');
    }

    public function update_keluar($id, $data){
        $old = $this->get_keluar($id);
        $diff = $data['jumlah'] - $old->jumlah;

        // update stok barang masuk
        $this->db->set('jumlah', 'jumlah-'.$diff, FALSE)
                 ->where('id_warehouse_masuk', $data['id_warehouse_masuk'])
                 ->update('tb_warehouse_masuk');

        $this->db->where('id_warehouse_keluar', $id)->update('tb_warehouse_keluar', $data);
    }

    public function delete_keluar($id){
        $item = $this->get_keluar($id);
        // kembalikan stok
        $this->db->set('jumlah', 'jumlah+'.$item->jumlah, FALSE)
                 ->where('id_warehouse_masuk', $item->id_warehouse_masuk)
                 ->update('tb_warehouse_masuk');

        $this->db->delete('tb_warehouse_keluar', ['id_warehouse_keluar' => $id]);
    }

    // ambil semua barang masuk untuk dropdown
    public function get_barang_masuk() {
        return $this->db->get('tb_warehouse_masuk')->result();
    }



    // ===================== BARANG RUSAK =====================
    public function get_all_rusak($limit, $start, $keyword = null) {
        $this->db->select('wr.*, wm.nama_barang');
        $this->db->from('tb_warehouse_rusak wr');
        $this->db->join('tb_warehouse_masuk wm', 'wr.id_warehouse_masuk = wm.id_warehouse_masuk');
        if($keyword){
            $this->db->like('wm.nama_barang', $keyword);
            $this->db->or_like('wr.keterangan', $keyword);
        }
        $this->db->order_by('wr.tanggal_rusak', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    public function count_all_rusak($keyword = null){
        $this->db->from('tb_warehouse_rusak wr');
        $this->db->join('tb_warehouse_masuk wm', 'wr.id_warehouse_masuk = wm.id_warehouse_masuk');
        if($keyword){
            $this->db->like('wm.nama_barang', $keyword);
            $this->db->or_like('wr.keterangan', $keyword);
        }
        return $this->db->count_all_results();
    }

    public function get_rusak($id){
        return $this->db->get_where('tb_warehouse_rusak', ['id_rusak' => $id])->row();
    }

    public function insert_rusak($data){
        $this->db->insert('tb_warehouse_rusak', $data);
        // kurangi stok barang masuk
        $this->db->set('jumlah', 'jumlah-'.$data['jumlah'], FALSE)
                 ->where('id_warehouse_masuk', $data['id_warehouse_masuk'])
                 ->update('tb_warehouse_masuk');
    }

    public function update_rusak($id, $data){
        $old = $this->get_rusak($id);
        $diff = $data['jumlah'] - $old->jumlah;

        // update stok barang masuk
        $this->db->set('jumlah', 'jumlah-'.$diff, FALSE)
                 ->where('id_warehouse_masuk', $data['id_warehouse_masuk'])
                 ->update('tb_warehouse_masuk');

        $this->db->where('id_rusak', $id)->update('tb_warehouse_rusak', $data);
    }

    public function delete_rusak($id){
        $item = $this->get_rusak($id);
        // kembalikan stok
        $this->db->set('jumlah', 'jumlah+'.$item->jumlah, FALSE)
                 ->where('id_warehouse_masuk', $item->id_warehouse_masuk)
                 ->update('tb_warehouse_masuk');

        $this->db->delete('tb_warehouse_rusak', ['id_rusak' => $id]);
    }



}
