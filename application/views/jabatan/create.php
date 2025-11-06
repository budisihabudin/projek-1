<div class="main-panel">
  <div class="content">
    <div class="page-inner mt--5">
      <div class="row">
        <div class="col-6 mx-auto">
          <div class="card shadow-sm">
            <div class="card-header">
              <h4 class="card-title mt-3"><?= $title ?? 'Tambah Jabatan'; ?></h4>
            </div>
            <div class="card-body">
              <?= validation_errors('<div class="alert alert-danger">','</div>'); ?>
              <form method="post" action="<?= $action; ?>">
                <div class="mb-3">
                  <label class="form-label">Nama Jabatan</label>
                  <input type="text" name="nama_jabatan" class="form-control" placeholder="Masukan nama jabatan" required value="<?= $jabatan->nama_jabatan ?? set_value('nama_jabatan'); ?>">
                </div>
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                <a href="<?= site_url('jabatan'); ?>" class="btn btn-secondary"><i class="fa fa-times"></i> Batal</a>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
