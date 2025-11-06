<!-- main content -->
<div class="main-panel">
  <div class="content">
    <div class="page-inner mt--5">

      <!-- Card: Data Teknisi -->
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header pt-3 pb-2">
              <h4 class="card-title mb-2 mt-3"><?= $title ?? 'Data Teknisi'; ?></h4>
            </div>

            <div class="card-body">

              <!-- Form Pencarian -->
              <form method="get" action="<?= site_url('teknisi'); ?>" class="mb-3">
                <div class="input-group">
                  <input type="text" name="keyword" value="<?= $keyword ?? '' ?>" 
                         class="form-control" placeholder="Cari teknisi...">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
              </form>

              <!-- Tombol Tambah Teknisi -->
              <div class="mb-3">
                <a href="<?= site_url('teknisi/create'); ?>" class="btn btn-success">
                  <i class="fa fa-plus"></i> Tambah Teknisi
                </a>
              </div>

              <!-- Tabel Responsif -->
              <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                  <thead class="table-dark">
                    <tr>
                      <th class="text-center">No</th>
                      <th>Username</th>
                      <th>Nama</th>
                      <th>Email</th>
                      <th>No HP</th>
                      <th>Status</th>
                      <th class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($teknisi)): ?>
                      <?php $no = ($this->uri->segment(3) && is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) + 1 : 1; ?>
                      <?php foreach($teknisi as $t): ?>
                        <tr>
                          <td class="text-center"><?= $no++; ?></td>
                          <td><?= htmlspecialchars($t->username, ENT_QUOTES, 'UTF-8'); ?></td>
                          <td><?= htmlspecialchars($t->nama, ENT_QUOTES, 'UTF-8'); ?></td>
                          <td><?= htmlspecialchars($t->email, ENT_QUOTES, 'UTF-8'); ?></td>
                          <td><?= htmlspecialchars($t->no_hp, ENT_QUOTES, 'UTF-8'); ?></td>
                          <td>
                            <span class="badge bg-<?= $t->status == 'aktif' ? 'success' : 'danger'; ?> text-white">
                              <?= ucfirst($t->status); ?>
                            </span>
                          </td>
                          <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                              <a href="<?= site_url('teknisi/edit/'.$t->id_teknisi); ?>" 
                                 class="btn btn-warning btn-sm mb-1">
                                <i class="fas fa-pencil-alt"></i>
                              </a>
                              <a href="<?= site_url('teknisi/delete/'.$t->id_teknisi); ?>" 
                                 class="btn btn-danger btn-sm mb-1"
                                 onclick="return confirm('Yakin hapus teknisi ini?');">
                                <i class="fa fa-trash"></i>
                              </a>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="7" class="text-center">Tidak ada data teknisi</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              <div class="mt-3">
                <?= $pagination ?? ''; ?>
              </div>

            </div>
          </div>
        </div>
      </div>
      <!-- end card -->

    </div>
  </div>
</div>
<!-- end main content -->
