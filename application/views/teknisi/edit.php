<!-- main content -->
<div class="main-panel">
  <div class="content">
    <div class="page-inner mt--5">

      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header pt-3 pb-2">
              <h4 class="card-title mb-2 mt-3"><?= $title ?? 'Edit Teknisi'; ?></h4>
            </div>

            <div class="card-body">
              <form method="post" action="<?= isset($action) ? $action : ''; ?>">

                <div class="mb-3">
                  <label for="nama" class="form-label">Nama</label>
                  <input type="text" name="nama" id="nama" 
                         class="form-control" 
                         value="<?= htmlspecialchars($teknisi->nama, ENT_QUOTES, 'UTF-8'); ?>" 
                         required placeholder="Masukkan nama lengkap teknisi">
                </div>

                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" name="email" id="email" 
                         class="form-control" 
                         value="<?= htmlspecialchars($teknisi->email, ENT_QUOTES, 'UTF-8'); ?>" 
                         required placeholder="Masukkan email teknisi">
                </div>

                <div class="mb-3">
                  <label for="no_hp" class="form-label">No HP</label>
                  <input type="text" name="no_hp" id="no_hp" 
                         class="form-control" 
                         value="<?= htmlspecialchars($teknisi->no_hp, ENT_QUOTES, 'UTF-8'); ?>" 
                         required placeholder="Masukkan nomor HP teknisi">
                </div>

                <div class="mb-3">
                  <label for="status" class="form-label">Status</label>
                  <select name="status" id="status" class="form-control" required>
                    <option value="aktif" <?= $teknisi->status == 'aktif' ? 'selected' : ''; ?>>Aktif</option>
                    <option value="nonaktif" <?= $teknisi->status == 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                  </select>
                </div>

                <div class="d-flex gap-2 mt-3">
                  <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Update
                  </button>
                  <a href="<?= site_url('teknisi'); ?>" class="btn btn-secondary">Kembali</a>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
<!-- end main content -->
