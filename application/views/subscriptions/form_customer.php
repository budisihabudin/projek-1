<!-- main content -->
<div class="main-panel">
  <div class="content">
    <div class="page-inner mt--4">

      <!-- Card Form Langganan -->
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header pt-3 pb-2">
              <h4 class="card-title mb-2 mt-3"><?= $title ?? 'Form Langganan'; ?></h4>
            </div>

            <div class="card-body">
              <form method="post" action="<?= site_url('subscriptions/simpan_customer') ?>">

                <input type="hidden" name="id_paket" value="<?= $paket->id_paket ?>">

                <div class="row g-4">
                  <!-- Form Input -->
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label">Kode Customer</label>
                      <input type="text" class="form-control" name="kode_customer" value="<?= $kode_customer ?>" readonly>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Nama</label>
                      <input type="text" class="form-control" name="nama" required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Alamat</label>
                      <textarea class="form-control" name="alamat" rows="3" required></textarea>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="form-label">No HP</label>
                        <input type="text" class="form-control" name="no_hp" required>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="tgl_mulai" required>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label class="form-label">Lama Langganan (bulan)</label>
                        <input type="number" class="form-control" name="lama_langganan" min="1" required>
                      </div>
                    </div>

                    <!-- Tombol Kirim & Kembali -->
                    <div class="d-flex gap-2 mt-2">
                      <a href="<?= site_url('subscriptions'); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
                      <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane me-1"></i> Kirim Pendaftaran</button>
                    </div>
                  </div>

                  <!-- Card Paket -->
                  <div class="col-md-6">
                    <div class="card shadow-sm h-100 text-center p-3" style="background: linear-gradient(135deg, #e0f7fa, #80deea);">
                      <!-- <img src="<?= base_url('uploads/paket/'.$paket->gambar) ?>" class="card-img-top mb-3" alt="Gambar Paket"> -->
                      <h5 class="card-title mb-2"><?= $paket->nama_paket ?></h5>
                      <p class="mb-1"><strong><?= $paket->kecepatan ?> Mbps</strong></p>
                      <p class="card-text mb-2"><?= $paket->deskripsi ?></p>
                      <p class="text-muted mb-0">Rp <?= number_format($paket->harga,0,',','.') ?>/bulan</p>
                    </div>
                  </div>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- end card -->

    </div>
  </div>
</div>
<!-- end main content -->

<style>
@media (max-width: 768px) {
    .card-body {
        text-align: center;
    }
}
</style>
