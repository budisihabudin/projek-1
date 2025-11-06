<div class="container my-4">
    <h3><?= $title ?></h3>
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-body">
            <?php
                // Tentukan apakah ini mode edit atau tambah
                $is_edit = isset($employee);
                $action_url = $is_edit ? site_url('employee/update/'.$employee->id_employee) : site_url('employee/store');

                // DEBUG: Log form action URL
                error_log('Employee form action URL: ' . $action_url);
                error_log('Form mode: ' . ($is_edit ? 'EDIT' : 'ADD'));
            ?>

            <!-- DEBUG: Show validation errors if any -->
            <?php if(validation_errors()): ?>
                <div class="alert alert-danger">
                    <h6>Validation Errors:</h6>
                    <?= validation_errors() ?>
                </div>
            <?php endif; ?>

            <!-- DEBUG: Show flash messages -->
            <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <?= $this->session->flashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= $action_url ?>" method="POST" id="employeeForm">
                <!-- DEBUG: Hidden field to track form submission -->
                <input type="hidden" name="form_submitted" value="1">
                <input type="hidden" name="debug_timestamp" value="<?= time() ?>">
                
                <h5>Data Pribadi</h5>
                <div class="mb-3">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap" required
                        value="<?= $is_edit ? htmlspecialchars($employee->nama_lengkap) : '' ?>">
                </div>

                <div class="mb-3">
                    <label>Alamat</label>
                    <input type="text" name="alamat" class="form-control" required
                        value="<?= $is_edit ? htmlspecialchars($employee->alamat ?? '') : '' ?>"
                        placeholder="Masukkan alamat lengkap">
                </div>

                <div class="mb-3">
                    <label>Telepon</label>
                    <input type="number" name="telepon" class="form-control" required
                        value="<?= $is_edit ? htmlspecialchars($employee->telepon ?? '') : '' ?>"
                        placeholder="Masukkan nomor telepon">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required
                        value="<?= $is_edit ? htmlspecialchars($employee->email ?? '') : '' ?>"
                        placeholder="Masukkan alamat email">
                </div>


                <div class="mb-3">
                    <label for="id_jabatan" class="form-label">Jabatan</label>
                    <select class="form-control jabatan-select" id="id_jabatan" name="id_jabatan" required>
                        <option value="">-- Pilih Jabatan --</option>
                        <?php foreach($jabatan as $j): ?>
                            <option value="<?= $j->id_jabatan ?>" <?= ($is_edit && $employee->id_jabatan == $j->id_jabatan) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($j->nama_jabatan) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <hr class="my-4">
                <h5>Data Akun Login</h5>
                
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-control role-akun-select" id="role" name="role" required>
                        <option value="">-- Pilih Role --</option>
                        <?php 
                            $roles = ['admin','sales','finance','noc','surveyor','thd']; 
                            $current_role = $is_edit ? ($employee->role ?? '') : ''; // Ambil role saat edit
                            foreach($roles as $r): 
                        ?>
                            <option value="<?= $r ?>" <?= ($current_role == $r) ? 'selected' : '' ?>>
                                <?= ucfirst($r) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text"  class="form-control" id="username" name="username" placeholder="Masukkan username" required
                        value="<?= $is_edit ? htmlspecialchars($employee->username ?? '') : '' ?>">
                    <?php if ($is_edit): ?>
                        <small class="text-muted">Username akan digunakan untuk login.</small>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" 
                        placeholder="<?= $is_edit ? 'Kosongkan jika tidak diubah' : 'Masukkan password' ?>" 
                        <?= $is_edit ? '' : 'required' ?>>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status Akun</label>
                    <select class="form-control" id="status" name="status" required>
                        <?php $current_status = $is_edit ? ($employee->status ?? '1') : '1'; ?>
                        <option value="1" <?= ($current_status == '1') ? 'selected' : '' ?>>Aktif</option>
                        <option value="0" <?= ($current_status == '0') ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?= $is_edit ? 'Update' : 'Simpan' ?></button>
                <a href="<?= site_url('employee') ?>" class="btn btn-secondary"><i class="fa fa-times"></i> Batal</a>
            </form>
        </div>
    </div>
</div>

<!-- DEBUG: Enhanced Debug Panel -->
<div class="alert alert-info mt-3">
    <h6><i class="fa fa-bug"></i> Debug Info</h6>
    <div id="debugInfo">
        <small>Mode: <?= $is_edit ? 'EDIT' : 'TAMBAH' ?></small><br>
        <small>Action URL: <?= $action_url ?></small><br>
        <small id="formStatus">Form ready for submission</small>
    </div>

    <!-- Error messages container -->
    <div id="errorContainer" style="display: none;" class="mt-2">
        <div class="alert alert-danger">
            <h6><i class="fa fa-exclamation-triangle"></i> Validation Errors</h6>
            <div id="errorList"></div>
        </div>
    </div>

    <!-- Success message container -->
    <div id="successContainer" style="display: none;" class="mt-2">
        <div class="alert alert-success">
            <h6><i class="fa fa-check-circle"></i> Success!</h6>
            <div id="successMessage"></div>
        </div>
    </div>
</div>

