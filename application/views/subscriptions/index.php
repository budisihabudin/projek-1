<!-- main content -->
<div class="main-panel">
    <div class="content">
        <div class="container my-4">
            <h3 class="text-center mb-4 display-6 fw-bold">Daftar Paket Internet</h3>

            <!-- Form Pencarian -->
            <form method="get" action="<?= site_url('subscriptions') ?>" class="mb-3">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari paket..." value="<?= htmlspecialchars($keyword ?? '', ENT_QUOTES) ?>">
                    <button class="btn btn-primary">Cari</button>
                </div>
            </form>

            <?php if (!empty($paket)): ?>
                <div class="d-flex overflow-auto gap-3 py-2">
                    <?php foreach ($paket as $p): ?>
                        <div class="card shadow-sm gradient-card flex-shrink-0 paket-card">
                            <div class="card-body text-center">
                                <h5 class="card-title mb-2 text-white"><?= htmlspecialchars($p->nama_paket, ENT_QUOTES, 'UTF-8') ?></h5>
                                <p class="card-text"><?= htmlspecialchars($p->deskripsi, ENT_QUOTES, 'UTF-8') ?></p>

                                <ul class="list-unstyled mb-3 text-start">
                                    <li><strong>Harga:</strong> Rp<?= number_format($p->harga, 0, ',', '.') ?></li>
                                    <li><strong>Kecepatan:</strong> <?= $p->kecepatan ?> Mbps</li>
                                    <li>
                                        <strong>Status:</strong>
                                        <span class="badge <?= ($p->status == 'aktif') ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= ucfirst($p->status) ?>
                                        </span>
                                    </li>
                                </ul>

                                <?php if ($p->status == 'aktif'): ?>
                                    <!-- Dropdown untuk pilihan langganan -->
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm paket-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-shopping-cart me-1"></i> Langganan
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="<?= site_url('subscriptions/add/'.$p->id_paket); ?>">
                                                    <i class="fas fa-file-alt me-2"></i>Request Pemasangan
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item btn-pay-subscription"
                                                   href="#"
                                                   data-paket-id="<?= $p->id_paket; ?>"
                                                   data-paket-name="<?= $p->nama_paket; ?>">
                                                    <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm w-100" disabled>Tidak Tersedia</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-muted">Belum ada paket yang tersedia.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Summary Card -->
<div class="container my-4">
  <div class="card shadow-sm border-0">
    <div class="card-header bg-info text-white">
      <h6 class="mb-0"><i class="fa fa-chart-bar me-2"></i>Ringkasan Paket Langganan</h6>
    </div>
    <div class="card-body">
      <?php if (!empty($paket)): ?>
        <div class="row">
          <!-- Total Paket -->
          <div class="col-md-3 mb-3">
            <div class="card border-primary">
              <div class="card-body text-center">
                <h5 class="text-primary"><?= count($paket); ?></h5>
                <small class="text-muted">Total Paket</small>
              </div>
            </div>
          </div>

          <!-- Paket Aktif -->
          <div class="col-md-3 mb-3">
            <div class="card border-success">
              <div class="card-body text-center">
                <h5 class="text-success">
                  <?= count(array_filter($paket, function($p) {
                    return $p->status == 'aktif';
                  })); ?>
                </h5>
                <small class="text-muted">Paket Aktif</small>
              </div>
            </div>
          </div>

          <!-- Rata-rata Harga -->
          <div class="col-md-3 mb-3">
            <div class="card border-info">
              <div class="card-body text-center">
                <h5 class="text-info">
                  Rp <?= number_format(array_sum(array_map(function($p) {
                    return $p->harga ?? 0;
                  }, $paket)) / count($paket), 0, ',', '.'); ?>
                </h5>
                <small class="text-muted">Rata-rata Harga</small>
              </div>
            </div>
          </div>

          <!-- Total Nilai Potensial -->
          <div class="col-md-3 mb-3">
            <div class="card border-warning">
              <div class="card-body text-center">
                <h5 class="text-warning">
                  Rp <?= number_format(array_sum(array_map(function($p) {
                    return $p->harga ?? 0;
                  }, $paket)), 0, ',', '.'); ?>
                </h5>
                <small class="text-muted">Total Nilai</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Detail Summary per Kecepatan -->
        <div class="mt-4">
          <h6 class="mb-3">Analisis Berdasarkan Kecepatan:</h6>
          <div class="row">
            <?php
            $speed_ranges = [
              '10 Mbps atau kurang' => ['min' => 0, 'max' => 10, 'count' => 0, 'total_harga' => 0],
              '11-50 Mbps' => ['min' => 11, 'max' => 50, 'count' => 0, 'total_harga' => 0],
              '51-100 Mbps' => ['min' => 51, 'max' => 100, 'count' => 0, 'total_harga' => 0],
              'Lebih dari 100 Mbps' => ['min' => 101, 'max' => 9999, 'count' => 0, 'total_harga' => 0]
            ];

            foreach($paket as $p) {
              $speed = (int)$p->kecepatan;
              foreach($speed_ranges as $range_name => $range_data) {
                if($speed >= $range_data['min'] && $speed <= $range_data['max']) {
                  $speed_ranges[$range_name]['count']++;
                  $speed_ranges[$range_name]['total_harga'] += $p->harga;
                  break;
                }
              }
            }

            foreach($speed_ranges as $range_name => $data):
              if($data['count'] > 0):
            ?>
            <div class="col-md-3 mb-3">
              <div class="card border-light">
                <div class="card-body text-center">
                  <h6 class="text-muted"><?= $range_name; ?></h6>
                  <h5 class="text-primary"><?= $data['count']; ?> Paket</h5>
                  <small class="text-success">
                    Avg: Rp <?= number_format($data['total_harga'] / $data['count'], 0, ',', '.'); ?>
                  </small>
                </div>
              </div>
            </div>
            <?php
              endif;
              endforeach;
            ?>
          </div>
        </div>

        <!-- Perbandingan Harga vs Kecepatan -->
        <div class="mt-4">
          <h6 class="mb-3">5 Paket Terbaik (Harga vs Kecepatan):</h6>
          <div class="table-responsive">
            <table class="table table-sm table-bordered">
              <thead class="table-light">
                <tr>
                  <th>Nama Paket</th>
                  <th>Kecepatan</th>
                  <th>Harga</th>
                  <th>Harga per Mbps</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Hitung harga per Mbps dan urutkan
                $paket_value = [];
                foreach($paket as $p) {
                  $harga_per_mbps = $p->kecepatan > 0 ? $p->harga / $p->kecepatan : 999999;
                  $paket_value[] = [
                    'nama' => $p->nama_paket,
                    'kecepatan' => $p->kecepatan,
                    'harga' => $p->harga,
                    'harga_per_mbps' => $harga_per_mbps,
                    'status' => $p->status
                  ];
                }

                // Sort by harga per Mbps (ascending = lebih murah per Mbps)
                usort($paket_value, function($a, $b) {
                  return $a['harga_per_mbps'] - $b['harga_per_mbps'];
                });

                // Tampilkan 5 terbaik
                $top_5 = array_slice($paket_value, 0, 5);
                foreach($top_5 as $paket_data):
                ?>
                <tr>
                  <td><?= $paket_data['nama']; ?></td>
                  <td><?= $paket_data['kecepatan']; ?> Mbps</td>
                  <td>Rp <?= number_format($paket_data['harga'], 0, ',', '.'); ?></td>
                  <td class="text-success">
                    Rp <?= number_format($paket_data['harga_per_mbps'], 0, ',', '.'); ?>
                  </td>
                  <td>
                    <span class="badge <?= $paket_data['status'] == 'aktif' ? 'bg-success' : 'bg-secondary' ?>">
                      <?= ucfirst($paket_data['status']); ?>
                    </span>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php else: ?>
        <p class="text-center text-muted">Belum ada data paket untuk dianalisis.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<style>
