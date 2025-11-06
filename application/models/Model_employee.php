<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_employee extends CI_Model {

    // ===========================
    // Get all employees with pagination and search
    // ===========================
    public function get_employees($limit, $start, $keyword = null) {
        // MENAMBAH KOLOM USERNAME, STATUS, DAN CREATED_AT
        $this->db->select('e.id_employee, e.nama_lengkap, e.id_jabatan, e.created_at, j.nama_jabatan, u.id_user, u.username, u.role, u.status');
        $this->db->from('employee e');
        $this->db->join('jabatan j', 'j.id_jabatan = e.id_jabatan', 'left');
        $this->db->join('users u', 'u.id_user = e.id_user', 'left');

        if ($keyword) {
            $this->db->group_start();
            $this->db->like('e.nama_lengkap', $keyword);
            $this->db->or_like('j.nama_jabatan', $keyword);
            $this->db->or_like('u.role', $keyword);
            $this->db->or_like('u.username', $keyword); // MENAMBAH PENCARIAN BERDASARKAN USERNAME
            $this->db->group_end();
        }

        $this->db->order_by('e.id_employee', 'DESC');
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    // ===========================
    // Count all employees (for pagination)
    // ===========================
    public function count_all_employees($keyword = null) {
        $this->db->from('employee e');
        $this->db->join('jabatan j', 'j.id_jabatan = e.id_jabatan', 'left');
        $this->db->join('users u', 'u.id_user = e.id_user', 'left');

        if ($keyword) {
            $this->db->group_start();
            $this->db->like('e.nama_lengkap', $keyword);
            $this->db->or_like('j.nama_jabatan', $keyword);
            $this->db->or_like('u.role', $keyword);
            $this->db->or_like('u.username', $keyword); // MENAMBAH PENCARIAN BERDASARKAN USERNAME
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    // ===========================
    // Get single employee by ID
    // ===========================
    public function get_employee($id) {
        // MENAMBAH KOLOM USERNAME, STATUS, DAN CREATED_AT
        $this->db->select('e.id_employee, e.nama_lengkap, e.id_jabatan, e.created_at, j.nama_jabatan, u.id_user, u.username, u.role, u.status');
        $this->db->from('employee e');
        $this->db->join('jabatan j', 'j.id_jabatan = e.id_jabatan', 'left');
        $this->db->join('users u', 'u.id_user = e.id_user', 'left');
        $this->db->where('e.id_employee', $id);
        return $this->db->get()->row();
    }

    // ===========================
    // Insert new employee + user
    // ===========================
    public function insert_employee($data_employee, $data_user) {
        log_message('debug', '=== MODEL INSERT EMPLOYEE DEBUG START ===');

        // DEBUG: Log database operations
        log_message('debug', 'Inserting user data first...');
        log_message('debug', 'User data: ' . print_r($data_user, true));

        // Insert ke user dulu untuk mendapatkan id_user
        $this->db->insert('users', $data_user);

        // Check if user insert was successful
        $affected_rows = $this->db->affected_rows();
        log_message('debug', 'User insert affected rows: ' . $affected_rows);

        if ($affected_rows <= 0) {
            log_message('error', 'User insert failed - no affected rows');
            log_message('error', 'Last SQL error: ' . $this->db->error()['message']);
            return false;
        }

        $id_user = $this->db->insert_id();
        log_message('debug', 'Generated user ID: ' . $id_user);

        // Insert ke employee dengan id_user dari user yang baru dibuat
        $data_employee['id_user'] = $id_user;
        log_message('debug', 'Employee data to insert: ' . print_r($data_employee, true));

        $this->db->insert('employee', $data_employee);

        // Check if employee insert was successful
        $employee_affected = $this->db->affected_rows();
        log_message('debug', 'Employee insert affected rows: ' . $employee_affected);

        if ($employee_affected <= 0) {
            log_message('error', 'Employee insert failed - no affected rows');
            log_message('error', 'Last SQL error: ' . $this->db->error()['message']);
            return false;
        }

        $id_employee = $this->db->insert_id();
        log_message('debug', 'Generated employee ID: ' . $id_employee);

        log_message('debug', '=== MODEL INSERT EMPLOYEE DEBUG END ===');
        return $id_user;
    }

    // ===========================
    // Update employee + optional user
    // ===========================
    public function update_employee($id, $data_employee, $data_user = null) {
        // Update employee data
        $this->db->where('id_employee', $id);
        $this->db->update('employee', $data_employee);

        if ($data_user) {
            // Get the id_user from employee first
            $employee = $this->db->get_where('employee', ['id_employee' => $id])->row();
            if ($employee && isset($employee->id_user)) {
                $this->db->where('id_user', $employee->id_user);
                $this->db->update('users', $data_user);
            }
        }
    }

    // ===========================
    // Delete employee + user
    // ===========================
    public function delete_employee($id) {
        // Get the id_user from employee first
        $employee = $this->db->get_where('employee', ['id_employee' => $id])->row();

        if ($employee && isset($employee->id_user)) {
            // 1. Hapus data dari tabel 'users' terlebih dahulu
            $this->db->where('id_user', $employee->id_user);
            $this->db->delete('users');
        }

        // 2. Hapus data dari tabel 'employee'
        $this->db->where('id_employee', $id);
        $this->db->delete('employee');
    }

    // ===========================
    // Get all jabatan for dropdown (FUNGSI INI YANG HILANG)
    // ===========================
    public function get_all_jabatan() {
        return $this->db->get('jabatan')->result();
    }
}