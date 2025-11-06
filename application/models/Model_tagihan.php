<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_tagihan extends CI_Model {

    // Ambil semua data tagihan bulanan (simplified untuk testing)
    public function get_tagihan_bulanan($limit, $start, $keyword = null)
    {
        // Ambil dari berlangganan yang aktif
        $this->db->select('
            b.id_berlangganan,
            b.id_customer,
            b.id_paket,
            b.tgl_mulai,
            b.tgl_berakhir,
            b.status as status_berlangganan,
            b.status_bayar,
            b.bukti_bayar,
            b.id_invoice,
            b.id_transaksi,
            b.payment_status,
            b.payment_method,
            b.payment_amount,
            b.payment_time,
            b.snap_token,
            b.created_at,
            b.updated_at,
            c.nama,
            c.alamat,
            c.no_hp,
            c.email,
            p.nama_paket,
            p.harga,
            MAX(r.id_request) as id_request,
            MAX(CASE WHEN r.approval_sales = "approved" THEN 1 ELSE 0 END) as approval_sales,
            MAX(CASE WHEN r.approval_survei = "approved" THEN 1 ELSE 0 END) as approval_survei,
            MAX(CASE WHEN r.approval_noc = "approved" THEN 1 ELSE 0 END) as approval_noc,
            MAX(CASE WHEN r.approval_finance = "approved" THEN 1 ELSE 0 END) as approval_finance
        ');
        $this->db->from('berlangganan b');
        $this->db->join('customer c', 'c.id_customer = b.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = b.id_paket', 'left');
        $this->db->join('tb_request_pemasangan r', 'r.id_customer = b.id_customer AND r.id_paket = b.id_paket', 'left');
        // Tampilkan semua data berlangganan (termasuk yang sudah lunas)

        // 🔒 Filter jika role = customer - tampilkan data berlangganan customer tersebut
        $role = $this->session->userdata('role');
        $customer_id = $this->session->userdata('id_customer');

        if ($role === 'customer' && !empty($customer_id)) {
            // Filter berdasarkan id_customer yang login
            $this->db->where('c.id_customer', $customer_id);
            log_message('debug', 'Customer filter applied - ID Customer: ' . $customer_id);
        }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('c.nama', $keyword);
            $this->db->or_like('p.nama_paket', $keyword);
            $this->db->or_like('b.id_invoice', $keyword);
            $this->db->group_end();
        }

        // Group by untuk mencegah data double
        $this->db->group_by('b.id_berlangganan');
        $this->db->order_by('b.created_at', 'DESC');

        $this->db->limit($limit, $start);

        // Debug: Log query
        $query = $this->db->get();
        $sql = $this->db->last_query();
        log_message('debug', '=== MODEL DEBUG ===');
        log_message('debug', 'Tagihan Query: ' . $sql);
        log_message('debug', 'Tagihan Results Count: ' . $query->num_rows());

        // Debug: Log session
        $role = $this->session->userdata('role');
        $customer_id = function_exists('get_customer_id') ? get_customer_id() : null;
        log_message('debug', 'Model - Role: ' . $role);
        log_message('debug', 'Model - Customer ID: ' . ($customer_id ?? 'NULL'));
        log_message('debug', 'Model - Customer Filter Applied: ' . (!empty($customer_id) ? 'YES' : 'NO'));

        return $query->result();
    }

    // Hitung total data
    public function count_all_tagihan_bulanan($keyword = null)
    {
        $this->db->from('berlangganan b');
        $this->db->join('customer c', 'c.id_customer = b.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = b.id_paket', 'left');
        $this->db->join('tb_request_pemasangan r', 'r.id_customer = b.id_customer AND r.id_paket = b.id_paket', 'left');
        // Tampilkan semua data berlangganan (termasuk yang sudah lunas)

        // 🔒 Filter jika role = customer - tampilkan data berlangganan customer tersebut
        // Gunakan helper function untuk consistency
        $role = $this->session->userdata('role');
        $customer_id = function_exists('get_customer_id') ? get_customer_id() : $this->session->userdata('id_customer');

        if ($role === 'customer' && !empty($customer_id)) {
            // Filter berdasarkan id_customer yang login
            $this->db->where('c.id_customer', $customer_id);
            log_message('debug', 'Customer filter applied in count - ID Customer: ' . $customer_id);
        }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('c.nama', $keyword);
            $this->db->or_like('p.nama_paket', $keyword);
            $this->db->or_like('b.id_invoice', $keyword);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    // Ambil detail berlangganan berdasarkan ID berlangganan
    public function get_berlangganan_by_id($id_berlangganan)
    {
        $this->db->select('
            b.id_berlangganan,
            b.id_customer,
            b.id_paket,
            b.tgl_mulai,
            b.tgl_berakhir,
            b.status as status_berlangganan,
            b.status_bayar,
            b.bukti_bayar,
            b.id_invoice,
            b.id_transaksi,
            b.snap_token,
            b.payment_status,
            b.payment_method,
            b.payment_amount,
            b.payment_time,
            c.nama,
            c.alamat,
            c.no_hp,
            c.email,
            p.nama_paket,
            p.harga,
            r.id_request,
            r.approval_sales,
            r.approval_survei,
            r.approval_noc,
            r.approval_finance
        ');
        $this->db->from('berlangganan b');
        $this->db->join('customer c', 'c.id_customer = b.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = b.id_paket', 'left');
        $this->db->join('tb_request_pemasangan r', 'r.id_customer = b.id_customer AND r.id_paket = b.id_paket', 'left');
        $this->db->where('b.id_berlangganan', $id_berlangganan);
        // Tampilkan semua data berlangganan (termasuk yang sudah lunas)
        return $this->db->get()->row();
    }

    // Function untuk mendapatkan data request yang belum jadi berlangganan
    public function get_request_bukan_berlangganan($limit, $start, $keyword = null)
    {
        $this->db->select('
            r.id_request as id_berlangganan,
            r.id_customer,
            r.id_paket,
            r.tgl_langganan as tgl_mulai,
            NULL as tgl_berakhir,
            "request" as status_berlangganan,
            "belum bayar" as status_bayar,
            NULL as bukti_bayar,
            NULL as id_invoice,
            NULL as id_transaksi,
            NULL as payment_status,
            NULL as payment_method,
            NULL as payment_amount,
            NULL as payment_time,
            NULL as snap_token,
            r.created_at,
            NULL as updated_at,
            c.nama,
            c.alamat,
            c.no_hp,
            c.email,
            p.nama_paket,
            p.harga,
            r.id_request,
            CASE WHEN r.approval_sales = "approved" THEN 1 ELSE 0 END as approval_sales,
            CASE WHEN r.approval_survei = "approved" THEN 1 ELSE 0 END as approval_survei,
            CASE WHEN r.approval_noc = "approved" THEN 1 ELSE 0 END as approval_noc,
            CASE WHEN r.approval_finance = "approved" THEN 1 ELSE 0 END as approval_finance
        ', FALSE);
        $this->db->from('tb_request_pemasangan r');
        $this->db->join('customer c', 'c.id_customer = r.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = r.id_paket', 'left');

        // Hanya ambil request yang belum ada di berlangganan
        $this->db->where('NOT EXISTS (SELECT 1 FROM berlangganan b WHERE b.id_customer = r.id_customer AND b.id_paket = r.id_paket AND b.created_at >= r.created_at)');

        // 🔒 Filter jika role = customer
        $role = $this->session->userdata('role');
        $customer_id = $this->session->userdata('id_customer');

        if ($role === 'customer' && !empty($customer_id)) {
            $this->db->where('c.id_customer', $customer_id);
        }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('c.nama', $keyword);
            $this->db->or_like('p.nama_paket', $keyword);
            $this->db->group_end();
        }

        $this->db->order_by('r.created_at', 'DESC');
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    // Function untuk menggabungkan data berlangganan dan request
    public function get_all_tagihan_data($limit, $start, $keyword = null)
    {
        // Get data berlangganan
        $berlangganan_data = $this->get_tagihan_bulanan($limit, $start, $keyword);

        // Get data request yang belum jadi berlangganan
        $request_data = $this->get_request_bukan_berlangganan($limit, $start, $keyword);

        // Combine data
        $all_data = array_merge($berlangganan_data, $request_data);

        // Sort by created_at descending
        usort($all_data, function($a, $b) {
            return strtotime($b->created_at) - strtotime($a->created_at);
        });

        return array_slice($all_data, 0, $limit);
    }

    // Legacy method for compatibility - sekarang mengambil data berlangganan
    public function get_tagihan_by_id($id_tagihan)
    {
        // Untuk kompatibilitas, anggap id_tagihan = id_berlangganan
        return $this->get_berlangganan_by_id($id_tagihan);
    }

    // Update status pembayaran / bukti bayar
    public function update_berlangganan($id_berlangganan, $data)
    {
        $this->db->where('id_berlangganan', $id_berlangganan);
        return $this->db->update('berlangganan', $data);
    }
}
