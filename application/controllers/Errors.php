<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends CI_Controller {

    public function not_found() {
        $data['message'] = 'Ges, Halaman tidak ditemukan. Sedang dalam pengembangan.';
        $this->load->view('custom_404', $data);
    }
}
