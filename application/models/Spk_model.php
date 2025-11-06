<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Spk_model extends CI_Model {
    
    // FUNGSI BARU: Generate Kode SPK (SPK-001/dd-mm-yyyy)
    public function generate_spk_code($preview = false) {
        $bulan_tahun = date('m/Y');
        $tanggal_format = date('d-m-Y');
        
        // Jika hanya untuk preview di form CREATE, gunakan nomor urut + 1
        if ($preview) {
            $nomor = $this->get_last_spk_number() + 1;
        } else {
            // Jika untuk INSERT, ambil nomor urut yang sebenarnya (akan digunakan di insert_data)
            $nomor = $this->get_last_spk_number() + 1;
        }
        
        // Format nomor: 001, 010, 100
        $nomor_format = sprintf('%03d', $nomor); 
        
        // Susun kode SPK
        return "SPK-{$nomor_format}/{$tanggal_format}";
    }

    // FUNGSI BARU: Ambil nomor urut SPK terakhir
    private function get_last_spk_number() {
        // Ambil SPK yang di-input pada tanggal hari ini
        $today = date('Y-m-d');
        
        // Cek data SPK hari ini
        $this->db->select('kode_spk');
        $this->db->like('tgl_spk', $today); // Asumsi tgl_spk adalah date
        $this->db->order_by('id_spk', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('spk');
        
        if ($query->num_rows() > 0) {
            $last_kode = $query->row()->kode_spk;
            // Ambil angka dari kode SPK (misal dari SPK-015/01-10-2025, ambil 15)
            preg_match('/SPK-(\d+)\//', $last_kode, $matches);
            
            // Jika hari ini sudah ada data, gunakan nomor urut terakhir
            if (isset($matches[1])) {
                return (int)$matches[1];
            }
        }
        
        // Jika belum ada data hari ini, mulai dari 0
        return 0;
    }


   public function get_by_id($id)
    {
        $this->db->select('spk.*, spk.kode_spk'); // Tambahkan kode_spk
        // Jika Anda ingin menampilkan nama customer/teknisi di edit:
        // $this->db->select('spk.*, spk.kode_spk, customer.nama as nama_customer, teknisi.nama as nama_teknisi'); 
        $this->db->from('spk');
        // AKTIFKAN JOIN BARIS INI (sesuaikan dengan nama foreign key Anda):
        // $this->db->join('customer', 'customer.id_customer = spk.id_customer', 'left');
        // $this->db->join('teknisi', 'teknisi.id_user = spk.id_teknisi', 'left'); 
        $this->db->where('spk.id_spk', $id);
        return $this->db->get()->row();
    }

    public function get_all($limit = null, $offset = null, $keyword = null)
{
    // AKTIFKAN KEMBALI KOLOM CUSTOMER DAN TEKNISI JIKA DIPERLUKAN DI VIEW INDEX
    $this->db->select('spk.id_spk, spk.kode_spk, spk.spk, spk.tgl_spk, spk.status, spk.keterangan');
    $this->db->from('spk');
    
    // AKTIFKAN JOIN BARIS INI:
    // $this->db->join('customer', 'customer.id_customer = spk.id_customer', 'left');
    // $this->db->join('teknisi', 'teknisi.id_user = spk.id_teknisi', 'left'); // join pakai id_user

    if ($keyword) {
        $this->db->group_start();
        $this->db->like('spk.spk', $keyword);
        $this->db->group_end();
    }

    if ($limit) {
        $this->db->limit($limit, $offset);
    }

    return $this->db->get()->result();
}

    public function count_all($keyword = null)
{
    $this->db->from('spk');
    
    // AKTIFKAN JOIN BARIS INI:
    // $this->db->join('customer', 'spk.id_customer = customer.id_customer', 'left');
    // $this->db->join('teknisi', 'teknisi.id_user = spk.id_teknisi', 'left'); 

    if ($keyword) {
        $this->db->group_start();
        $this->db->like('spk.spk', $keyword);
        // $this->db->or_like('teknisi.nama', $keyword);
        $this->db->group_end();
    }

    return $this->db->count_all_results();
}


    public function insert_data($kode_spk) // Ganti nama function dan terima kode SPK
    {
        $id_teknisi = $this->input->post('id_teknisi');

        // ambil id_user dari tabel teknisi (jika diperlukan)
        // $teknisi = $this->db->get_where('teknisi', ['id_teknisi' => $id_teknisi])->row();
        // if (!$teknisi) {
        //     return false; 
        // }

        $data = [
            'kode_spk'   => $kode_spk, // MASUKKAN KODE SPK
            'spk'        => $this->input->post('spk'), // Ini adalah Nama/Judul SPK
            'tgl_spk'    => $this->input->post('tgl_spk'),
            'keterangan' => $this->input->post('keterangan')
            // 'id_teknisi' => $teknisi->id_user, 
        ];

        $this->db->insert('spk', $data);
        return true;
    }


    public function update($id)
    {
        $data = [
            'spk' => $this->input->post('spk'),
            'tgl_spk' => $this->input->post('tgl_spk'),
            'keterangan' => $this->input->post('keterangan')
        ];
        $this->db->where('id_spk', $id);
        $this->db->update('spk', $data);
    }

    public function delete($id)
    {
        $this->db->where('id_spk', $id);
        $this->db->delete('spk');
    }
}