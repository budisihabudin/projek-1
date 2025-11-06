<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Akun</title>
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo_transparan.png') ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .register-card {
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0px 8px 20px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 420px;
    }
    .form-control {
      border-radius: 10px;
    }
    .btn-primary {
      border-radius: 10px;
    }
    .toggle-password {
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="register-card">
    <h3 class="text-center mb-3">Registrasi Akun</h3>
    
    <?php if ($this->session->flashdata('error')): ?>
      <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('success')): ?>
      <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>

    <form action="<?= base_url('auth/register'); ?>" method="post">
      <!-- Nama -->
      <div class="mb-3">
        <label for="nama" class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control" id="nama" name="nama" required>
      </div>

      <!-- Email -->
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>

      <!-- Nomor HP -->
      <div class="mb-3">
        <label for="no_hp" class="form-label">Nomor HP</label>
        <input type="tel" class="form-control" id="no_hp" name="no_hp"
               pattern="[0-9]{10,15}" required>
        <div class="form-text">Nomor HP harus 10–15 digit angka.</div>
      </div>

      <!-- Alamat -->
      <div class="mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
      </div>

      <!-- Password -->
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="input-group">
          <input type="password" class="form-control" id="password" name="password" required>
          <span class="input-group-text toggle-password" onclick="togglePassword('password', this)">
            <i class="fa fa-eye"></i>
          </span>
        </div>
      </div>

      <!-- Konfirmasi Password -->
      <div class="mb-3">
        <label for="confirm_password" class="form-label">Konfirmasi Password</label>
        <div class="input-group">
          <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
          <span class="input-group-text toggle-password" onclick="togglePassword('confirm_password', this)">
            <i class="fa fa-eye"></i>
          </span>
        </div>
      </div>

      <!-- Username -->
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required
               pattern="[a-zA-Z0-9_]{3,20}"
               title="Username 3-20 karakter, hanya huruf, angka, dan underscore">
        <div class="form-text">Username 3-20 karakter, hanya huruf, angka, dan underscore.</div>
      </div>

      <!-- Kode Referal -->
      <div class="mb-3">
        <label for="kode_member" class="form-label">Kode Referal / Reseller (Opsional)</label>
        <input type="text"
               class="form-control"
               id="kode_member"
               name="kode_member"
               value="<?= isset($_GET['ref']) ? htmlspecialchars($_GET['ref']) : '' ?>"
               placeholder="Masukkan kode reseller jika ada">
        <div class="form-text">Kosongkan jika tidak ada kode reseller.</div>
      </div>

      <!-- Tombol Daftar -->
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Daftar</button>
      </div>
    </form>

    <p class="text-center mt-3">
      Sudah punya akun? <a href="<?= base_url('auth/login'); ?>">Login di sini</a>
    </p>
  </div>

  <script>
    function togglePassword(fieldId, el) {
      const field = document.getElementById(fieldId);
      const icon = el.querySelector("i");
      if (field.type === "password") {
        field.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      } else {
        field.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      }
    }

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
      const username = document.getElementById('username').value;
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirm_password').value;
      const noHp = document.getElementById('no_hp').value;

      // Validasi username
      if (!/^[a-zA-Z0-9_]{3,20}$/.test(username)) {
        e.preventDefault();
        alert('Username 3-20 karakter, hanya huruf, angka, dan underscore!');
        return false;
      }

      // Validasi password match
      if (password !== confirmPassword) {
        e.preventDefault();
        alert('Password dan konfirmasi password harus sama!');
        return false;
      }

      // Validasi panjang password
      if (password.length < 6) {
        e.preventDefault();
        alert('Password minimal 6 karakter!');
        return false;
      }

      // Validasi nomor HP
      if (!/^[0-9]{10,15}$/.test(noHp)) {
        e.preventDefault();
        alert('Nomor HP harus 10-15 digit angka!');
        return false;
      }
    });
  </script>
</body>
</html>
