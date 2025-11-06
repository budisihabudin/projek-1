<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['default_controller'] = 'auth';

// Route untuk webhook Midtrans (public endpoint, tidak perlu login)
$route['payment/notification'] = 'payment/notification';

$route['404_override'] = 'errors/not_found';
$route['translate_uri_dashes'] = FALSE;