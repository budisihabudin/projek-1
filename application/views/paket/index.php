<!-- main content -->
<div class="main-panel">
  <div class="content">
    <div class="page-inner mt--5">

      <!-- Card: Data Paket -->
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header pt-3 pb-2">
              <h4 class="card-title mb-2 mt-3"><?= $title ?? 'Data Paket'; ?></h4>
            </div>

            <div class="card-body">

              <!-- Form Pencarian -->
              <form method="get" action="<?= site_url('paket'); ?>" class="mb-3">
                <div class="input-group">
                  <input type="text" name="q" value="<?= $keyword ?? '' ?>" 
                         class="form-control" placeholder="Cari paket...">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
              </form>

              <!-- Tombol Tambah Paket -->
              <div class="mb-3">
                <a href="<?= site_url('paket/create'); ?>" class="btn btn-success">
                  <i class="fa fa-plus"></i> Tambah Paket
                </a>
              </div>

              <!-- Tabel Responsif -->
              <div class="table-responsive shadow-sm rounded">
                <table class="table table-bordered table-striped align-middle mb-0">
                  <thead class="table-dark">
                    <tr>
                      <th class="text-center">No</th>
                      <th>Nama Paket</th>
                      <th>Deskripsi</th>
                      <th>Harga</th>
                      <th>Kecepatan (Mbps)</th>
                      <th>Status</th>
                      <th class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($paket)): ?>
                      <?php $no = ($this->uri->segment(3) && is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) + 1 : 1; ?>
                      <?php foreach($paket as $p): ?>
                        <tr>
                          <td class="text-center"><?= $no++; ?></td>
                          <td><?= htmlspecialchars($p->nama_paket, ENT_QUOTES, 'UTF-8'); ?></td>
                          <td><?= htmlspecialchars($p->deskripsi, ENT_QUOTES, 'UTF-8'); ?></td>
                          <td>Rp <?= number_format($p->harga,0,',','.'); ?></td>
                          <td><?= $p->kecepatan ?> Mbps</td>
                          <td>
                            <span class="badge bg-<?= $p->status == 'aktif' ? 'success' : 'secondary'; ?> text-white">
                              <?= ucfirst($p->status); ?>
                            </span>
                          </td>
                          <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                              <a href="<?= site_url('paket/edit/'.$p->id_paket); ?>" class="btn btn-warning btn-sm mb-1">
                                <i class="fas fa-pencil-alt"></i>
                              </a>
                              <a href="<?= site_url('paket/delete/'.$p->id_paket); ?>" class="btn btn-danger btn-sm mb-1"
                                 onclick="return confirm('Yakin hapus paket ini?')">
                                <i class="fa fa-trash"></i>
                              </a>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="7" class="text-center">Data tidak ditemukan</td>
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
