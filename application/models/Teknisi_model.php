<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teknisi_model extends CI_Model {

    private $table_user = 'users';
    private $table_teknisi = 'teknisi';

    public function get_teknisi()
    {
        return $this->db->get('teknisi')->result();
    }

    public function generate_kode_teknisi() {
        $this->db->select('kode_teknisi');
        $this->db->from($this->table_teknisi);
        $this->db->order_by('id_teknisi', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get()->row();

        if ($last) {
            $parts = explode('/', $last->kode_teknisi);
            $num = intval($parts[1]) + 1;
        } else {
            $num = 1;
        }

        $nomor = str_pad($num, 3, '0', STR_PAD_LEFT);
        $kode = "TEK/" . $nomor . "/" . date('d') . "/" . date('m') . "/" . date('Y');
        return $kode;
    }


    public function get_all($limit, $offset, $keyword = null) {
        $this->db->select('teknisi.*, users.username');
        $this->db->from($this->table_teknisi);
        $this->db->join($this->table_user, 'users.id_user = teknisi.id_user', 'left');

        if ($keyword) {
            $this->db->like('teknisi.nama', $keyword);
            $this->db->or_like('teknisi.email', $keyword);
        }

        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_all($keyword = null) {
        $this->db->from($this->table_teknisi);
        if ($keyword) {
            $this->db->like('nama', $keyword);
            $this->db->or_like('email', $keyword);
        }
        return $this->db->count_all_results();
    }

    public function get_by_id($id) {
        $this->db->where('id_teknisi', $id);
        return $this->db->get($this->table_teknisi)->row();
    }

    public function insert($data_user, $data_teknisi) {
        $this->db->trans_start();
        $this->db->insert($this->table_user, $data_user);
        $user_id = $this->db->insert_id();

        $data_teknisi['id_user'] = $user_id;
        $this->db->insert($this->table_teknisi, $data_teknisi);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function update($id, $data_teknisi) {
        $this->db->where('id_teknisi', $id);
        return $this->db->update($this->table_teknisi, $data_teknisi);
    }

    public function delete($id) {
        $this->db->trans_start();

        // ambil id_user dulu
        $this->db->select('id_user');
        $this->db->where('id_teknisi', $id);
        $teknisi = $this->db->get($this->table_teknisi)->row();

        if ($teknisi) {
            $this->db->where('id_teknisi', $id);
            $this->db->delete($this->table_teknisi);

            $this->db->where('id_user', $teknisi->id_user);
            $this->db->delete($this->table_user);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

}
