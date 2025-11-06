<div class="px-3">
  <div class="container-fluid">
    
  

    <!-- Card Analisis Pendapatan -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card shadow-sm border-0">
          <div class="card-body">
 
            <!-- Card Konten Tabel -->
            <div class="card mt-3 border-0 shadow-sm">
              <div class="card-header bg-light border-bottom">
                <h5 class="card-title mb-0"><?= $title ?? 'Approval Langganan'; ?></h5>
              </div>
              <div class="card-body">

                <!-- Form Pencarian -->
                <form method="get" class="mb-3">
                  <div class="input-group">
                    <input type="text" name="keyword" class="form-control" 
                           placeholder="Cari nama / paket..." 
                           value="<?= htmlspecialchars($this->input->get('keyword'), ENT_QUOTES, 'UTF-8'); ?>">
                    <button class="btn btn-primary" type="submit">
                      <i class="fa fa-search"></i> Cari
                    </button>
                  </div>
                </form>

                <!-- Tabel Responsif -->
                <div class="table-responsive shadow-sm rounded">
                  <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                      <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Paket</th>
                        <th>Kecepatan</th>
                        <th>Langganan</th>
                        <th>Lama (bulan)</th>
                        <th>Status</th>
                        <th>Survei</th>
                        <th>Instalasi</th>
                        <th class="text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($requests)): ?>
                        <?php $no = ($this->uri->segment(3) && is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) + 1 : 1; ?>
                        <?php foreach ($requests as $r): ?>
                          <tr>
                            <td><?= htmlspecialchars($r->kode_customer ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($r->nama_customer ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($r->nama_paket ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= $r->kecepatan ? htmlspecialchars($r->kecepatan, ENT_QUOTES, 'UTF-8').' Mbps' : '-'; ?></td>
                            <td><?= date('d-m-Y', strtotime($r->tgl_langganan)); ?></td>
                            <td><?= htmlspecialchars($r->lama_langganan ?? '-', ENT_QUOTES, 'UTF-8'); ?> bulan</td>
                            <td><span class="badge bg-warning text-white"><?= ucfirst($r->status); ?></span></td>
                            
                            <!-- Kolom Survei -->
                            <td>
                              <?php if ($r->aktivitas == 'sudah survei'): ?>
                                <span class="badge bg-primary text-white"><?= ucfirst($r->aktivitas); ?></span>
                              <?php elseif ($r->aktivitas == 'belum survei'): ?>
                                <span class="badge bg-danger text-white"><?= ucfirst($r->aktivitas); ?></span>
                              <?php elseif ($r->aktivitas == 'approval' || $r->aktivitas == 'online'): ?>
                                <span class="badge bg-success text-white"><?= ucfirst($r->aktivitas); ?></span>
                              <?php endif; ?>
                            </td>

                            <!-- Kolom Instalasi -->
                            <td>
                              <?php if ($r->instalasi == 'pending'): ?>
                                <span class="badge bg-warning text-white"><?= ucfirst($r->instalasi); ?></span>
                              <?php elseif ($r->instalasi == 'maintanance'): ?>
                                <span class="badge bg-danger text-white"><?= ucfirst($r->instalasi); ?></span>
                              <?php elseif ($r->instalasi == 'done'): ?>
                                <span class="badge bg-success text-white"><?= ucfirst($r->instalasi); ?></span>
                              <?php endif; ?>
                            </td>

                            <!-- Kolom Aksi -->
                            <?php if ($r->aktivitas == 'approval'): ?>
                              <td class="text-center">
                                <?php if (in_array($this->session->userdata('role'), ['admin', 'finance'])): ?>
                                  <div class="btn-group btn-group-sm" role="group">
                                    <a href="<?= site_url('finance/approve/'.$r->id_histori) ?>" 
                                       class="btn btn-success btn-sm" 
                                       onclick="return confirm('Setujui langganan ini?')">
                                      Approve
                                    </a>
                                    <a href="<?= site_url('finance/reject/'.$r->id_histori) ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Tolak langganan ini?')">
                                      Reject
                                    </a>
                                  </div>
                                <?php endif; ?>

                                <?php if (in_array($this->session->userdata('role'), ['admin', 'noc'])): ?>
                                  <?php if (in_array($r->instalasi, ['pending', 'maintanance', null])): ?>
                                    <a href="<?= site_url('approval_langganan/approve_online/'.$r->id_histori) ?>" 
                                       class="btn btn-success btn-sm" 
                                       onclick="return confirm('Approve instalasi langganan ini?')">
                                      Approve Instalasi
                                    </a>
                                  <?php endif; ?>
                                <?php endif; ?>
                              </td>
                            <?php else: ?>
                              <td class="text-center text-muted">-</td>
                            <?php endif; ?>

                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="10" class="text-center text-muted">Tidak ada permintaan pending.</td>
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

.card-subtitle {
  color: #6c757d;
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
  h4.page-title {
    font-size: 18px;
  }
  .card-title {
    font-size: 16px;
  }
}
</style>
