<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_dashboard extends CI_Model {

    public function get_total_akun() {
        return $this->db->count_all('tb_akun');
    }

    public function get_total_produk() {
        return $this->db->count_all('tb_produk');
    }

    public function get_total_transaksi() {
        return $this->db->count_all('tb_transaksi');
    }

    public function get_total_bonus() {
        return $this->db->count_all('tb_bonus');
    }

    public function get_transaksi_by_akun($id_akun) {
        $this->db->where('id_akun', $id_akun);
        return $this->db->count_all_results('tb_transaksi');
    }

    public function get_bonus_by_akun($id_akun) {
        $this->db->where('id_akun', $id_akun);
        return $this->db->count_all_results('tb_bonus');
    }
}
