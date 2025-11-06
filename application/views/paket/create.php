<!-- main content -->
<div class="main-panel">
  <div class="content">
    <div class="page-inner mt--5">

      <!-- Card: Form Tambah Paket -->
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header pt-3 pb-2">
              <h4 class="card-title mb-2 mt-3"><?= $title ?? 'Tambah Paket'; ?></h4>
            </div>

            <div class="card-body">

              <form method="post" action="<?= site_url('paket/create'); ?>">

                <div class="mb-3">
                  <label class="form-label">Nama Paket</label>
                  <input type="text" name="nama_paket" class="form-control" placeholder="Masukkan nama paket" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Deskripsi</label>
                  <textarea name="deskripsi" class="form-control" placeholder="Masukkan deskripsi paket" required></textarea>
                </div>

                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="harga" class="form-control" placeholder="Harga paket" required>
                  </div>

                  <div class="col-md-4 mb-3">
                    <label class="form-label">Kecepatan (Mbps)</label>
                    <input type="number" name="kecepatan" class="form-control" placeholder="Kecepatan paket" required>
                  </div>

                  <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                      <option value="aktif">Aktif</option>
                      <option value="nonaktif">Nonaktif</option>
                    </select>
                  </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                  <button type="submit" class="btn btn-success">Simpan</button>
                  <a href="<?= site_url('paket'); ?>" class="btn btn-secondary">Kembali</a>
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
