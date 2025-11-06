<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {

    private $table_user = 'users';
    private $table_customer = 'customer';
    private $table_berlangganan = 'berlangganan';
    private $table_paket = 'paket';
    private $table_histori = 'histori_berlangganan';

     public function insert_customer($data) {
        return $this->db->insert('customer', $data);
    }

    public function count_riwayat($id_user, $keyword = null) {
        $this->db->where('id_user', $id_user);
        if (!empty($keyword)) {
            $this->db->like('nama', $keyword);
            $this->db->or_like('kode_customer', $keyword);
        }
        return $this->db->count_all_results('customer');
    }

    public function get_riwayat_paginate($id_user, $limit, $start, $keyword = null) {
        $this->db->where('id_user', $id_user);
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nama', $keyword);
            $this->db->or_like('kode_customer', $keyword);
            $this->db->group_end();
        }
        $this->db->limit($limit, $start);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('customer')->result();
    }


    public function get_by_user($id_user) {
        return $this->db->get_where('customer', ['id_user' => $id_user])->row();
    }

    public function get_registered_customers() {
        $this->db->select('c.id_customer, c.kode_customer, c.nama, c.alamat, c.no_hp, c.email, u.username');
        $this->db->from('customer c');
        $this->db->join('users u', 'u.id_user = c.id_user', 'left');
        $this->db->where('c.status_customer', 'approved');
        $this->db->or_where('c.status_customer', 'aktif');
        $this->db->order_by('c.nama', 'ASC');
        return $this->db->get()->result();
    }

    public function update($id_customer, $data) {
        $this->db->where('id_customer', $id_customer);
        return $this->db->update('customer', $data);
    }

    public function get_riwayat_langganan($id_user) {
        $this->db->select('berlangganan.*, paket.nama_paket, paket.harga, paket.deskripsi, customer.nama as nama_customer');
        $this->db->from('berlangganan');
        $this->db->join('customer', 'customer.id_customer = berlangganan.id_customer');
        $this->db->join('users', 'users.id_user = customer.id_user');
        $this->db->join('paket', 'paket.id_paket = berlangganan.id_paket');
        $this->db->where('users.id_user', $id_user);
        $this->db->order_by('berlangganan.tgl_mulai', 'DESC');
        return $this->db->get()->result();
    }


    // ==========================
    // GENERATE KODE CUSTOMER
    // ==========================
    public function generate_kode_customer() {
    $this->db->select('kode_customer');
    $this->db->order_by('id_customer', 'DESC');
    $this->db->limit(1);
    $query = $this->db->get('customer');

    if ($query->num_rows() > 0) {
        $last_customer = $query->row();
        $last_code = explode('-', $last_customer->kode_customer);

        // pastikan ada bagian angka
        if (isset($last_code[1])) {
            $number = (int)$last_code[1] + 1;
        } else {
            // fallback jika format tidak sesuai
            $number = ((int) filter_var($last_customer->kode_customer, FILTER_SANITIZE_NUMBER_INT)) + 1;
        }

        $new_code = 'CUST-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    } else {
        $new_code = 'CUST-001';
    }

    return $new_code;
}


    // ==========================
    // AMBIL SEMUA CUSTOMER (SEARCH & PAGINATION)
    // ==========================
    public function get_all($limit, $offset, $keyword = null) {
        $id_user = $this->session->userdata('id_user');
        $role = $this->session->userdata('role');

        $this->db->select('c.*, u.username');
        $this->db->from($this->table_customer . ' c');
        $this->db->join($this->table_user . ' u', 'u.id_user = c.id_user', 'left');

        // Kalau bukan admin, hanya tampilkan data milik user login
        if ($role !== 'admin') {
            $this->db->where('c.modified_by', $id_user);
        }elseif ($role == 'customer') {
            $this->db->where('c.id_user', $id_user);
        }

        // Filter pencarian
        if (!empty($keyword)) {
            $keyword = $this->db->escape_str($keyword);
            $this->db->group_start();
            $this->db->like('c.nama', $keyword);
            $this->db->or_like('c.kode_customer', $keyword);
            $this->db->or_like('c.email', $keyword);
            $this->db->group_end();
        }

        // Pagination
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }



    public function count_all($keyword = null) {
        $this->db->from($this->table_customer);
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('nama', $keyword);
            $this->db->or_like('kode_customer', $keyword);
            $this->db->or_like('email', $keyword);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }

    // ==========================
    // INSERT USER + CUSTOMER
    // ==========================
    public function insert($data_user, $data_customer) {
        $this->db->trans_start();

        $this->db->insert($this->table_user, $data_user);
        $user_id = $this->db->insert_id();

        $data_customer['kode_customer'] = $this->generate_kode_customer();
        $data_customer['id_user'] = $user_id;
        $this->db->insert($this->table_customer, $data_customer);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // ==========================
    // INSERT BERLANGGANAN BULANAN OTOMATIS
    // ==========================
    public function insert_berlangganan($id_customer, $id_paket, $tgl_mulai, $lama_langganan) {
        $this->db->trans_start();
        $start = new DateTime($tgl_mulai);

        for ($i = 0; $i < $lama_langganan; $i++) {
            $tgl_mulai_bulan = $start->format('Y-m-d');
            $tgl_berakhir_bulan = $start->format('Y-m-t'); // akhir bulan

            // Generate unique transaction ID dan invoice untuk setiap periode
            $invoice_id = 'INV-' . date('Ym') . '-' . str_pad($id_customer, 4, '0', STR_PAD_LEFT) . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);

            $data = [
                'id_customer'   => $id_customer,
                'id_paket'      => $id_paket,
                'tgl_mulai'     => $tgl_mulai_bulan,
                'tgl_berakhir'  => $tgl_berakhir_bulan,
                'status'        => 'aktif',
                'status_bayar'  => 'belum bayar',
                'id_invoice'    => $invoice_id // Tambahkan ID Invoice
            ];

            $this->db->insert($this->table_berlangganan, $data);

            // Tambahkan 1 bulan
            $start->modify('+1 month');
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    // ==========================
    // AMBIL DAFTAR BERLANGGANAN CUSTOMER
    // ==========================
    public function get_berlangganan_by_customer($id_customer) {
        $this->db->select('b.*, p.nama_paket, p.harga');
        $this->db->from($this->table_berlangganan . ' b');
        $this->db->join($this->table_paket . ' p', 'p.id_paket = b.id_paket', 'left');
        $this->db->where('b.id_customer', $id_customer);
        $this->db->order_by('b.tgl_mulai', 'DESC');
        return $this->db->get()->result();
    }

    // ==========================
    // CEK LANGGANAN AKTIF CUSTOMER
    // ==========================
    public function get_langganan_aktif($id_customer) {
        $this->db->select('b.*, p.nama_paket, p.harga');
        $this->db->from($this->table_berlangganan . ' b');
        $this->db->join($this->table_paket . ' p', 'p.id_paket = b.id_paket', 'left');
        $this->db->where('b.id_customer', $id_customer);
        $this->db->where('b.status', 'aktif');
        $this->db->order_by('b.tgl_mulai', 'DESC');
        return $this->db->get()->result();
    }

    // ==========================
    // DELETE CUSTOMER + RELASI
    // ==========================
    public function delete($id_customer) {
        $this->db->trans_start();

        // 1️⃣ Hapus histori terkait customer
        $this->db->where('id_customer', $id_customer);
        $this->db->delete($this->table_histori);

        // 2️⃣ Hapus berlangganan terkait (jika ada tabel lain)
        $this->db->where('id_customer', $id_customer);
        $this->db->delete($this->table_berlangganan);

        // 3️⃣ Ambil id_user customer
        $this->db->select('id_user');
        $this->db->where('id_customer', $id_customer);
        $customer = $this->db->get($this->table_customer)->row();

        if ($customer) {
            // 4️⃣ Hapus customer
            $this->db->where('id_customer', $id_customer);
            $this->db->delete($this->table_customer);

            // 5️⃣ Hapus akun user
            $this->db->where('id_user', $customer->id_user);
            $this->db->delete($this->table_user);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // reques
    public function insert_reques_customer($data) {
        $this->db->insert('customer', $data);
    }

    // input regis from form
    public function insert_customer_regis($data) {
        return $this->db->insert('customer', $data);
    }

    // get id dokumen by id customer // dokumen customer
    public function get_dokumen_by_id_customer($id_dokumen_customer)
    {
        $this->db->select('d.*, c.id_customer');
        $this->db->from('tb_dokumen_customer d');
        $this->db->join('customer c', 'c.id_customer = d.id_customer', 'left');
        $this->db->where('d.id_dokumen_customer', $id_dokumen_customer);
        $query = $this->db->get();
        $result = $query->row();
        return $result ? $result->id_customer : null;
    }

    // Ambil data request pemasangan berdasarkan id_customer, id paket
    public function get_request_by_id_customer($id_request)
    {
        $this->db->select('r.*, c.id_customer');
        $this->db->from('tb_request_pemasangan r');
        $this->db->join('customer c', 'c.id_customer = r.id_customer', 'left');
        $this->db->where('r.id_request', $id_request);
        $query = $this->db->get();
        return $query->row();
    }

}
