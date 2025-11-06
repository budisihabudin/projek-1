<div class="px-3">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12">
        <div class="card shadow-sm border-0">
          <div class="card-body">

            <!-- Card Konten Tabel -->
            <div class="card mt-3 border-0 shadow-sm">
              <div class="card-header bg-light border-bottom">
                <h5 class="card-title mb-0"><?= $title ?? 'Tagihan Langganan'; ?></h5>
              </div>
              <div class="card-body">

                <!-- Form Pencarian -->
                <form method="get" action="<?= site_url('berlangganan'); ?>" class="mb-3">
                  <div class="input-group">
                    <input type="text" name="keyword" class="form-control" 
                           placeholder="Cari nama customer, paket, status" 
                           value="<?= htmlspecialchars($this->input->get('keyword'), ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                  </div>
                </form>

                <!-- Tabel Responsif -->
                <div class="table-responsive shadow-sm rounded">
                  <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                      <tr>
                        <th class="text-center">#</th>
                        <th>Nama Customer</th>
                        <th>Paket</th>
                        <th>Kecepatan</th>
                        <th>Tgl Mulai</th>
                        <th>Tgl Berakhir</th>
                        <th>Status Bayar</th>
                        <th class="text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($berlangganan)): ?>
                        <?php $no = ($this->uri->segment(3) && is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) + 1 : 1; ?>
                        <?php foreach($berlangganan as $b): ?>
                          <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td><?= htmlspecialchars($b->nama_customer, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($b->nama_paket, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($b->kecepatan, ENT_QUOTES, 'UTF-8'); ?> Mbps</td>
                            <td><?= date('d/m/Y', strtotime($b->tgl_mulai)); ?></td>
                            <td><?= date('d/m/Y', strtotime($b->tgl_berakhir)); ?></td>
                            <td>
                              <span class="badge bg-<?= $b->status_bayar=='sudah bayar'?'success':'warning'; ?> text-white">
                                <?= ucfirst($b->status_bayar); ?>
                              </span>
                            </td>
                            <td class="text-center">
                              <a href="<?= site_url('berlangganan/toggle_status/'.$b->id_berlangganan); ?>" 
                                 class="btn btn-sm m-1 <?= $b->status_bayar=='sudah bayar'?'btn-success':'btn-warning'; ?>">
                                <?= $b->status_bayar=='sudah bayar'?'Sudah Bayar':'Belum Bayar'; ?>
                              </a>
                              <a href="<?= site_url('berlangganan/bukti/'.$b->id_berlangganan); ?>" 
                                 class="btn btn-sm btn-info m-1 text-white">
                                <i class="fa fa-eye"></i> Lihat Bukti
                              </a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="8" class="text-center text-muted">Data tidak ditemukan.</td>
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
      </div>
    </div>

  </div>
</div>

<style>
body {
  background-color: #f8f9fa;
  font-family: "Poppins", sans-serif;
}

.card {
  border-radius: 15px;
  border: none;
}

.card-title {
  font-weight: 600;
  color: #2c3e50;
}

.table thead th {
  vertical-align: middle;
}

.table td {
  vertical-align: middle;
}

.btn-group .btn {
  min-width: 80px;
}

@media (max-width: 768px) {
  table {
    font-size: 13px;
  }
  .btn-sm {
    padding: 4px 8px;
    font-size: 12px;
  }
  h5.card-title {
    font-size: 16px;
  }
}
</style>
