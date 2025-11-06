<div class="main-panel">
  <div class="content">
    <div class="page-inner mt--5">

      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header pt-3 pb-2">
              <h4 class="card-title mb-2 mt-3"><?= $title ?? 'Edit SPK'; ?></h4>
            </div>

            <div class="card-body">

              <form action="<?= site_url('spk/update/'.$spk->id_spk) ?>" method="post">
                
                <div class="mb-3">
                  <label class="form-label">Kode SPK</label>
                  <input type="text" class="form-control" value="<?= $spk->kode_spk ?>" disabled>
                  </div>

                <div class="mb-3">
                  <label class="form-label">Nama SPK</label>
                  <input type="text" name="spk" class="form-control" value="<?= $spk->spk ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Tanggal SPK</label>
                  <input type="date" name="tgl_spk" class="form-control" value="<?= $spk->tgl_spk ?>" required>
                </div>

               
                <div class="mb-3">
                  <label class="form-label">Keterangan</label>
                  <textarea name="keterangan" class="form-control" rows="3"><?= $spk->keterangan ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                  <i class="fa fa-save"></i> Update
                </button>
                <a href="<?= site_url('spk') ?>" class="btn btn-secondary">
                  <i class="fa fa-times"></i> Batal
                </a>

              </form>

            </div> </div> </div>
      </div>
      </div>
  </div>
</div>