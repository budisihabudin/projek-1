<div class="card auth-card">
  <div class="row g-0">
    <!-- Kolom kiri gambar -->
    <div class="col-md-6 auth-image"></div>

    <!-- Kolom kanan form registrasi -->
    <div class="col-md-6 d-flex align-items-center">
      <div class="card-body p-4 w-100">
        <div class="auth-header text-center mb-4">
          <h3>Registrasi Akun</h3>
          <p class="mb-0">Buat akun baru untuk menggunakan layanan ERP ISP</p>
        </div>

        <?php if ($this->session->flashdata('error')): ?>
          <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('auth/register'); ?>">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control form-control-lg" placeholder="Masukkan nama lengkap" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Alamat</label>
            <textarea name="alamat" class="form-control form-control-lg" placeholder="Masukkan alamat lengkap" rows="2" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">No. HP</label>
            <input type="text" name="no_hp" class="form-control form-control-lg" placeholder="Masukkan nomor HP aktif" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" class="form-control form-control-lg" placeholder="Masukkan email aktif" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Username</label>
            <input type="text" name="username" class="form-control form-control-lg" placeholder="Masukkan username" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" name="password" class="form-control form-control-lg" placeholder="Masukkan password" required>
          </div>
          
          <button type="submit" class="btn btn-success w-100 btn-lg mb-2">
            <i class="fa fa-user-plus me-1"></i> Daftar
          </button>

          <a href="<?= base_url('beranda'); ?>" class="btn btn-outline-secondary w-100 btn-lg">
            <i class="fa fa-home me-1"></i> Beranda
          </a>
        </form>

        <div class="text-center mt-4">
          <small>Sudah punya akun? 
            <a href="<?= site_url('auth/login'); ?>" class="fw-semibold text-decoration-none">Login di sini</a>
          </small>
        </div>
      </div>
    </div>
  </div>
</div>
