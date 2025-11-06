<div class="card auth-card">
  <div class="row g-0">
    <!-- Kolom kiri untuk gambar -->
    <div class="col-md-6 auth-image"></div>

    <!-- Kolom kanan untuk form login -->
    <div class="col-md-6 d-flex align-items-center">
      <div class="card-body p-4 w-100">
        <div class="auth-header text-center mb-4">
          <h3>Login ERP ISP</h3>
          <p class="mb-0">Silakan masuk untuk melanjutkan</p>
        </div>

        <form method="post" action="<?= site_url('auth/login'); ?>">
          <div class="mb-3">
            <label class="form-label fw-semibold">Username</label>
            <input type="text" name="username" class="form-control form-control-lg" placeholder="Masukan username" required autofocus>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" name="password" class="form-control form-control-lg" placeholder="Masukan password" required>
          </div>

          <button type="submit" class="btn btn-primary w-100 btn-lg mb-2">
            <i class="fa fa-sign-in-alt me-1"></i> Login
          </button>

          <a href="<?= base_url('beranda'); ?>" class="btn btn-success w-100 btn-lg text-white">
            <i class="fa fa-home me-1"></i> Beranda
          </a>
        </form>

        <div class="text-center mt-4">
          <small>Belum punya akun? 
            <a href="<?= site_url('auth/register'); ?>" class="fw-semibold text-decoration-none text-primary">
              Daftar sekarang
            </a>
          </small>
        </div>
      </div>
    </div>
  </div>
</div>
