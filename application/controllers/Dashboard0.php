<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->view('backend/templates/header');
        $this->load->view('backend/templates/sidebar');
        $this->load->view('backend/templates/topbar');
        $this->load->view('dashboard/admin');
        $this->load->view('backend/templates/footer');
    }

}