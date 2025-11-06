<div class="px-3">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h5><?= $title ?? 'Upload Dokumen Survei'; ?></h5>
            <a href="<?= site_url('request/survei_index'); ?>" class="btn btn-secondary btn-sm mb-3">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>

            <!-- Form Upload Multi -->
            <form action="<?= site_url('request/tambah_dokumen_multi'); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_survei" value="<?= $id_survei; ?>">
                <div class="mb-3">
                    <label>Dokumen Survei (Bisa pilih lebih dari 1)</label>
                    <input type="file" name="foto_survei[]" multiple class="form-control">
                </div>
                <div class="mb-3">
                    <label>Keterangan untuk masing-masing file (pisahkan dengan | jika multiple)</label>
                    <input type="text" name="keterangan_foto" class="form-control" placeholder="Keterangan file1|Keterangan file2|...">
                </div>
                <button type="submit" class="btn btn-primary">Upload Dokumen</button>
            </form>

            <hr>

            <!-- Tabel Dokumen -->
            <h6 class="mt-3">Daftar Dokumen</h6>
            <div class="table-responsive">
              <table class="table table-bordered table-striped">
                <thead class="table-dark">
                  <tr>
                    <th>File</th>
                    <th>Keterangan</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($dokumens)): ?>
                    <?php foreach($dokumens as $d): ?>
                      <tr>
                        <td>
                            <?php if(pathinfo($d->foto_survei, PATHINFO_EXTENSION) == 'pdf'): ?>
                                <a href="<?= base_url('uploads/survei/'.$d->foto_survei); ?>" target="_blank">Lihat PDF</a>
                            <?php else: ?>
                                <img src="<?= base_url('uploads/survei/'.$d->foto_survei); ?>" width="80">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($d->keterangan_foto, ENT_QUOTES); ?></td>
                        <td class="text-center">
                            <a href="<?= site_url('request/edit_dokumen/'.$d->id_dokumen_survei); ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?= site_url('request/hapus_dokumen/'.$d->id_dokumen_survei); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus dokumen ini?');">Hapus</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr><td colspan="3" class="text-center">Belum ada dokumen.</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
