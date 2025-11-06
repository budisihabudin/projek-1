<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>
<link rel="icon" type="image/png" href="<?= base_url('assets/img/logo_transparan.png') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    body {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .card {
        width: 100%;
        max-width: 400px;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        padding: 30px;
        background-color: #fff;
    }
    .card h4 {
        font-weight: bold;
        color: #343a40;
    }
    .btn-custom {
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.2);
    }
    .password-wrapper {
        position: relative;
    }
    .password-wrapper .toggle-password {
        position: absolute;
        top: 50%;
        right: 12px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d;
    }
    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        border-color: #007bff;
    }
    @media (max-width: 480px) {
        .card {
            padding: 20px;
            border-radius: 15px;
        }
    }
</style>
</head>
<body>
<div class="card">
    <h4 class="text-center mb-4">Reset Password Baru</h4>

    <!-- Flashdata -->
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('auth/reset_password'); ?>">
        <div class="mb-3 password-wrapper">
            <label class="form-label">Password Baru</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password baru" required autocomplete="new-password">
            <span toggle="#password" class="fa fa-eye toggle-password"></span>
        </div>
        <button type="submit" class="btn btn-primary w-100 btn-custom">Simpan Password</button>
        <a href="<?= base_url('auth/login'); ?>" class="btn btn-secondary w-100 btn-custom mt-2">Kembali Login</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const togglePassword = document.querySelector(".toggle-password");
    const passwordInput = document.querySelector("input[name='password']");

    togglePassword.addEventListener("click", function() {
        const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);
        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });
});
</script>
</body>
</html>
