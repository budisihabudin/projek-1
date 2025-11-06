<?php
class Model_request extends CI_Model {

    public function insert_request($data_request)
    {
        return $this->db->insert('tb_request_pemasangan', $data_request);
    }

	// request pemasangan
    public function get_request_by_id($id) {
        $this->db->select('r.*, c.nama, c.kode_customer, c.alamat, c.no_hp, c.email, c.instansi, c.pic, p.nama_paket');
        $this->db->from('tb_request_pemasangan r');
        $this->db->join('customer c', 'c.id_customer = r.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = r.id_paket', 'left');
        $this->db->where('r.id_request', $id);
        return $this->db->get()->row();
    }

    public function count_all_request_customer($keyword = null) {
        $this->db->from('tb_request_pemasangan r');
        $this->db->join('customer c', 'c.id_customer = r.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = r.id_paket', 'left');
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('c.nama', $keyword);
            $this->db->or_like('c.kode_customer', $keyword);
            $this->db->or_like('p.nama_paket', $keyword);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }

    public function get_all_request_customer($limit, $start, $keyword = null) {
        $this->db->select('r.*, c.nama AS nama_customer, c.kode_customer, c.no_hp, p.nama_paket');
        $this->db->from('tb_request_pemasangan r');
        $this->db->join('customer c', 'c.id_customer = r.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = r.id_paket', 'left');
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('c.nama', $keyword);
            $this->db->or_like('p.nama_paket', $keyword);
            $this->db->group_end();
        }
        $this->db->order_by('r.created_at', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    // end request pemasangan

// ==========================================================================================================

 	// request survei
	public function get_survei_by_id($id_survei) {
	    $this->db->select('s.*, r.id_request, c.nama AS nama_customer, p.nama_paket');
	    $this->db->from('tb_request_survei s');
	    $this->db->join('tb_request_pemasangan r', 'r.id_request = s.id_request', 'left');
	    $this->db->join('customer c', 'c.id_customer = r.id_customer', 'left');
	    $this->db->join('paket p', 'p.id_paket = r.id_paket', 'left');
	    $this->db->where('s.id_survei', $id_survei);
	    return $this->db->get()->row();
	}

	public function get_all_survei($limit, $start, $keyword = null) {
    $ses = $this->session->userdata('id_employee');
    $jabatan = $this->session->userdata('nama_jabatan');

    $this->db->select('s.*, r.id_request, c.nama AS nama_customer, p.nama_paket');
    $this->db->from('tb_request_survei s');
    $this->db->join('tb_request_pemasangan r', 'r.id_request = s.id_request', 'left');
    $this->db->join('customer c', 'c.id_customer = r.id_customer', 'left');
    $this->db->join('paket p', 'p.id_paket = r.id_paket', 'left');

    // Filter berdasarkan jabatan
    switch ($jabatan) {
        case "Staff Surveyor":
            $this->db->where('s.id_surveyor', $ses);
            break;
        case "Manager Surveyor":
            // $this->db->where_in('s.id_surveyor', $array_id_surveyor);
            break;
    }

    if ($keyword) {
        $this->db->group_start();
        $this->db->like('c.nama', $keyword);
        $this->db->or_like('p.nama_paket', $keyword);
        $this->db->group_end();
    }

    $this->db->order_by('s.created_at', 'DESC');
    $this->db->limit($limit, $start);
    return $this->db->get()->result();
}

public function count_all_survei($keyword = null) {
    $ses = $this->session->userdata('id_employee');
    $jabatan = $this->session->userdata('nama_jabatan');

    $this->db->from('tb_request_survei s');
    $this->db->join('tb_request_pemasangan r', 'r.id_request = s.id_request', 'left');
    $this->db->join('customer c', 'c.id_customer = r.id_customer', 'left');
    $this->db->join('paket p', 'p.id_paket = r.id_paket', 'left');

    // Filter berdasarkan jabatan
    if ($jabatan == "Staff Surveyor") {
        $this->db->where('s.id_surveyor', $ses);
    }

    if ($keyword) {
        $this->db->group_start();
        $this->db->like('c.nama', $keyword);
        $this->db->or_like('p.nama_paket', $keyword);
        $this->db->group_end();
    }

    return $this->db->count_all_results();
}


	public function insert_survei($data) {
	    $this->db->insert('tb_request_survei', $data);
	}


	public function get_surveyor_list() {
        $this->db->select('e.id_employee, e.nama_lengkap');
        $this->db->from('employee e');
        $this->db->join('users u', 'u.id_user = e.id_user');
        $this->db->join('jabatan j', 'j.id_jabatan = e.id_jabatan');

        // Kriteria 1: Role harus 'surveyor'
        $this->db->where('u.role', 'surveyor');

        // Kriteria 2: Nama Jabatan harus 'Staff Surveyor'
        $this->db->where('j.nama_jabatan', 'Staff Surveyor');

        return $this->db->get()->result();
    }

    /**
     * Memperbarui data request survei.
     */
    public function update_survei($id_survei, $data) {
        $this->db->where('id_survei', $id_survei);
        $this->db->update('tb_request_survei', $data);
    }
	
	// end request survei

//==================================================================================
    // dokumen survei
    //get survei all dokumen
    public function get_all_dokumen_paginate($limit, $start, $keyword = null) {
        $ses = $this->session->userdata('id_employee');
        $jabatan = $this->session->userdata('nama_jabatan');

        $this->db->select('d.*, s.tgl_survei, c.nama AS nama_customer, p.nama_paket');
        $this->db->from('tb_dokumen_survei d');
        $this->db->join('tb_request_survei s', 's.id_survei = d.id_survei', 'left');
        $this->db->join('tb_request_pemasangan r', 'r.id_request = s.id_request', 'left');
        $this->db->join('customer c', 'c.id_customer = r.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = r.id_paket', 'left');

        // Filter khusus untuk Staff Surveyor
        if ($jabatan === "Staff Surveyor") {
            $this->db->where('d.id_surveyor', $ses);
        }

        // Keyword search
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('d.keterangan_foto', $keyword);
            $this->db->or_like('c.nama', $keyword);
            $this->db->or_like('p.nama_paket', $keyword);
            $this->db->group_end();
        }

        $this->db->order_by('d.created_at', 'DESC');
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function count_all_dokumen($keyword = null) {
        $ses = $this->session->userdata('id_employee');
        $jabatan = $this->session->userdata('nama_jabatan');

        $this->db->from('tb_dokumen_survei d');
        $this->db->join('tb_request_survei s', 's.id_survei = d.id_survei', 'left');
        $this->db->join('tb_request_pemasangan r', 'r.id_request = s.id_request', 'left');
        $this->db->join('customer c', 'c.id_customer = r.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = r.id_paket', 'left');

        // Filter khusus untuk Staff Surveyor
        if ($jabatan === "Staff Surveyor") {
            $this->db->where('d.id_surveyor', $ses);
        }

        // Keyword search
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('d.keterangan_foto', $keyword);
            $this->db->or_like('c.nama', $keyword);
            $this->db->or_like('p.nama_paket', $keyword);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }



    // Ambil dokumen berdasarkan id_survei
	public function get_dokumen_by_survei($id_survei) {
    $ses = $this->session->userdata('id_employee');
    $jabatan = $this->session->userdata('nama_jabatan');

    $this->db->where('id_survei', $id_survei);

    // Filter khusus untuk Staff Surveyor
    if ($jabatan === "Staff Surveyor") {
        $this->db->where('id_surveyor', $ses);
    }

    return $this->db->get('tb_dokumen_survei')->result();
}


	// Ambil dokumen per id_dokumen
	public function get_dokumen($id_dokumen) {
	    $this->db->where('id_dokumen_survei', $id_dokumen);
	    return $this->db->get('tb_dokumen_survei')->row();
	}

	// Insert dokumen
	public function insert_dokumen($data) {
	    $this->db->insert('tb_dokumen_survei', $data);
	}

	// Update dokumen
	public function update_dokumen($id_dokumen, $data) {
	    $this->db->where('id_dokumen_survei', $id_dokumen);
	    $this->db->update('tb_dokumen_survei', $data);
	}

	// Delete dokumen
	public function delete_dokumen($id_dokumen) {
	    $this->db->where('id_dokumen_survei', $id_dokumen);
	    $this->db->delete('tb_dokumen_survei');
	}

    // end dokumen survei


// ===============
    // get customer sudah survei
    public function get_customer_done_survei_reques()
    {
        $this->db->select('tb_request_pemasangan.id_request, tb_request_pemasangan.id_customer, customer.nama as nama_customer');
        $this->db->from('tb_request_pemasangan');
        $this->db->join('customer', 'customer.id_customer = tb_request_pemasangan.id_customer', 'left');
        return $this->db->get()->result();
    }
// ================

    // ===================== Dokumen Customer =====================

    // Ambil semua dokumen customer dengan pagination dan search
    public function get_all_dokumen_customer_paginate($limit, $start, $keyword = null) {
        $role_ses = $this->session->userdata('role');
        $this->db->select('d.*, c.nama as nama_customer');
        $this->db->from('tb_dokumen_customer d');
        $this->db->join('customer c', 'c.id_customer = d.id_customer', 'left');

        if ($keyword) {
            $this->db->group_start();
            $this->db->like('d.keterangan_foto', $keyword);
            $this->db->or_like('c.nama', $keyword);
            $this->db->group_end();
        }

        $this->db->order_by('d.created_at', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    // Hitung total dokumen customer (untuk pagination)
    public function count_all_dokumen_customer($keyword = null) {
        $this->db->from('tb_dokumen_customer d');
        $this->db->join('customer c', 'c.id_customer = d.id_customer', 'left');

        if ($keyword) {
            $this->db->group_start();
            $this->db->like('d.keterangan_foto', $keyword);
            $this->db->or_like('c.nama', $keyword);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    // Ambil dokumen customer per id_customer
    public function get_dokumen_customer($id_customer) {
        return $this->db->get_where('tb_dokumen_customer', ['id_customer' => $id_customer])->result();
    }

    // Ambil dokumen customer per id_dokumen_customer
    public function get_dokumen_customer_by_id($id_dokumen_customer) {
    $this->db->select('d.*, c.nama as nama_customer');
    $this->db->from('tb_dokumen_customer d');
    $this->db->join('customer c', 'c.id_customer = d.id_customer', 'left');
    $this->db->where('d.id_dokumen_customer', $id_dokumen_customer);
    return $this->db->get()->row();
}


    // Insert dokumen customer
    public function insert_dokumen_customer($data) {
        $this->db->insert('tb_dokumen_customer', $data);
    }

    // Update dokumen customer
    public function update_dokumen_customer($id_dokumen_customer, $data) {
        $this->db->where('id_dokumen_customer', $id_dokumen_customer);
        $this->db->update('tb_dokumen_customer', $data);
    }

    // Delete dokumen customer
    public function delete_dokumen_customer($id_dokumen_customer) {
        $this->db->where('id_dokumen_customer', $id_dokumen_customer);
        $this->db->delete('tb_dokumen_customer');
    }
    /*end dokumen customer*/


// ==================================================================================
    /*reques instalasi noc*/
    
    public function count_all_request_instalasi_customer($keyword = null) {
        $this->db->from('tb_request_pemasangan r');
        $this->db->join('customer c', 'c.id_customer = r.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = r.id_paket', 'left');
        // $this->db->where('r.approval_sales="approval"');
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('c.nama', $keyword);
            $this->db->or_like('c.kode_customer', $keyword);
            $this->db->or_like('p.nama_paket', $keyword);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }

    public function get_all_request_instalasi_customer($limit, $start, $keyword = null) {
        $this->db->select('r.*, c.nama AS nama_customer, c.kode_customer, c.no_hp, p.nama_paket');
        $this->db->from('tb_request_pemasangan r');
        $this->db->join('customer c', 'c.id_customer = r.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = r.id_paket', 'left');
        // $this->db->where('r.approval_sales="approval"');
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('c.nama', $keyword);
            $this->db->or_like('p.nama_paket', $keyword);
            $this->db->group_end();
        }
        $this->db->order_by('r.created_at', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    public function update_request_instalasi($id_request, $data) {
        $this->db->where('id_request', $id_request);
        return $this->db->update('tb_request_pemasangan', $data);
    }

    public function insert_instalasi_customer($data) {
        return $this->db->insert('tb_instalasi_customer', $data);
    }

    // Instalasi methods
    public function count_all_instalasi($keyword = null) {
        $this->db->from('tb_instalasi_customer i');
        $this->db->join('customer c', 'c.id_customer = i.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = i.id_paket', 'left');
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('c.nama', $keyword);
            $this->db->or_like('c.kode_customer', $keyword);
            $this->db->or_like('p.nama_paket', $keyword);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }

    public function get_all_instalasi($limit, $start, $keyword = null) {
        $this->db->select('i.*, c.nama AS nama_customer, c.kode_customer, c.no_hp, p.nama_paket, r.id_request, r.lama_bulan');
        $this->db->from('tb_instalasi_customer i');
        $this->db->join('customer c', 'c.id_customer = i.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = i.id_paket', 'left');
        $this->db->join('tb_request_pemasangan r', 'r.id_request = i.id_request', 'left');
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('c.nama', $keyword);
            $this->db->or_like('p.nama_paket', $keyword);
            $this->db->group_end();
        }
        $this->db->order_by('i.created_at', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    public function get_instalasi_by_id($id) {
        $this->db->select('i.*, c.nama AS nama_customer, c.kode_customer, c.no_hp, p.nama_paket, r.id_request');
        $this->db->from('tb_instalasi_customer i');
        $this->db->join('customer c', 'c.id_customer = i.id_customer', 'left');
        $this->db->join('paket p', 'p.id_paket = i.id_paket', 'left');
        $this->db->join('tb_request_pemasangan r', 'r.id_request = i.id_request', 'left');
        $this->db->where('i.id_instalasi_customer', $id);
        return $this->db->get()->row();
    }

    public function delete_survei($id_survei) {
        $this->db->where('id_survei', $id_survei);
        return $this->db->delete('tb_request_survei');
    }

    /*end reques instalasi noc*/


}
