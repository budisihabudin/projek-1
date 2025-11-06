<div class="container-fluid px-3 my-3">

  <div class="row">
    <div class="col-12">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="fa fa-user me-2"></i>Status Layanan Internet Saya</h5>
        </div>
        <div class="card-body">

          <!-- Search Form -->
          <form method="get" class="mb-3">
            <div class="input-group">
              <input type="text" name="keyword" class="form-control"
                     placeholder="Cari paket layanan..."
                     value="<?= htmlspecialchars($keyword ?? '', ENT_QUOTES); ?>">
              <button class="btn btn-primary" type="submit">
                <i class="fa fa-search"></i> Cari
              </button>
            </div>
          </form>

          <!-- Tabel Customer View -->
          <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-striped table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Paket Layanan</th>
                  <th>Status Berlangganan</th>
                  <th>Tanggal Aktif</th>
                  <th>Tagihan</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if(!empty($requests)): ?>
                  <?php foreach($requests as $r): ?>
                    <tr>
                      <td>
                        <strong><?= htmlspecialchars($r->nama_paket, ENT_QUOTES); ?></strong>
                        <br><small class="text-muted">Req: <?= date('d-m-Y', strtotime($r->tgl_langganan)); ?></small>
                      </td>
                      <td>
                        <?php
                        // Status yang customer-friendly
                        if ($r->approval_finance == 'approved') {
                            echo '<span class="badge bg-success fs-6"><i class="fa fa-check"></i> Aktif</span>';
                        } elseif ($r->approval_finance == 'pending' && $r->approval_sales == 'approved' && $r->approval_survei == 'approved' && $r->approval_noc == 'approved') {
                            echo '<span class="badge bg-info fs-6"><i class="fa fa-file-invoice"></i> Menunggu Invoice</span>';
                        } else {
                            echo '<span class="badge bg-warning fs-6"><i class="fa fa-clock"></i> Sedang Diproses</span>';
                        }
                        ?>
                      </td>
                      <td>
                        <?= $r->id_invoice ? date('d-m-Y', strtotime($r->tgl_langganan)) : '<span class="text-muted">-</span>'; ?>
                      </td>
                      <td>
                        <?php if ($r->id_invoice): ?>
                          <span class="badge bg-primary fs-6"><?= $r->id_invoice; ?></span>
                        <?php else: ?>
                          <span class="text-muted fs-6">Belum Ada</span>
                        <?php endif; ?>
                      </td>
                      <td class="text-center">
                        <?php if ($r->id_invoice): ?>
                          <a href="<?= site_url('tagihan/bulanan'); ?>" class="btn btn-primary btn-sm">
                            <i class="fa fa-credit-card"></i> Bayar Tagihan
                          </a>
                        <?php else: ?>
                          <button class="btn btn-secondary btn-sm" disabled>
                            <i class="fa fa-clock"></i> Menunggu
                          </button>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                      <i class="fa fa-inbox fa-3x mb-3"></i>
                      <p>Belum ada data request layanan.</p>
                      <small>Silakan hubungi sales untuk memesan layanan internet.</small>
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <?php if (isset($pagination)): ?>
            <div class="mt-3"><?= $pagination; ?></div>
          <?php endif; ?>

          <!-- Info Box -->
          <div class="alert alert-info mt-4">
            <h6><i class="fa fa-info-circle"></i> Informasi Status Layanan</h6>
            <ul class="mb-0">
              <li><strong>Sedang Diproses</strong> - Permintaan Anda sedang dalam proses persetujuan internal</li>
              <li><strong>Menunggu Invoice</strong> - Sedang disiapkan tagihan untuk pembayaran</li>
              <li><strong>Aktif</strong> - Layanan internet Anda sudah aktif dan bisa digunakan</li>
            </ul>
          </div>

        </div>
      </div>
    </div>
  </div>

</div>