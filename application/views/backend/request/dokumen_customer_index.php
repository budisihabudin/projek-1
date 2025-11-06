<div class="px-3">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12">
        <div class="card shadow-sm border-0">
          <div class="card-body">

            <div class="card mt-3 border-0 shadow-sm">
              <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><?= $title ?? 'Daftar Dokumen Customer'; ?></h5>
                <a href="<?= site_url('request/tambah_dokumen_customer'); ?>" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Tambah Dokumen Customer
                </a>
              </div>

              <div class="card-body">

                <!-- Form Pencarian -->
                <form method="get" class="mb-3">
                  <div class="input-group">
                    <input type="text" name="keyword" class="form-control"
                           placeholder="Cari nama customer..."
                           value="<?= htmlspecialchars($this->input->get('keyword'), ENT_QUOTES, 'UTF-8'); ?>">
                    <button class="btn btn-primary" type="submit">
                      <i class="fa fa-search"></i> Cari
                    </button>
                  </div>
                </form>

                <!-- Tabel Dokumen Customer -->
                <div class="table-responsive shadow-sm rounded">
                  <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nama Customer</th>
                        <th>Dokumen</th>
                        <th>Keterangan</th>
                        <th>Tanggal Upload</th>
                        <th class="text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(!empty($dokumens)): ?>
                        <?php foreach($dokumens as $i => $d): ?>
                          <tr>
                            <td><?= $i+1; ?></td>
                            <td><?= htmlspecialchars($d->nama_customer); ?></td>
                            <td>
                              <?php if($d->foto_customer): ?>
                                <a href="<?= base_url('uploads/customer/'.$d->foto_customer); ?>" target="_blank">Lihat Dokumen</a>
                              <?php else: ?>
                                -
                              <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($d->keterangan_foto); ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($d->created_at)); ?></td>
                            <td class="text-center">
                                <a href="<?= site_url('request/edit_dokumen_customer/'.$d->id_dokumen_customer); ?>" class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <a href="<?= site_url('request/hapus_dokumen_customer/'.$d->id_dokumen_customer); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus dokumen ini?');">
                                    <i class="fa fa-trash"></i> Hapus
                                </a>
                              

                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr><td colspan="6" class="text-center text-muted">Belum ada dokumen customer.</td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>

                <div class="mt-3"><?= $pagination ?? ''; ?></div>

              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>
</div>