<!-- DEBUG: AJAX Form with Validation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('employeeForm');
    const debugInfo = document.getElementById('formStatus');
    const errorContainer = document.getElementById('errorContainer');
    const errorList = document.getElementById('errorList');
    const successContainer = document.getElementById('successContainer');
    const successMessage = document.getElementById('successMessage');
    const submitBtn = form.querySelector('button[type="submit"]');

    // Hide containers initially
    errorContainer.style.display = 'none';
    successContainer.style.display = 'none';

    if (form) {
        // Prevent normal form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Hide previous messages
            errorContainer.style.display = 'none';
            successContainer.style.display = 'none';

            debugInfo.innerHTML = '<span class="text-warning">Validating form...</span>';

            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';

            // Get all form data
            const formData = new FormData(this);
            let dataPreview = '<strong>Form Data:</strong><br>';

            for (let [key, value] of formData.entries()) {
                if (key === 'password') {
                    value = '***(' + value.length + ' chars)***';
                }
                dataPreview += `&nbsp;&nbsp;${key}: ${value}<br>`;
            }

            debugInfo.innerHTML = dataPreview;

            // Client-side validation first
            const validationErrors = validateForm(formData);

            if (validationErrors.length > 0) {
                showErrors(validationErrors);
                enableSubmitButton();
                return false;
            }

            // Send data via AJAX with proper headers
            const xhr = new XMLHttpRequest();
            xhr.open('POST', this.action + '?ajax=1', true); // Add ajax parameter to URL
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.onload = function() {
                enableSubmitButton();

                if (xhr.status >= 200 && xhr.status < 300) {
                    debugInfo.innerHTML += '<br><span class="text-info">Server response received, parsing...</span>';
                    debugInfo.innerHTML += '<br><span class="text-muted">Response: ' + xhr.responseText.substring(0, 200) + '...</span>';

                    try {
                        const response = JSON.parse(xhr.responseText);

                        if (response.success) {
                            showSuccess(response.message);
                            debugInfo.innerHTML += '<br><span class="text-success">✓ Data saved successfully!</span>';

                            // Redirect after 2 seconds
                            setTimeout(function() {
                                window.location.href = '<?= site_url('employee') ?>';
                            }, 2000);
                        } else {
                            showErrors([response.message || 'Unknown error occurred']);
                            debugInfo.innerHTML += '<br><span class="text-danger">✗ Server validation failed</span>';
                        }
                    } catch (e) {
                        debugInfo.innerHTML += '<br><span class="text-warning">⚠ Not JSON response, treating as redirect...</span>';
                        debugInfo.innerHTML += '<br><span class="text-muted">Parse error: ' + e.message + '</span>';

                        // If response is HTML, it means regular form processing occurred
                        if (xhr.responseText.includes('<!DOCTYPE') || xhr.responseText.includes('<html')) {
                            debugInfo.innerHTML += '<br><span class="text-success">✓ Form submitted via regular POST (redirecting...)</span>';
                            // Create a temporary div to parse the HTML and extract any messages
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = xhr.responseText;

                            // Look for success/error messages in the response
                            const successElements = tempDiv.querySelectorAll('.alert-success');
                            const errorElements = tempDiv.querySelectorAll('.alert-danger');

                            if (successElements.length > 0) {
                                showSuccess('Employee berhasil ditambahkan!');
                            } else if (errorElements.length > 0) {
                                showErrors(['Validation failed - check form fields']);
                            } else {
                                debugInfo.innerHTML += '<br><span class="text-success">✓ Processing complete, redirecting...</span>';
                            }

                            setTimeout(function() {
                                window.location.href = '<?= site_url('employee') ?>';
                            }, 2000);
                        } else {
                            showErrors(['Unexpected server response format']);
                            debugInfo.innerHTML += '<br><span class="text-danger">✗ Unexpected response format</span>';
                        }
                    }
                } else {
                    showErrors(['Server error: HTTP ' + xhr.status]);
                    debugInfo.innerHTML += '<br><span class="text-danger">✗ HTTP Error: ' + xhr.status + ' ' + xhr.statusText + '</span>';
                }
            };

            xhr.onerror = function() {
                enableSubmitButton();
                showErrors(['Network error occurred']);
                debugInfo.innerHTML += '<br><span class="text-danger">✗ Network error</span>';
            };

            debugInfo.innerHTML += '<br><span class="text-info">Sending to server...</span>';
            debugInfo.innerHTML += '<br><span class="text-muted">URL: ' + this.action + '?ajax=1</span>';
            xhr.send(formData);
        });
    }

    function validateForm(formData) {
        const errors = [];
        const requiredFields = ['nama_lengkap', 'id_jabatan', 'username', 'password', 'role', 'status'];

        requiredFields.forEach(function(fieldName) {
            const value = formData.get(fieldName);
            if (!value || !value.trim()) {
                errors.push(fieldName.replace('_', ' ').toUpperCase() + ' is required');
            }
        });

        // Password validation
        const password = formData.get('password');
        if (password && password.length < 6) {
            errors.push('Password must be at least 6 characters');
        }

        // Email validation
        const email = formData.get('email');
        if (email && !isValidEmail(email)) {
            errors.push('Please enter a valid email address');
        }

        // Phone validation
        const telepon = formData.get('telepon');
        if (telepon && !isValidPhone(telepon)) {
            errors.push('Please enter a valid phone number');
        }

        return errors;
    }

    function showErrors(errors) {
        errorList.innerHTML = '<ul class="mb-0">' +
            errors.map(function(error) {
                return '<li>' + error + '</li>';
            }).join('') + '</ul>';
        errorContainer.style.display = 'block';

        // Scroll to error
        errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function showSuccess(message) {
        successMessage.innerHTML = message;
        successContainer.style.display = 'block';

        // Scroll to success message
        successContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function enableSubmitButton() {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fa fa-save"></i> <?= $is_edit ? 'Update' : 'Simpan' ?>';
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function isValidPhone(phone) {
        return /^[0-9\-\+\(\)\s]+$/.test(phone) && phone.replace(/\D/g, '').length >= 10;
    }

    // Add real-time field validation
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(function(input) {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
});
</script>