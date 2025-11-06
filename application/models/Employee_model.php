<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_model extends CI_Model {

    private $table = "employee";
    private $table_users = "users";

    // get sales
    public function get_sales()
    {
        $this->db->select('employee.id_employee, employee.nama_lengkap, employee.alamat, employee.telepon, employee.email, employee.created_at,
                           jabatan.nama_jabatan, users.username, users.role');
        $this->db->from('employee');
        $this->db->join('jabatan', 'jabatan.id_jabatan = employee.id_jabatan', 'left');
        $this->db->join('users', 'users.id_user = employee.id_user', 'left');
        $this->db->where('users.role', 'sales');
        return $this->db->get()->result();
    }

    // end get sales

    public function get_all($limit, $offset, $keyword = null) {
        $this->db->select('employee.*, jabatan.nama_jabatan, users.username, users.role');
        $this->db->from('employee');
        $this->db->join('jabatan', 'jabatan.id_jabatan = employee.id_jabatan', 'left');
        $this->db->join('users', 'users.id_user = employee.id_user', 'left'); // relasi baru
        if ($keyword) {
            $this->db->like('employee.nama_lengkap', $keyword);
            $this->db->or_like('jabatan.nama_jabatan', $keyword);
            $this->db->or_like('users.username', $keyword);
        }
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_all($keyword = null) {
        $this->db->from('employee');
        $this->db->join('jabatan', 'jabatan.id_jabatan = employee.id_jabatan', 'left');
        $this->db->join('users', 'users.id_user = employee.id_user', 'left');
        if ($keyword) {
            $this->db->like('employee.nama_lengkap', $keyword);
            $this->db->or_like('jabatan.nama_jabatan', $keyword);
            $this->db->or_like('users.username', $keyword);
        }
        return $this->db->count_all_results();
    }

    public function get($id) {
        $this->db->select('employee.*, jabatan.nama_jabatan, users.username, users.role');
        $this->db->from('employee');
        $this->db->join('jabatan', 'jabatan.id_jabatan = employee.id_jabatan', 'left');
        $this->db->join('users', 'users.id_user = employee.id_user', 'left');
        $this->db->where('id_employee', $id);
        return $this->db->get()->row();
    }

    public function insert($data_employee, $data_user) {
        $this->db->trans_start();
        $this->db->insert('employee', $data_employee);
        $id_employee = $this->db->insert_id();

        // Insert ke users dengan proper mapping
        $data_user['id_employee'] = $id_employee;  // Hubungkan ke employee
        // Password sudah di-hash di controller
        $this->db->insert('users', $data_user);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function update($id, $data_employee, $data_user = null) {
        $this->db->trans_start();
        $this->db->where('id_employee', $id);
        $this->db->update('employee', $data_employee);

        if ($data_user) {
            if (!empty($data_user['password'])) {
                $data_user['password'] = password_hash($data_user['password'], PASSWORD_BCRYPT);
            } else {
                unset($data_user['password']);
            }
            // Get the user_id from employee first
            $employee = $this->db->get_where('employee', ['id_employee' => $id])->row();
            if ($employee) {
                $this->db->where('id_user', $employee->id_user);
                $this->db->update('users', $data_user);
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function delete($id) {
        $this->db->trans_start();

        // Get the user_id from employee first
        $employee = $this->db->get_where('employee', ['id_employee' => $id])->row();

        $this->db->where('id_employee', $id);
        $this->db->delete('employee');

        if ($employee) {
            $this->db->where('id_user', $employee->id_user);
            $this->db->delete('users');
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
