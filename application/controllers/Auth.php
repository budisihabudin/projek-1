<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }

    // Halaman login
    public function index() {
        $data['title'] = "Login - ERP ISP";
        $this->load->view('frontend/templates/header', $data);
        $this->load->view('auth/login/index', $data);
        $this->load->view('frontend/templates/footer');
    }

    // Proses login
    public function login() {
        if ($this->input->post()) {
            $username = trim($this->input->post('username'));
            $password = trim($this->input->post('password'));

            // Validasi dasar
            if (empty($username) || empty($password)) {
                $this->session->set_flashdata('error', 'Username dan Password wajib diisi.');
                redirect('auth/login');
            }

            $user = $this->User_model->get_by_username($username);

            if ($user && password_verify($password, $user->password)) {
                // Cek status akun
                if ($user->status !== 'active') {
                    $this->session->set_flashdata('error', 'Akun Anda nonaktif, hubungi admin.');
                    redirect('auth/login');
                }

                // Get customer/employee ID based on role and new schema
                $related_id = null;
                $additional_nama = null;

                if ($user->role === 'customer') {
                    // For customers, get id_customer from customer table
                    $this->db->select('id_customer, nama');
                    $this->db->where('id_user', $user->id_user);
                    $customer_data = $this->db->get('customer')->row();
                    $related_id = $customer_data ? $customer_data->id_customer : null;
                    $additional_nama = $customer_data ? $customer_data->nama : null;
                } else {
                    // For employee roles, get id_employee from employee table
                    $this->db->select('e.id_employee, e.nama_lengkap, j.nama_jabatan');
                    $this->db->from('employee e');
                    $this->db->join('jabatan j', 'j.id_jabatan = e.id_jabatan', 'left');
                    $this->db->where('e.id_user', $user->id_user);
                    $employee_data = $this->db->get()->row();
                    $related_id = $employee_data ? $employee_data->id_employee : null;
                    $additional_nama = $employee_data ? $employee_data->nama_lengkap : null;
                }

                // Set session login with new structure
                $this->session->set_userdata([
                    'id_user'      => $user->id_user,
                    'id_employee'  => ($user->role !== 'customer') ? $related_id : null,
                    'id_customer'  => ($user->role === 'customer') ? $related_id : null,
                    'nama'         => $user->full_name ?? $additional_nama ?? $user->username,
                    'email'        => $user->email,
                    'phone'        => $user->phone,
                    'role'         => $user->role,
                    'modified_by'  => null,
                    'nama_jabatan'  => ($user->role !== 'customer' && isset($employee_data->nama_jabatan)) ? $employee_data->nama_jabatan : null,
                    'akses_menu'   => $user->akses_menu ?? null,
                    'logged_in'    => TRUE
                ]);
//                 echo "<pre>";
// print_r($this->session->userdata());
// echo "</pre>";
// die();

                // Redirect berdasarkan role
                if ($user->role == 'admin') {
                    $this->session->set_flashdata('success', 'Selamat datang, Admin!');
                } elseif ($user->role == 'customer') {
                    $this->session->set_flashdata('success', 'Selamat datang, Customer!');
                } elseif ($user->role == 'finance') {
                    $this->session->set_flashdata('success', 'Selamat datang, Finance!');
                } else {
                    $this->session->set_flashdata('success', 'Selamat datang!');
                }
                redirect('dashboard');

            } else {
                $this->session->set_flashdata('error', 'Username atau Password salah.');
                redirect('auth/login');
            }

        } else {
            $data['title'] = "Login - ERP ISP";
            $this->load->view('frontend/templates/header', $data);
            $this->load->view('auth/login/index', $data);
            $this->load->view('frontend/templates/footer');
        }
    }


    // Halaman register (opsional)
    public function register() {
        if ($this->input->post()) {
            // ⬇️ Load Models (Pastikan ini sudah ada)
            $this->load->model('User_model');
            $this->load->model('Customer_model');

            // 1️⃣ Validasi duplikasi data
            $username = $this->input->post('username');
            $email = $this->input->post('email');

            // Cek username sudah ada
            $cek_username = $this->db->get_where('users', ['username' => $username])->row();
            if ($cek_username) {
                $this->session->set_flashdata('error', 'Username sudah digunakan, silakan pilih username lain.');
                redirect('auth/register');
            }

            // Cek email sudah ada
            $cek_email = $this->db->get_where('users', ['email' => $email])->row();
            if ($cek_email) {
                $this->session->set_flashdata('error', 'Email sudah digunakan, silakan gunakan email lain.');
                redirect('auth/register');
            }

            // 2️⃣ Cek kode reseller jika ada
            $id_reseller = null;
            $kode_reseller = $this->input->post('kode_member');
            if (!empty($kode_reseller)) {
                // Cari user reseller berdasarkan username
                $this->db->select('id_user');
                $this->db->where('role', 'reseller');
                $this->db->where('status', 'active');
                $this->db->where('username', $kode_reseller);
                $reseller = $this->db->get('users')->row();

                if ($reseller) {
                    $id_reseller = $reseller->id_user;
                } else {
                    // Jika kode reseller tidak valid, beri warning tapi tetap lanjut
                    $this->session->set_flashdata('warning', 'Kode reseller tidak ditemukan. Registrasi tetap dilanjutkan tanpa reseller.');
                }
            }

            // 3️⃣ Generate Kode Customer Otomatis (CUST-000001, dst.)
            $kode_customer = $this->_generate_next_kode_customer();

            // 4️⃣ Insert ke tabel USERS dulu
            $data_user = [
                'username'     => $username,
                'password'     => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'role'         => 'customer',
                'status'       => 'active',
                'full_name'    => $this->input->post('nama'),
                'email'        => $email,
                'phone'        => $this->input->post('no_hp')
            ];

            $this->User_model->insert($data_user);
            $id_user = $this->db->insert_id();

            // 5️⃣ Persiapkan dan Insert data ke tabel CUSTOMER
            $data_customer = [
                'kode_customer' => $kode_customer,
                'id_user'       => $id_user,        // Foreign key ke users
                'id_reseller'   => $id_reseller,    // Optional: NULL jika tidak ada reseller
                'nama'          => $this->input->post('nama'),
                'alamat'        => $this->input->post('alamat'),
                'no_hp'         => $this->input->post('no_hp'),
                'email'         => $email,
                'status_customer' => 'pending'      // Status pending untuk approval admin
            ];

            $this->Customer_model->insert_customer_regis($data_customer);

            // 6️⃣ Notifikasi & redirect
            $this->session->set_flashdata('success', 'Registrasi berhasil! Akun Anda sedang dalam proses verifikasi admin.');
            redirect('auth/login');
        } else {
            $data['title'] = "Register - ERP ISP";
            $this->load->view('frontend/templates/header', $data);
            $this->load->view('auth/register/index', $data);
            $this->load->view('frontend/templates/footer');
        }
    }
 
    private function _generate_next_kode_customer()
    {
        // Ambil kode customer terakhir
        $this->db->select('kode_customer');
        $this->db->like('kode_customer', 'CUST-', 'after');
        $this->db->order_by('kode_customer', 'DESC');
        $this->db->limit(1);
        $last_customer = $this->db->get('customer')->row();

        $prefix = 'CUST-';
        $next_number = 1;
        $padding = 6; // Menggunakan 6 digit padding (000001)

        if ($last_customer) {
            $last_kode = $last_customer->kode_customer;
            // Ambil bagian nomor urut dari kode terakhir
            $number_part = substr($last_kode, strlen($prefix));
            $last_number = (int)$number_part;
            $next_number = $last_number + 1;
        }

        // Format nomor urut menjadi string dengan padding (misal: 1 -> "000001")
        $new_number_padded = str_pad($next_number, $padding, '0', STR_PAD_LEFT);

        return $prefix . $new_number_padded;
    }

    // Logout
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
