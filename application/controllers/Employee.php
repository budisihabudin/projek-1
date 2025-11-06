<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Model_employee');

        $role = $this->session->userdata('role');
        if (!$this->session->userdata('logged_in') || !in_array($role, ['admin'])) {
            redirect('auth/login');
        }
    }

    public function index() {
        $data['title'] = 'Daftar Employee';
        $this->load->library('pagination');

        $keyword = $this->input->get('keyword');
        $config['base_url'] = site_url('employee/index');
        $config['total_rows'] = $this->Model_employee->count_all_employees($keyword);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['employees'] = $this->Model_employee->get_employees($config['per_page'], $page, $keyword);
        
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/employee/index', $data);
        $this->load->view('backend/templates/footer');
    }

    public function add() {
        $data['title'] = 'Tambah Employee';
        $data['jabatan'] = $this->Model_employee->get_all_jabatan();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/employee/form', $data);
        $this->load->view('backend/templates/footer');
    }

   public function store() {
    // DEBUG: Log all POST data received
    log_message('debug', '=== EMPLOYEE STORE DEBUG START ===');
    log_message('debug', 'POST DATA: ' . print_r($_POST, true));
    log_message('debug', 'Method: ' . $this->input->server('REQUEST_METHOD'));

    // 1. Definisikan aturan validasi
    $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
    $this->form_validation->set_rules('id_jabatan', 'Jabatan', 'required');
    $this->form_validation->set_rules('role', 'Role', 'required');
    $this->form_validation->set_rules('status', 'Status Akun', 'required');

    // ATURAN KRUSIAL: Memeriksa apakah username unik di tabel 'users'
    $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]', [
        'is_unique' => 'Username ini sudah digunakan, silakan pilih yang lain.'
    ]);
    $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]'); // Tambah validasi min_length

    // 2. Jalankan validasi
    log_message('debug', 'Form validation result: ' . ($this->form_validation->run() == FALSE ? 'FAILED' : 'PASSED'));

    // Check if this is an AJAX request (multiple methods)
    $is_ajax = ($this->input->get('ajax') == '1') ||  // URL parameter
               ($this->input->post('form_submitted') &&
                (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'));

    // Debug: Log AJAX detection
    log_message('debug', 'AJAX Detection:');
    log_message('debug', '- GET[ajax]: ' . $this->input->get('ajax'));
    log_message('debug', '- POST[form_submitted]: ' . $this->input->post('form_submitted'));
    log_message('debug', '- HTTP_X_REQUESTED_WITH: ' . ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? 'not set'));
    log_message('debug', '- Is AJAX: ' . ($is_ajax ? 'YES' : 'NO'));

    if ($this->form_validation->run() == FALSE) {
        // DEBUG: Log validation errors
        log_message('debug', 'Validation errors: ' . print_r($this->form_validation->error_array(), true));

        if ($is_ajax) {
            // Return JSON response for AJAX requests
            $errors = $this->form_validation->error_array();
            $error_string = '';
            foreach ($errors as $field => $error) {
                $error_string .= $error . '<br>';
            }

            $response = [
                'success' => false,
                'message' => $error_string,
                'errors' => $errors
            ];

            log_message('debug', 'Sending JSON error response: ' . json_encode($response));

            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }

        // Jika validasi gagal, kembalikan ke form 'add' dengan error
        $data['title'] = 'Tambah Employee';
        $data['jabatan'] = $this->Model_employee->get_all_jabatan();

        // PENTING: Muat ulang view dengan error
        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/employee/form', $data);
        $this->load->view('backend/templates/footer');
        return; // Hentikan eksekusi di sini
    }

    // Jika validasi berhasil, lanjutkan proses simpan

    // Data Employee
    $nama = $this->input->post('nama_lengkap');
    $alamat = $this->input->post('alamat');
    $telepon = $this->input->post('telepon');
    $email = $this->input->post('email');
    $id_jabatan = $this->input->post('id_jabatan');

    // Data User
    $username = $this->input->post('username');
    $password = $this->input->post('password');
    $role = $this->input->post('role');
    $status = $this->input->post('status');

    // DEBUG: Log extracted data
    log_message('debug', 'Extracted employee data:');
    log_message('debug', '- Nama: ' . $nama);
    log_message('debug', '- Alamat: ' . $alamat);
    log_message('debug', '- Telepon: ' . $telepon);
    log_message('debug', '- Email: ' . $email);
    log_message('debug', '- ID Jabatan: ' . $id_jabatan);

    log_message('debug', 'Extracted user data:');
    log_message('debug', '- Username: ' . $username);
    log_message('debug', '- Password length: ' . strlen($password));
    log_message('debug', '- Role: ' . $role);
    log_message('debug', '- Status: ' . $status);

    $data_employee = [
        'nama_lengkap' => $nama,
        'alamat' => $alamat,
        'telepon' => $telepon,
        'email' => $email,
        'id_jabatan' => $id_jabatan,
        'created_at' => date('Y-m-d H:i:s')
    ];

    // DEBUG: Log data arrays before insert
    log_message('debug', 'Employee data array: ' . print_r($data_employee, true));

    $data_user = [
        'username' => $username,
        'password' => password_hash($password, PASSWORD_BCRYPT),
        'role' => $role,
        'status' => $status,
        'full_name' => $nama,
        'email' => $email,
        'phone' => $telepon
    ];

    // DEBUG: Log user data array
    log_message('debug', 'User data array: ' . print_r($data_user, true));

    try {
        log_message('debug', 'Attempting to insert employee data...');
        $result = $this->Model_employee->insert_employee($data_employee, $data_user);
        log_message('debug', 'Insert result: ' . ($result ? 'SUCCESS: ' . $result : 'FAILED'));

        if ($result) {
            $success_message = 'Employee berhasil ditambahkan. User ID: ' . $result;
            log_message('debug', 'Employee added successfully with User ID: ' . $result);

            if ($is_ajax) {
                // Return JSON response for AJAX requests
                $response = [
                    'success' => true,
                    'message' => $success_message,
                    'user_id' => $result
                ];

                log_message('debug', 'Sending JSON success response: ' . json_encode($response));

                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
                return;
            }

            $this->session->set_flashdata('success', $success_message);
        } else {
            $error_message = 'Gagal menambahkan employee.';
            log_message('error', 'Failed to add employee - no result returned');

            if ($is_ajax) {
                // Return JSON response for AJAX requests
                $response = [
                    'success' => false,
                    'message' => $error_message
                ];

                log_message('debug', 'Sending JSON error response (no result): ' . json_encode($response));

                $this->output
                    ->set_status_header(500)
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
                return;
            }

            $this->session->set_flashdata('error', $error_message);
        }
    } catch (Exception $e) {
        log_message('error', 'Error adding employee: ' . $e->getMessage());
        log_message('error', 'Exception trace: ' . $e->getTraceAsString());
        $error_message = 'Terjadi kesalahan saat menambahkan employee: ' . $e->getMessage();

        if ($is_ajax) {
            // Return JSON response for AJAX requests
            $response = [
                'success' => false,
                'message' => $error_message
            ];

            log_message('debug', 'Sending JSON exception response: ' . json_encode($response));

            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }

        $this->session->set_flashdata('error', $error_message);
    }

    log_message('debug', '=== EMPLOYEE STORE DEBUG END ===');

    // Only redirect if not AJAX
    if (!$is_ajax) {
        redirect('employee');
    }
}

    public function edit($id) {
        $data['title'] = 'Edit Employee';
        $data['employee'] = $this->Model_employee->get_employee($id);
        $data['jabatan'] = $this->Model_employee->get_all_jabatan();

        $this->load->view('backend/templates/header', $data);
        $this->load->view('backend/templates/sidebar', $data);
        $this->load->view('backend/employee/form', $data);
        $this->load->view('backend/templates/footer');
    }

   public function update($id) {
        // Validation rules
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('id_jabatan', 'Jabatan', 'required');
        $this->form_validation->set_rules('role', 'Role', 'required');
        $this->form_validation->set_rules('status', 'Status Akun', 'required');

        // Get current employee to check current username
        $current_employee = $this->Model_employee->get_employee($id);
        $current_username = $current_employee->username ?? '';

        // Username validation - only check uniqueness if changed
        $username = $this->input->post('username');
        if ($username !== $current_username) {
            $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]', [
                'is_unique' => 'Username ini sudah digunakan, silakan pilih yang lain.'
            ]);
        } else {
            $this->form_validation->set_rules('username', 'Username', 'required');
        }

        // Password validation - only required if creating new user or if changed
        $password = $this->input->post('password');
        if (!empty($password)) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
        }

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, return to form with errors
            $data['title'] = 'Edit Employee';
            $data['employee'] = $current_employee;
            $data['jabatan'] = $this->Model_employee->get_all_jabatan();

            $this->load->view('backend/templates/header', $data);
            $this->load->view('backend/templates/sidebar', $data);
            $this->load->view('backend/employee/form', $data);
            $this->load->view('backend/templates/footer');
            return;
        }

        // Data Employee
        $nama = $this->input->post('nama_lengkap');
        $alamat = $this->input->post('alamat');
        $telepon = $this->input->post('telepon');
        $email = $this->input->post('email');
        $id_jabatan = $this->input->post('id_jabatan');

        // Data User
        $username = $this->input->post('username');
        $role = $this->input->post('role');
        $status = $this->input->post('status');

        $data_employee = [
            'nama_lengkap' => $nama,
            'alamat' => $alamat,
            'telepon' => $telepon,
            'email' => $email,
            'id_jabatan' => $id_jabatan
        ];

        $data_user = [
            'username' => $username,
            'role' => $role,
            'status' => $status,
            'full_name' => $nama,
            'email' => $email,
            'phone' => $telepon
        ];

        // Hanya perbarui password jika diisi
        if (!empty($password)) {
            $data_user['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->Model_employee->update_employee($id, $data_employee, $data_user);
        $this->session->set_flashdata('success', 'Employee berhasil diperbarui.');
        redirect('employee');
    }

    public function delete($id) {
        $this->Model_employee->delete_employee($id);
        $this->session->set_flashdata('success', 'Employee berhasil dihapus.');
        redirect('employee');
    }
}
