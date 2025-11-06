<div class="main-panel">
  <div class="content">
    <div class="container my-4">
      <div class="card shadow-sm p-4">
        <h3 class="text-center mb-4"><?= $title ?></h3>

        <form method="post" action="<?= base_url('survei/update/'.$survei->id_histori) ?>" enctype="multipart/form-data">

          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Customer</label>
            <input type="text" class="form-control" value="<?= $survei->nama_customer ?>" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Paket</label>
            <input type="text" class="form-control" value="<?= $survei->nama_paket ?>" disabled>
          </div>

          <?php if ($this->session->userdata('nama_jabatan') == "Manager Surveyor"): ?>
            <div class="mb-3">
              <label class="form-label fw-semibold">Teknisi</label>
              <select name="id_employee" class="form-control" required>
                <option value="">-- Pilih Teknisi --</option>
                <?php foreach($employees as $e): ?>
                  <option value="<?= $e->id_employee ?>" <?= $survei->id_employee == $e->id_employee ? 'selected' : '' ?>>
                    <?= $e->nama_lengkap ?>
                  </option>
                <?php endforeach; ?>
              </select>

            </div>            
          <?php endif ?>


           <div class="mb-3">
            <label class="form-label fw-semibold">Foto Survei</label><br>
            <?php if (!empty($survei->foto_survei)): ?>
              <img src="<?= base_url('uploads/survei/'.$survei->foto_survei) ?>" alt="Foto Survei" class="img-thumbnail mb-2" style="max-width:150px;">
            <?php endif; ?>
            <input type="file" name="foto_survei" class="form-control">
            <small class="text-muted">Biarkan kosong jika tidak ingin mengganti foto.</small>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Aktivitas</label>
            <select name="aktivitas" class="form-control" required>
              <?php 
                $options = ['belum survei','sudah survei'];
                foreach($options as $opt): ?>
                  <option value="<?= $opt ?>" <?= $survei->aktivitas == $opt ? 'selected' : '' ?>>
                    <?= $opt ?>
                  </option>
              <?php endforeach; ?>
            </select>
          </div>
          <br>
          <br>
          <br>

          <div class="text-end mt-4">
            <button type="submit" class="btn btn-success">
              <i class="fas fa-save me-1"></i> Simpan
            </button>
            <a href="<?= base_url('survei') ?>" class="btn btn-secondary">
              <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
