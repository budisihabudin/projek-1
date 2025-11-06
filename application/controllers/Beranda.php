<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function indexs() {
        $this->load->view('frontend/beranda/index');
    }

}