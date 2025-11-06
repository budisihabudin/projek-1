<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dokumen_model extends CI_Model {

    protected $table = 'dokumen_customer';

    
    public function insert_dokumen($id_histori, $tipe_dokumen, $file_path) {
        $data = [
            'id_histori'      => $id_histori,
            'tipe_dokumen'    => $tipe_dokumen,
            'file_path'       => $file_path,
            'tgl_upload'      => date('Y-m-d H:i:s')
            // status_approval defaultnya 'pending' di SQL
        ];
        return $this->db->insert($this->table, $data);
    }
    
  
    public function get_by_histori($id_histori) {
        return $this->db->get_where($this->table, ['id_histori' => $id_histori])->result();
    }
    
    
}