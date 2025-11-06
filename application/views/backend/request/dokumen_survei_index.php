<div class="px-3">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12">
        <div class="card shadow-sm border-0">
          <div class="card-body">

            <div class="card mt-3 border-0 shadow-sm">
              <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><?= $title ?? 'Dokumen Survei'; ?></h5>
              </div>

              <div class="card-body">

                <!-- Form Pencarian -->
                <form method="get" class="mb-3">
                  <div class="input-group w-100">
                    <input type="text" name="keyword" class="form-control"
                           placeholder="Cari nama customer, paket, atau keterangan dokumen..."
                           value="<?= htmlspecialchars($keyword ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <button class="btn btn-primary" type="submit">
                      <i class="fa fa-search"></i> Cari
                    </button>
                  </div>
                </form>

                <!-- Tabel Dokumen -->
                <div class="table-responsive shadow-sm rounded">
                  <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nama Customer</th>
                        <th>Paket</th>
                        <th>Dokumen</th>
                        <th>Keterangan</th>
                        <th>Tanggal Upload</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(!empty($dokumens)): ?>
                        <?php 
                          $start_no = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
                          foreach($dokumens as $i => $d): 
                        ?>
                          <tr>
                            <td><?= $start_no + $i + 1; ?></td>
                            <td><?= htmlspecialchars($d->nama_customer ?? '-'); ?></td>
                            <td><?= htmlspecialchars($d->nama_paket ?? '-'); ?></td>
                            <td>
                              <?php if($d->foto_survei): ?>
                                <a href="<?= base_url('uploads/survei/'.$d->foto_survei); ?>" target="_blank">
                                  Lihat Dokumen
                                </a>
                              <?php else: ?>
                                -
                              <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($d->keterangan_foto ?? '-'); ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($d->created_at)); ?></td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="6" class="text-center text-muted">Belum ada dokumen survei.</td>
                        </tr>
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
