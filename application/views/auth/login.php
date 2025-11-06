<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo_transparan.png') ?>">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #74ebd5, #ACB6E5);
    }
    .login-card {
      width: 100%;
      max-width: 420px;
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      padding: 30px;
    }
    .login-card h3 {
      font-weight: bold;
      color: #343a40;
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
    .btn-custom {
      border-radius: 10px;
      font-weight: 500;
      transition: 0.3s;
    }
    .btn-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    /* Responsif untuk logo */
    .login-card img.logo-title {
      height: 40px;
    }
    @media (max-width: 576px) {
      .login-card img.logo-title {
        height: 30px;
      }
    }
  </style>
</head>
<body>

<div class="login-card">
  <h3 class="text-center mb-4 d-flex align-items-center justify-content-center">
   <!--  <img src="<?//= base_url('assets/img/logo_transparan.png') ?>" 
         alt="Logo MLM" 
         class="me-2 logo-title"> -->
    Login
  </h3>

  <!-- Flashdata -->
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
  <?php endif; ?>
  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
  <?php endif; ?>

  <form action="<?= base_url('auth/login'); ?>" method="post">
    <!-- Nomor HP -->
    <div class="mb-3">
      <label for="phone" class="form-label">Nomor Telepon</label>
      <input type="tel" class="form-control" id="phone" name="phone" 
             placeholder="Masukkan nomor HP" pattern="[0-9]{10,15}" required>
      <div class="form-text">Gunakan format angka 10-15 digit.</div>
    </div>

    <!-- Password -->
    <div class="mb-3 password-wrapper">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password"
             placeholder="Masukkan password" required>
      <span toggle="#password" class="fa fa-eye toggle-password"></span>
    </div>

    <!-- Tombol -->
    <div class="d-grid gap-2">
      <button type="submit" class="btn btn-primary btn-custom">Login</button>
      <a href="<?= base_url('auth/register'); ?>" class="btn btn-success btn-custom">Registrasi</a>
      <a href="<?= base_url('home'); ?>" class="btn btn-secondary btn-custom">Ke Beranda</a>
      <a href="<?= base_url('auth/forgot_password'); ?>" class="text-primary">Lupa Password?</a>
    </div>
  </form>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const togglePassword = document.querySelector(".toggle-password");
    const passwordInput = document.querySelector("#password");

    togglePassword.addEventListener("click", function () {
      const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);

      this.classList.toggle("fa-eye");
      this.classList.toggle("fa-eye-slash");
    });
  });
</script>

</body>
</html>
