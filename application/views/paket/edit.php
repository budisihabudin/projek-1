<!-- main content -->
<div class="main-panel">
  <div class="content">
    <div class="page-inner mt--5">

      <!-- Card: Form Edit Paket -->
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header pt-3 pb-2">
              <h4 class="card-title mb-2 mt-3"><?= $title ?? 'Edit Paket'; ?></h4>
            </div>

            <div class="card-body">

              <form method="post" action="">

                <div class="mb-3">
                  <label class="form-label">Nama Paket</label>
                  <input type="text" name="nama_paket" class="form-control" 
                         value="<?= htmlspecialchars(@$paket->nama_paket, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Deskripsi</label>
                  <textarea name="deskripsi" class="form-control" required><?= htmlspecialchars(@$paket->deskripsi, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="harga" class="form-control" 
                           value="<?= htmlspecialchars(@$paket->harga, ENT_QUOTES, 'UTF-8'); ?>" required>
                  </div>

                  <div class="col-md-4 mb-3">
                    <label class="form-label">Kecepatan (Mbps)</label>
                    <input type="number" name="kecepatan" class="form-control" 
                           value="<?= htmlspecialchars(@$paket->kecepatan, ENT_QUOTES, 'UTF-8'); ?>" required>
                  </div>

                  <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                      <option value="aktif" <?= (@$paket->status=='aktif')?'selected':'' ?>>Aktif</option>
                      <option value="nonaktif" <?= (@$paket->status=='nonaktif')?'selected':'' ?>>Nonaktif</option>
                    </select>
                  </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                  <button type="submit" class="btn btn-success">Simpan</button>
                  <a href="<?= site_url('paket') ?>" class="btn btn-secondary">Kembali</a>
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
