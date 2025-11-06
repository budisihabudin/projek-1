<div class="px-3">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12">
        <div class="card shadow-sm border-0">
          <div class="card-body">

            <div class="card mt-3 border-0 shadow-sm">
              <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><?= $title ?? 'Data Request Survei'; ?></h5>
                <?php if ($this->session->userdata('role') =="sales"): ?>
                  <a href="<?= site_url('request/add_survei'); ?>" class="btn btn-primary btn-sm">
                  <i class="fa fa-plus"></i> Tambah Survei
                </a>
                <?php endif ?>
              </div>

              <div class="card-body">

                <!-- Form Pencarian -->
                <form method="get" class="mb-3">
                  <div class="input-group">
                    <input type="text" name="keyword" class="form-control"
                           placeholder="Cari nama customer / paket..."
                           value="<?= htmlspecialchars($this->input->get('keyword'), ENT_QUOTES, 'UTF-8'); ?>">
                    <button class="btn btn-primary" type="submit">
                      <i class="fa fa-search"></i> Cari
                    </button>
                  </div>
                </form>

                <!-- Tabel -->
                <div class="table-responsive shadow-sm rounded">
                  <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                      <tr>
                        <th>Nama Customer</th>
                        <th>Paket</th>
                        <th>Lama Berlangganan</th>
                        <th>IP Customer</th>
                        <th class="text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(!empty($requests_instalasi)): ?>
                        <?php foreach($requests_instalasi as $s): ?>
                          <tr>
                            <td><?= htmlspecialchars($s->nama_customer, ENT_QUOTES); ?></td>
                            <td><?= htmlspecialchars($s->nama_paket, ENT_QUOTES); ?></td>
                            <td><?= htmlspecialchars($s->lama_bulan ?? '-', ENT_QUOTES)." Bulan "; ?></td>
                            <td>
                              <?= $s->ip_customer ? '<span class="badge bg-success">'.htmlspecialchars($s->ip_customer, ENT_QUOTES).'</span>' : '<span class="badge bg-warning">Belum Diset</span>'; ?>
                            </td>
                            <td class="text-center">
                             <a href="<?php echo base_url('request/edit_instalasi/'.$s->id_request); ?>" class="btn btn-warning btn-sm m-1"><i class="fa fa-edit"></i> Edit</a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted">Belum ada data instalasi.</td></tr>
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
 