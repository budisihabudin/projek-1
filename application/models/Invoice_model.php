<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends CI_Model {

    /**
     * Generate invoice dari request pemasangan
     * Dipanggil ketika finance approve request
     */
    public function generate_invoice_from_request($id_request) {
        // Get request data
        $this->db->select('r.*, c.nama, c.no_hp, c.email, p.nama_paket, p.harga');
        $this->db->from('tb_request_pemasangan r');
        $this->db->join('customer c', 'c.id_customer = r.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = r.id_paket', 'left');
        $this->db->where('r.id_request', $id_request);
        $request = $this->db->get()->row();

        if (!$request) {
            log_message('error', 'Request not found for invoice generation: ' . $id_request);
            return false;
        }

        // Generate unique invoice number
        $invoice_no = $this->generate_invoice_number($request->id_customer);

        // Get lama_bulan from request, default to 1 if not set
        $lama_bulan = $request->lama_bulan ?? 1;

        // Get tanggal mulai dari request atau default hari ini
        $tgl_mulai = !empty($request->tgl_langganan) ? $request->tgl_langganan : date('Y-m-d');

        // Create multiple berlangganan records based on lama_bulan
        $berlangganan_ids = [];
        $first_id_berlangganan = null;

        for ($bulan = 0; $bulan < $lama_bulan; $bulan++) {
            // Calculate start date for each month
            $start_date = date('Y-m-d', strtotime($tgl_mulai . " +{$bulan} months"));

            // Calculate end date (sama tanggal + 1 bulan - 1 hari)
            $end_date = date('Y-m-d', strtotime($start_date . " +1 month -1 day"));

            // Prepare data untuk berlangganan
            $berlangganan_data = [
                'id_customer' => $request->id_customer,
                'id_paket' => $request->id_paket,
                'tgl_mulai' => $start_date,
                'tgl_berakhir' => $end_date,
                'status' => 'aktif',
                'status_bayar' => 'belum bayar', // Default belum bayar
                'id_invoice' => $invoice_no,
                'payment_status' => 'pending', // Ready untuk payment
                'payment_method' => 'midtrans',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Insert ke berlangganan
            $this->db->insert('berlangganan', $berlangganan_data);
            $id_berlangganan = $this->db->insert_id();

            // Store first ID for return
            if ($bulan === 0) {
                $first_id_berlangganan = $id_berlangganan;
            }

            $berlangganan_ids[] = $id_berlangganan;
        }

        // Log invoice generation
        log_message('info', 'Invoice generated: ' . $invoice_no . ' for request: ' . $id_request .
                    ', lama_bulan: ' . $lama_bulan . ', total records: ' . count($berlangganan_ids) .
                    ', customer: ' . $request->nama);

        // Update request status
        $this->db->where('id_request', $id_request);
        $this->db->update('tb_request_pemasangan', [
            'approval_finance' => 'approved'
        ]);

        // Return invoice info
        return [
            'success' => true,
            'id_berlangganan' => $first_id_berlangganan, // First record ID
            'berlangganan_ids' => $berlangganan_ids, // All created IDs
            'invoice_no' => $invoice_no,
            'customer_name' => $request->nama,
            'package_name' => $request->nama_paket,
            'amount' => $request->harga,
            'lama_bulan' => $lama_bulan,
            'total_amount' => $request->harga * $lama_bulan
        ];
    }

    /**
     * Generate unique invoice number
     */
    private function generate_invoice_number($id_customer) {
        $prefix = 'INV-' . date('Ym');
        $customer_code = str_pad($id_customer, 4, '0', STR_PAD_LEFT);
        $random = time() . rand(100, 999);

        return $prefix . '-' . $customer_code . '-' . $random;
    }

    /**
     * Get invoice data by invoice number
     */
    public function get_invoice_by_number($invoice_no) {
        $this->db->select('b.*, c.nama as customer_name, c.email, c.no_hp, p.nama_paket, p.harga');
        $this->db->from('berlangganan b');
        $this->db->join('customer c', 'c.id_customer = b.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = b.id_paket', 'left');
        $this->db->where('b.id_invoice', $invoice_no);
        return $this->db->get()->row();
    }

    /**
     * Get all invoices with pagination
     */
    public function get_all_invoices($limit, $start, $keyword = null) {
        $this->db->select('b.*, c.nama as customer_name, c.kode_customer, p.nama_paket, p.harga');
        $this->db->from('berlangganan b');
        $this->db->join('customer c', 'c.id_customer = b.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = b.id_paket', 'left');

        if ($keyword) {
            $this->db->group_start();
            $this->db->like('b.id_invoice', $keyword);
            $this->db->or_like('c.nama', $keyword);
            $this->db->or_like('c.kode_customer', $keyword);
            $this->db->or_like('p.nama_paket', $keyword);
            $this->db->group_end();
        }

        $this->db->order_by('b.created_at', 'DESC');
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    /**
     * Count all invoices
     */
    public function count_all_invoices($keyword = null) {
        $this->db->from('berlangganan b');
        $this->db->join('customer c', 'c.id_customer = b.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = b.id_paket', 'left');
        $this->db->where('b.id_invoice IS NOT NULL');

        if ($keyword) {
            $this->db->group_start();
            $this->db->like('b.id_invoice', $keyword);
            $this->db->or_like('c.nama', $keyword);
            $this->db->or_like('c.kode_customer', $keyword);
            $this->db->or_like('p.nama_paket', $keyword);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    /**
     * Update invoice status
     */
    public function update_invoice_status($id_berlangganan, $status, $payment_data = []) {
        $update_data = [
            'payment_status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Add payment data if provided
        if (!empty($payment_data)) {
            $update_data = array_merge($update_data, $payment_data);
        }

        // Map payment status to status_bayar
        if ($status == 'lunas') {
            $update_data['status_bayar'] = 'sudah bayar';
            $update_data['bukti_bayar'] = $payment_data['bukti_bayar'] ?? null;
            $update_data['payment_time'] = $payment_data['payment_time'] ?? date('Y-m-d H:i:s');
        } elseif ($status == 'pending') {
            $update_data['status_bayar'] = 'menunggu konfirmasi';
        } else {
            $update_data['status_bayar'] = 'belum bayar';
        }

        $this->db->where('id_berlangganan', $id_berlangganan);
        return $this->db->update('berlangganan', $update_data);
    }

    /**
     * Get invoice statistics
     */
    public function get_invoice_statistics() {
        $this->db->select('
            COUNT(*) as total_invoices,
            SUM(CASE WHEN b.status_bayar = "sudah bayar" THEN 1 ELSE 0 END) as paid_invoices,
            SUM(CASE WHEN b.status_bayar = "belum bayar" THEN 1 ELSE 0 END) as unpaid_invoices,
            SUM(CASE WHEN b.status_bayar = "menunggu konfirmasi" THEN 1 ELSE 0 END) as pending_invoices,
            SUM(p.harga) as total_amount,
            SUM(CASE WHEN b.status_bayar = "sudah bayar" THEN p.harga ELSE 0 END) as paid_amount
        ');
        $this->db->from('berlangganan b');
        $this->db->join('paket p', 'p.id_paket = b.id_paket', 'left');
        $this->db->where('b.id_invoice IS NOT NULL');

        return $this->db->get()->row();
    }
}