/* Scrollbar horizontal */
.overflow-auto::-webkit-scrollbar {
    height: 8px;
}
.overflow-auto::-webkit-scrollbar-thumb {
    background-color: #ccc;
    border-radius: 10px;
}
.overflow-auto::-webkit-scrollbar-thumb:hover {
    background-color: #999;
}

/* Card hover effect */
.card:hover {
    transform: translateY(-5px);
    transition: 0.3s ease;
}

/* Gradasi background card */
.gradient-card {
    background: linear-gradient(135deg, #4e54c8, #8f94fb);
    color: white;
    border: none;
}

/* Ukuran dan jarak card */
.paket-card {
    width: 18rem;
    min-width: 18rem;
    margin-right: 1rem;
}

/* Tombol full-width di card */
.paket-card .btn {
    margin-top: 0.5rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .overflow-auto {
        flex-wrap: wrap !important;
        justify-content: center;
    }
    .paket-card {
        width: 100% !important;
        min-width: auto !important;
        margin-bottom: 1rem;
    }
}
</style>

<!-- Midtrans Snap Script -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-PRFxISSmbVPIm6Se"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('.btn-pay-subscription').click(function(e) {
        e.preventDefault();

        var btn = $(this);
        var paketId = btn.data('paket-id');
        var paketName = btn.data('paket-name');

        // Konfirmasi pembayaran
        if (!confirm('Apakah Anda ingin berlangganan paket ' + paketName + '?')) {
            return;
        }

        // Tampilkan loading
        var originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Memproses...');

        // Request snap token via AJAX
        $.ajax({
            url: '<?= site_url('subscriptions/token'); ?>/' + paketId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.token) {
                    console.log('Snap token received:', response.token);
                    console.log('Order ID:', response.order_id);

                    // Buka Snap popup
                    snap.pay(response.token, {
                        onSuccess: function(result) {
                            console.log('Payment success:', result);
                            window.location.href = '<?= site_url('subscriptions/payment_success?order_id='); ?>' + response.order_id + '&status_code=200&transaction_status=settlement';
                        },
                        onPending: function(result) {
                            console.log('Payment pending:', result);
                            window.location.href = '<?= site_url('subscriptions/payment_pending?order_id='); ?>' + response.order_id + '&status_code=201&transaction_status=pending';
                        },
                        onError: function(result) {
                            console.log('Payment error:', result);
                            alert('Pembayaran gagal. Silakan coba lagi.');
                            btn.html(originalText);
                        },
                        onClose: function() {
                            console.log('Payment popup closed');
                            btn.html(originalText);
                        }
                    });
                } else if (response.error) {
                    alert('Error: ' + response.error);
                    btn.html(originalText);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                btn.html(originalText);
            }
        });
    });
});
</script>
