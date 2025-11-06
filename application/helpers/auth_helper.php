<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('is_logged_in')) {
    /**
     * Cek apakah user sudah login
     * @return bool
     */
    function is_logged_in() {
        $CI =& get_instance();
        return $CI->session->userdata('logged_in') === TRUE;
    }
}

if (!function_exists('get_user_role')) {
    /**
     * Get current user role
     * @return string|null
     */
    function get_user_role() {
        $CI =& get_instance();
        return $CI->session->userdata('role');
    }
}

if (!function_exists('get_user_id')) {
    /**
     * Get current user ID
     * @return int|null
     */
    function get_user_id() {
        $CI =& get_instance();
        return $CI->session->userdata('id_user');
    }
}

if (!function_exists('get_user_name')) {
    /**
     * Get current user name
     * @return string|null
     */
    function get_user_name() {
        $CI =& get_instance();
        return $CI->session->userdata('nama');
    }
}

if (!function_exists('check_role_access')) {
    /**
     * Cek apakah user memiliki role yang diizinkan
     * @param array|string $allowed_roles
     * @param bool $redirect
     * @return bool
     */
    function check_role_access($allowed_roles, $redirect = true) {
        if (!is_logged_in()) {
            if ($redirect) {
                $CI =& get_instance();
                $CI->session->set_flashdata('error', 'Silakan login terlebih dahulu.');
                redirect('auth/login');
            }
            return false;
        }

        $current_role = get_user_role();
        $allowed_roles = is_array($allowed_roles) ? $allowed_roles : [$allowed_roles];

        if (!in_array($current_role, $allowed_roles)) {
            if ($redirect) {
                $CI =& get_instance();
                $CI->session->set_flashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
                redirect('dashboard');
            }
            return false;
        }

        return true;
    }
}

if (!function_exists('require_login')) {
    /**
     * Require user to login
     * @param string $redirect_url
     */
    function require_login($redirect_url = 'auth/login') {
        if (!is_logged_in()) {
            $CI =& get_instance();
            $CI->session->set_flashdata('error', 'Silakan login terlebih dahulu.');
            redirect($redirect_url);
        }
    }
}

if (!function_exists('get_customer_id')) {
    /**
     * Get current customer ID (for customer role) or null for other roles
     * @return int|null
     */
    function get_customer_id() {
        $CI =& get_instance();
        $role = $CI->session->userdata('role');
        $id_customer = $CI->session->userdata('id_customer');

        // For customer role, use id_customer directly
        if ($role === 'customer' && !empty($id_customer)) {
            return $id_customer;
        }

        return null;
    }
}