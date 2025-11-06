<!-- main content -->
<div class="main-panel">
  <div class="content">
    <div class="page-inner mt--5">

      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header pt-3 pb-2">
              <h4 class="card-title mb-2 mt-3"><?= $title ?? 'Tambah Teknisi'; ?></h4>
            </div>

            <div class="card-body">
              <form method="post" action="<?= isset($action) ? $action : ''; ?>">

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" 
                           class="form-control" required 
                           placeholder="Masukkan username teknisi">
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" 
                           class="form-control" required 
                           placeholder="Masukkan password teknisi">
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" name="nama" id="nama" 
                           class="form-control" required 
                           placeholder="Masukkan nama lengkap teknisi">
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" 
                           class="form-control" required 
                           placeholder="Masukkan email teknisi">
                  </div>
                </div>

                <div class="mb-3">
                  <label for="no_hp" class="form-label">No HP</label>
                  <input type="text" name="no_hp" id="no_hp" 
                         class="form-control" required 
                         placeholder="Masukkan nomor HP teknisi">
                </div>

                <div class="mb-3">
                  <label for="status" class="form-label">Status</label>
                  <select name="status" id="status" class="form-control" required>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                  </select>
                </div>

                <div class="d-flex gap-2 mt-3">
                  <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Simpan
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
