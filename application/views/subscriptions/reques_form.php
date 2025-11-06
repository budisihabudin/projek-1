<!-- main content -->
<div class="main-panel">
  <div class="content">
    <div class="container my-4">
      <div class="card shadow-sm border-0 p-4">
        <h3 class="text-center mb-4 display-6 fw-bold"><?= $title; ?></h3>

        <?php
        // Tentukan action form sesuai role
        $role = $this->session->userdata('role');
        if ($role == 'admin' || $role == 'sales') {
            $action = 'aksi/tambah_request';
        } elseif ($role == 'customer') {
            $action = 'subscriptions/tambah_request';
        } else {
            $action = '#';
        }
        ?>

        <form method="post" action="<?= base_url($action); ?>">

          <!-- Bagian Identitas Pelanggan -->
          <h5 class="fw-bold mb-3 text-primary">🧾 Identitas Pelanggan</h5>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">PIC</label>
                <input type="text" name="pic" class="form-control" placeholder="Masukkan nama PIC" required>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Instansi</label>
                <input type="text" name="instansi" class="form-control" placeholder="Masukkan nama instansi" required>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan" class="form-control"
                       value="<?= isset($request) ? htmlspecialchars($request->nama_pelanggan, ENT_QUOTES, 'UTF-8') : ''; ?>"
                       placeholder="Masukkan nama pelanggan" required>
              </div>
            </div>

            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">No. HP</label>
                <input type="number" name="no_hp" class="form-control" placeholder="Masukkan nomor HP aktif" required>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Masukkan alamat email" required>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Alamat</label>
                <input type="text" name="alamat" class="form-control"
                       value="<?= isset($request) ? htmlspecialchars($request->alamat, ENT_QUOTES, 'UTF-8') : ''; ?>"
                       placeholder="Masukkan alamat pelanggan" required>
              </div>
            </div>
          </div>

          <hr class="my-4">

          <!-- Bagian Layanan / Paket -->
          <h5 class="fw-bold mb-3 text-primary">📦 Layanan / Paket</h5>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Paket Layanan</label>
                <select name="id_paket" id="select-paket" 
                        class="form-control selectpicker" 
                        data-live-search="true" 
                        title="-- Pilih Paket --" required>
                  <?php foreach($paket as $p): ?>
                    <option value="<?= $p->id_paket ?>">
                      <?= $p->nama_paket ?> - <?= $p->kecepatan ?> Mbps
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Mulai</label>
                <input type="date" name="tgl_mulai" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Lama Berlangganan (bulan)</label>
                <input type="number" name="lama_langganan" class="form-control" min="1" required placeholder="Masukkan lama berlangganan">
              </div>

              <?php if ($role == "admin" || $role == "sales"): ?>
                <hr class="my-3">
                <h6 class="fw-semibold text-secondary">🛠 Data User</h6>

                <div class="mb-3">
                  <label class="form-label">Username</label>
                  <input type="text" name="username" class="form-control" required
                         value="<?= set_value('username', $user->username ?? ''); ?>"
                         placeholder="Masukkan username">
                </div>

                <div class="mb-3">
                  <label class="form-label">Password <?= isset($user) ? '(isi jika ingin ganti)' : ''; ?></label>
                  <input type="password" name="password" class="form-control"
                         placeholder="Masukkan password" <?= isset($user) ? '' : 'required'; ?>>
                </div>

                 
              <?php endif ?>
            </div>
          </div>

          <div class="text-end mt-4">
            <button type="submit" class="btn btn-success btn-lg me-2">
              <i class="fas fa-save me-1"></i> Simpan
            </button>
            <a href="<?= base_url('subscriptions/request'); ?>" class="btn btn-secondary btn-lg">
              <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<!-- CSS tambahan untuk placeholder & font -->
<style>
  .form-control::placeholder {
    font-size: 0.9rem;
    opacity: 0.75;
  }
  .form-label {
    font-size: 0.95rem;
  }
</style>

<!-- Script inisialisasi Bootstrap Select -->
<script>
  $(document).ready(function() {
    $('.selectpicker').selectpicker();
  });
</script>
