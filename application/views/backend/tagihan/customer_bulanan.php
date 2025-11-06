<div class="container-fluid px-3 my-3">

  <div class="row">
    <div class="col-12">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white">
          <h5 class="mb-0"><i class="fa fa-file-invoice-dollar me-2"></i>Tagihan Internet Saya</h5>
        </div>
        <div class="card-body">

          <!-- Search Form -->
          <form method="get" class="mb-3">
            <div class="input-group">
              <input type="text" name="keyword" class="form-control"
                     placeholder="Cari nomor invoice atau paket..."
                     value="<?= htmlspecialchars($keyword ?? '', ENT_QUOTES); ?>">
              <button class="btn btn-success" type="submit">
                <i class="fa fa-search"></i> Cari
              </button>
            </div>
          </form>

          <!-- Tabel Request Pemesanan -->
          <div class="table-responsive shadow-sm rounded mb-4">
            <table class="table table-bordered table-striped table-hover align-middle mb-0">
              <thead class="table-info text-white">
                <tr>
                  <th>Paket Layanan</th>
                  <th>Status</th>
                  <th>Tanggal Request</th>
                  <th>Perkiraan Aktif</th>
                  <th class="text-center">Progress</th>
                </tr>
              </thead>
              <tbody>
                <?php if(!empty($tagihan_bulanan)): ?>
                  <?php foreach($tagihan_bulanan as $t): ?>
                    <tr>
                      <td>
                        <strong><?= htmlspecialchars($t->nama_paket, ENT_QUOTES); ?></strong>
                        <br><small class="text-muted">Request: <?= date('d-M-Y', strtotime($t->created_at)); ?></small>
                      </td>
                      <td>
                        <?php
                        // Status yang customer-friendly - cek status langsung dari data
                        $is_paid = ($t->status_bayar == 'sudah bayar' || $t->payment_status == 'lunas');
                        $is_request_level = ($t->status_berlangganan == 'request');

                        if ($is_paid) {
                            echo '<span class="badge bg-success fs-6"><i class="fa fa-check-circle"></i> Aktif</span>';
                        } elseif ($is_request_level) {
                            // Status untuk request level
                            if ($t->approval_finance == 1) {
                                echo '<span class="badge bg-warning fs-6"><i class="fa fa-credit-card"></i> Menunggu Pembayaran</span>';
                            } elseif ($t->approval_sales == 1 && $t->approval_survei == 1 && $t->approval_noc == 1 && $t->approval_finance == 0) {
                                echo '<span class="badge bg-info fs-6"><i class="fa fa-file-invoice"></i> Menunggu Invoice</span>';
                            } else {
                                echo '<span class="badge bg-secondary fs-6"><i class="fa fa-clock"></i> Sedang Diproses</span>';
                            }
                        } else {
                            // Status untuk berlangganan level
                            if ($t->payment_status == 'pending') {
                                echo '<span class="badge bg-info fs-6"><i class="fa fa-hourglass-half"></i> Menunggu Konfirmasi</span>';
                            } else {
                                echo '<span class="badge bg-warning fs-6"><i class="fa fa-credit-card"></i> Menunggu Pembayaran</span>';
                            }
                        }
                        ?>
                      </td>
                      <td><?= date('d-M-Y', strtotime($t->tgl_mulai ?: $t->created_at)); ?></td>
                      <td>
                        <?php
                        if ($is_paid) {
                            // Tanggal aktif dari data berlangganan
                            echo date('d-M-Y', strtotime($t->tgl_mulai));
                        } elseif ($t->approval_finance == 1) { // MAX() returns 1 for approved
                            echo date('d-M-Y', strtotime($t->tgl_mulai ?: $t->created_at));
                        } else {
                            echo '<span class="text-muted">Menunggu Proses</span>';
                        }
                        ?>
                      </td>
                      <td class="text-center">
                        <?php
                        // Progress bar - berdasarkan approval
                        $steps = 0;
                        $total_steps = 4;

                        if ($t->approval_sales == 1) $steps++; // MAX() returns 1 for approved
                        if ($t->approval_survei == 1) $steps++;
                        if ($t->approval_noc == 1) $steps++;
                        if ($t->approval_finance == 1) $steps++;

                        // Cek status pembayaran
                        if ($t->approval_finance == 1 && $is_paid) {
                            // Jika sudah dibayar, progress 100%
                            $steps = $total_steps;
                        } elseif ($t->approval_finance == 1 && !$is_paid) {
                            // Finance approved tapi belum bayar, progress 75%
                            $steps = 3;
                                }

                        $percentage = ($steps / $total_steps) * 100;

                        // Set color based on progress
                        $progress_color = '#28a745'; // Default green untuk completed
                        if ($steps == 3) {
                            $progress_color = '#ffc107'; // Yellow untuk menunggu pembayaran
                        } elseif ($steps < 3) {
                            $progress_color = '#6c757d'; // Gray untuk proses
                        }

                        // Status text untuk progress
                        $progress_text = $steps . '/' . $total_steps;
                        if ($is_paid) {
                            $progress_text = 'Selesai';
                        }
                        ?>
                        <div class="progress" style="height: 20px;">
                          <div class="progress-bar" role="progressbar"
                               style="width: <?= $percentage; ?>%; background-color: <?= $progress_color; ?>;"
                               aria-valuenow="<?= $percentage; ?>" aria-valuemin="0" aria-valuemax="100">
                            <?= $progress_text; ?>
                          </div>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="5" class="text-center text-muted py-3">
                      <small>Belum ada request pemesanan.</small>
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <!-- Tabel Tagihan Bulanan - Hanya yang berlangganan -->
          <?php
          // Filter hanya data berlangganan (bukan request)
          $berlangganan_only = array_filter($tagihan_bulanan, function($item) {
              return $item->status_berlangganan != 'request' && $item->id_invoice;
          });
          ?>
          <?php if(!empty($berlangganan_only)): ?>
          <h5 class="mb-3"><i class="fa fa-file-invoice-dollar"></i> Tagihan Bulanan</h5>
          <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-striped table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Nomor Invoice</th>
                  <th>Paket</th>
                  <th>Periode</th>
                  <th>Jumlah Tagihan</th>
                  <th>Status</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($berlangganan_only as $t): ?>
                    <tr>
                      <td>
                        <strong><?= $t->id_invoice ?: '-'; ?></strong>
                        <?php if ($t->payment_status == 'pending' && $t->id_invoice): ?>
                          <span class="badge bg-warning ms-1">
                            <i class="fa fa-clock fa-spin"></i> Pending
                          </span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?= htmlspecialchars($t->nama_paket, ENT_QUOTES); ?>
                        <br><small class="text-muted"><?= htmlspecialchars($t->nama, ENT_QUOTES); ?></small>
                      </td>
                      <td>
                        <?= date('d-M-Y', strtotime($t->tgl_mulai)); ?>
                        <br>
                        <small class="text-muted">s/d <?= date('d-M-Y', strtotime($t->tgl_berakhir)); ?></small>
                      </td>
                      <td>
                        <strong class="text-primary">
                          Rp <?= number_format($t->harga ?? 0, 0, ',', '.'); ?>
                        </strong>
                      </td>
                      <td>
                        <?php
                        $badge_class = 'bg-warning';
                        $status_text = 'Menunggu Pembayaran';
                        $icon = 'fa-clock';

                        if ($t->status_bayar == 'sudah bayar' || $t->payment_status == 'lunas') {
                            $badge_class = 'bg-success';
                            $status_text = 'Sudah Dibayar';
                            $icon = 'fa-check-circle';
                        } elseif ($t->status_bayar == 'menunggu konfirmasi') {
                            $badge_class = 'bg-info';
                            $status_text = 'Menunggu Konfirmasi';
                            $icon = 'fa-hourglass-half';
                        }

                        echo '<span class="badge ' . $badge_class . ' fs-6">';
                        echo '<i class="fa ' . $icon . '"></i> ' . $status_text;
                        echo '</span>';
                        ?>
                      </td>
                      <td class="text-center">
                        <?php if ($t->id_invoice && $t->status_bayar != 'sudah bayar'): ?>
                          <button class="btn btn-success btn-sm btn-pay-now"
                                  data-berlangganan-id="<?= $t->id_berlangganan; ?>"
                                  data-invoice="<?= $t->id_invoice; ?>"
                                  data-customer="<?= htmlspecialchars($t->nama, ENT_QUOTES); ?>"
                                  data-harga="<?= $t->harga ?? 0; ?>">
                            <i class="fa fa-credit-card"></i> Bayar Sekarang
                          </button>
                          <!-- Tombol Bayar Manual -->
                          <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#modalManual<?= $t->id_berlangganan; ?>">
                            <i class="fa fa-upload"></i> Bayar Manual
                          </button>
                        <?php elseif ($t->status_bayar == 'sudah bayar'): ?>
                          <button class="btn btn-outline-success btn-sm" disabled>
                            <i class="fa fa-check"></i> Sudah Dibayar
                          </button>
                        <?php elseif ($t->status_bayar == 'menunggu konfirmasi'): ?>
                          <button class="btn btn-info btn-sm btn-refresh-status"
                                  data-berlangganan-id="<?= $t->id_berlangganan; ?>">
                            <i class="fa fa-refresh"></i> Cek Status
                          </button>
                        <?php else: ?>
                          <button class="btn btn-secondary btn-sm" disabled>
                            <i class="fa fa-clock"></i> Menunggu
                          </button>
                        <?php endif; ?>

                        <!-- Modal Bayar Manual -->
                        <div class="modal fade" id="modalManual<?= $t->id_berlangganan; ?>" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                              <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">Upload Bukti Pembayaran</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                              </div>
                              <form action="<?= site_url('pembayaran/bayar_manual/' . $t->id_berlangganan); ?>" method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                  <div class="mb-3">
                                    <label class="form-label">Upload Bukti (jpg/png/pdf)</label>
                                    <input type="file" name="bukti_pembayaran" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Kirim</button>
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                      <i class="fa fa-inbox fa-3x mb-3"></i>
                      <p>Belum ada data tagihan.</p>
                      <small>Tagihan akan muncul setelah request layanan Anda disetujui.</small>
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <?php if (isset($pagination_tagihan)): ?>
            <div class="mt-4">
              <style>
                /* Modern Pagination Styling */
                .modern-pagination {
                  display: flex;
                  justify-content: center;
                  align-items: center;
                  flex-wrap: wrap;
                  gap: 0.5rem;
                  margin: 1rem 0;
                }

                .modern-pagination .page-item {
                  list-style: none;
                  margin: 0;
                }

                .modern-pagination .page-link {
                  display: inline-flex;
                  align-items: center;
                  justify-content: center;
                  min-width: 45px;
                  height: 45px;
                  margin: 0 2px;
                  padding: 0 15px;
                  border: none;
                  border-radius: 12px;
                  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                  color: white !important;
                  font-weight: 600;
                  font-size: 14px;
                  text-decoration: none !important;
                  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                  box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
                  position: relative;
                  overflow: hidden;
                }

                .modern-pagination .page-link:before {
                  content: '';
                  position: absolute;
                  top: 0;
                  left: -100%;
                  width: 100%;
                  height: 100%;
                  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                  transition: left 0.6s ease;
                }

                .modern-pagination .page-link:hover:before {
                  left: 100%;
                }

                .modern-pagination .page-link:hover {
                  transform: translateY(-3px) scale(1.05);
                  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
                  background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
                  color: white !important;
                  text-decoration: none !important;
                }

                .modern-pagination .page-item.active .page-link {
                  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                  box-shadow: 0 6px 20px rgba(245, 87, 108, 0.4);
                  transform: scale(1.1);
                  z-index: 10;
                }

                .modern-pagination .page-item.active .page-link:hover {
                  transform: scale(1.1) translateY(-3px);
                  box-shadow: 0 10px 30px rgba(245, 87, 108, 0.5);
                }

                .modern-pagination .page-item.disabled .page-link {
                  background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
                  color: #6c757d !important;
                  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                  cursor: not-allowed;
                  transform: none;
                }

                .modern-pagination .page-item.disabled .page-link:hover {
                  transform: none;
                  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                  background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
                }

                /* Pagination Info Badge */
                .pagination-info-badge {
                  display: inline-flex;
                  align-items: center;
                  padding: 10px 20px;
                  margin: 0 10px;
                  background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
                  color: #5e35b1;
                  border-radius: 25px;
                  font-size: 13px;
                  font-weight: 700;
                  border: 2px solid rgba(94, 53, 177, 0.2);
                  box-shadow: 0 2px 10px rgba(94, 53, 177, 0.15);
                  transition: all 0.3s ease;
                }

                .pagination-info-badge:hover {
                  transform: translateY(-2px);
                  box-shadow: 0 4px 15px rgba(94, 53, 177, 0.25);
                  border-color: rgba(94, 53, 177, 0.3);
                }

                /* Responsive Design */
                @media (max-width: 768px) {
                  .modern-pagination {
                    gap: 0.25rem;
                  }

                  .modern-pagination .page-link {
                    min-width: 40px;
                    height: 40px;
                    padding: 0 12px;
                    font-size: 13px;
                    margin: 0 1px;
                  }

                  .pagination-info-badge {
                    font-size: 12px;
                    padding: 8px 16px;
                    margin: 0 8px;
                  }
                }

                /* Loading Animation */
                @keyframes paginationPulse {
                  0% { transform: scale(1); }
                  50% { transform: scale(1.05); }
                  100% { transform: scale(1); }
                }

                .modern-pagination.loading .page-link {
                  animation: paginationPulse 1.5s ease-in-out infinite;
                }

                /* Fade in Animation */
                @keyframes fadeInScale {
                  from {
                    opacity: 0;
                    transform: translateY(20px) scale(0.9);
                  }
                  to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                  }
                }

                .modern-pagination {
                  animation: fadeInScale 0.6s ease-out;
                }

                /* Override Bootstrap default pagination styles */
                .modern-pagination .page-link:focus {
                  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
                  outline: none;
                }
              </style>

              <div class="modern-pagination">
                <?php
                // Parse the existing pagination HTML and extract the links
                $pagination_html = $pagination_tagihan;

                // Convert HTML to array of page numbers and links
                $pages = [];

                // Extract all page links
                if (preg_match_all('/<a[^>]*href="([^"]*)"[^>]*class="[^"]*page-link[^"]*"[^>]*>(\d+|&laquo;|&raquo;|‹|›|First|Last|Previous|Next|<|>)[^<]*<\/a>/i', $pagination_html, $matches, PREG_SET_ORDER)) {
                  foreach ($matches as $match) {
                    $url = $match[1];
                    $text = trim(strip_tags($match[2]));

                    // Normalize text
                    if ($text === '&laquo;' || $text === '‹' || strtolower($text) === 'previous' || $text === '<') {
                      $text = '‹';
                    } elseif ($text === '&raquo;' || $text === '›' || strtolower($text) === 'next' || $text === '>') {
                      $text = '›';
                    }

                    // Check if active
                    $is_active = strpos($match[0], 'active') !== false;

                    $pages[] = [
                      'url' => $url,
                      'text' => $text,
                      'active' => $is_active
                    ];
                  }
                }

                // Extract active page spans
                if (preg_match_all('/<span[^>]*class="[^"]*page-link[^"]* active[^"]*"[^>]*>(\d+)[^<]*<\/span>/i', $pagination_html, $matches, PREG_SET_ORDER)) {
                  foreach ($matches as $match) {
                    $text = trim(strip_tags($match[1]));
                    $pages[] = [
                      'url' => null,
                      'text' => $text,
                      'active' => true
                    ];
                  }
                }

                // Extract disabled items
                if (preg_match_all('/<span[^>]*class="[^"]*page-link[^"]* disabled[^"]*"[^>]*>([^<]*)<\/span>/i', $pagination_html, $matches, PREG_SET_ORDER)) {
                  foreach ($matches as $match) {
                    $text = trim(strip_tags($match[1]));

                    // Normalize text
                    if ($text === '&laquo;' || $text === '‹' || strtolower($text) === 'previous' || $text === '<') {
                      $text = '‹';
                    } elseif ($text === '&raquo;' || $text === '›' || strtolower($text) === 'next' || $text === '>') {
                      $text = '›';
                    }

                    $pages[] = [
                      'url' => null,
                      'text' => $text,
                      'disabled' => true
                    ];
                  }
                }

                // Extract ellipsis
                if (preg_match_all('/<span[^>]*class="[^"]*page-link[^"]*"[^>]*>(\.{3}|…)[^<]*<\/span>/i', $pagination_html, $matches, PREG_SET_ORDER)) {
                  foreach ($matches as $match) {
                    $pages[] = [
                      'url' => null,
                      'text' => '…',
                      'ellipsis' => true
                    ];
                  }
                }

                // Render custom pagination
                foreach ($pages as $page):
                  if (!empty($page['ellipsis'])): ?>
                    <li class="page-item">
                      <span class="page-link" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); color: #6c757d; cursor: default;">
                        <?= htmlspecialchars($page['text']) ?>
                      </span>
                    </li>
                  <?php elseif (!empty($page['disabled'])): ?>
                    <li class="page-item disabled">
                      <span class="page-link">
                        <?= htmlspecialchars($page['text']) ?>
                      </span>
                    </li>
                  <?php elseif (!empty($page['active'])): ?>
                    <li class="page-item active">
                      <span class="page-link">
                        <?= htmlspecialchars($page['text']) ?>
                      </span>
                    </li>
                  <?php else: ?>
                    <li class="page-item">
                      <a href="<?= htmlspecialchars($page['url']) ?>" class="page-link">
                        <?= htmlspecialchars($page['text']) ?>
                      </a>
                    </li>
                  <?php endif;
                endforeach; ?>

                <!-- Pagination Info -->
                <div class="pagination-info-badge">
                  <i class="fas fa-file-invoice-dollar me-2"></i>
                  <span>Halaman Tagihan</span>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <!-- Info Box -->
          <div class="alert alert-success mt-4">
            <h6><i class="fa fa-info-circle"></i> Informasi Pembayaran</h6>
            <ul class="mb-0">
              <li><strong>Menunggu Pembayaran</strong> - Tagihan sudah siap, silakan lakukan pembayaran</li>
              <li><strong>Menunggu Konfirmasi</strong> - Pembayaran sedang diverifikasi oleh sistem</li>
              <li><strong>Sudah Lunas</strong> - Tagihan sudah dibayar dan dikonfirmasi</li>
            </ul>
            <p class="mb-0 mt-2">
              <strong>Pembayaran dapat dilakukan melalui:</strong><br>
              • Transfer Bank (sesuai invoice)<br>
              • E-Wallet (GoPay, OVO, DANA)<br>
              • Kartu Kredit/Debit
            </p>
          </div>

        </div>
      </div>
    </div>
  </div>

</div>

<!-- Upload Bukti Pembayaran Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="uploadModalLabel">
          <i class="fa fa-upload"></i> Upload Bukti Pembayaran
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="uploadForm" method="post" action="<?= site_url('payment/upload_manual'); ?>" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="id_berlangganan" id="upload_id_berlangganan">

          <div class="mb-3">
            <label for="bukti_bayar" class="form-label">Upload Bukti Transfer</label>
            <input type="file" class="form-control" id="bukti_bayar" name="bukti_bayar" accept="image/*,.pdf" required>
            <small class="text-muted">Format: JPG, PNG, atau PDF (Max 5MB)</small>
          </div>

          <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                      placeholder="Misal: Transfer dari BCA atas nama..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">
            <i class="fa fa-upload"></i> Upload Bukti
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // Payment buttons
    $('.btn-pay-now').click(function(e) {
        e.preventDefault();

        var btn = $(this);
        var berlanggananId = btn.data('berlangganan-id');
        var customerName = btn.data('customer');
        var invoice = btn.data('invoice');
        var harga = btn.data('harga');

        // Show loading
        var originalText = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
        btn.prop('disabled', true);

        // Get snap token for payment
        $.ajax({
            url: '<?= site_url('payment/token'); ?>/' + berlanggananId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.token) {
                    // Open Midtrans Snap popup
                    snap.pay(response.token, {
                        onSuccess: function(result) {
                            console.log('Payment success:', result);
                            console.log('Payment result details:');
                            console.log('- Transaction ID:', result.transaction_id);
                            console.log('- Order ID:', result.order_id);
                            console.log('- Gross Amount:', result.gross_amount);
                            console.log('- Payment Type:', result.payment_type);
                            console.log('- Transaction Status:', result.transaction_status);
                            console.log('- Transaction Time:', result.transaction_time);

                            // Update status secara real-time tanpa reload
                            updatePaymentStatus(berlanggananId, result.order_id, 'lunas');

                            // Show success notification
                            showNotification('Pembayaran berhasil! Order ID: ' + result.order_id, 'success');

                            // Update buttons
                            btn.removeClass('btn-success').addClass('btn-outline-success')
                              .html('<i class="fa fa-check"></i> Sudah Dibayar')
                              .prop('disabled', true);

                            // Auto reload after successful payment to update all data
                            setTimeout(function() {
                                location.reload();
                            }, 3000);
                        },
                        onPending: function(result) {
                            alert('Pembayaran pending. Silakan selesaikan pembayaran Anda.');
                            btn.html(originalText);
                            btn.prop('disabled', false);
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal. Silakan coba lagi.');
                            btn.html(originalText);
                            btn.prop('disabled', false);
                        },
                        onClose: function() {
                            btn.html(originalText);
                            btn.prop('disabled', false);
                        }
                    });
                } else {
                    // Show upload modal if snap token not available
                    $('#upload_id_berlangganan').val(berlanggananId);
                    $('#uploadModal').modal('show');
                    btn.html(originalText);
                    btn.prop('disabled', false);
                }
            },
            error: function() {
                alert('Terjadi kesalahan. Silakan coba lagi.');
                btn.html(originalText);
                btn.prop('disabled', false);
            }
        });
    });

    // Refresh status button
    $('.btn-refresh-status').click(function(e) {
        e.preventDefault();

        var btn = $(this);
        var berlanggananId = btn.data('berlangganan-id');

        btn.html('<i class="fa fa-spinner fa-spin"></i> Mengecek...');
        btn.prop('disabled', true);

        // Check payment status
        $.ajax({
            url: '<?= site_url('payment/check_status'); ?>/' + berlanggananId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function() {
                btn.html('<i class="fa fa-refresh"></i> Cek Status');
                btn.prop('disabled', false);
            }
        });
    });
});

// Function untuk update payment status tanpa reload
function updatePaymentStatus(berlanggananId, orderId, newStatus) {
  console.log('Updating payment status:', {
    berlanggananId: berlanggananId,
    orderId: orderId,
    newStatus: newStatus
  });

  // Update status payment di database via AJAX
  $.ajax({
    url: '<?= site_url('payment/finish'); ?>',
    method: 'GET',
    data: {
      order_id: orderId,
      status_code: '200',
      transaction_status: 'settlement'
    },
    success: function(response) {
      console.log('Status updated successfully:', response);
    },
    error: function(xhr, status, error) {
      console.log('Error updating status:', error);
    }
  });

  // Disable manual upload button
  $('.btn[data-bs-target="#modalManual' + berlanggananId + '"]')
    .removeClass('btn-outline-primary')
    .addClass('btn-outline-secondary')
    .html('<i class="fa fa-check"></i> Sudah Dibayar')
    .prop('disabled', true)
    .removeAttr('data-bs-target');
}

// Function untuk show notification
function showNotification(message, type) {
  var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
  var notification = $('<div class="alert ' + alertClass + ' alert-dismissible fade show position-fixed" ' +
                     'style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">' +
                     '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                     '<strong>' + (type === 'success' ? 'Sukses!' : 'Error!') + '</strong> ' +
                     message + '</div>');

  $('body').append(notification);

  // Auto remove setelah 5 detik
  setTimeout(function() {
    notification.fadeOut(500, function() {
      notification.remove();
    });
  }, 5000);
}
</script>

<!-- Midtrans Snap Script - Pastikan ini terload dengan benar -->
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-PRFxISSmbVPIm6Se"></script>

<script>
  // Tunggu Snap terload
  window.addEventListener('load', function() {
    console.log('Snap loaded:', typeof snap !== 'undefined');
    if (typeof snap !== 'undefined') {
      console.log('Midtrans Snap ready to use!');
    } else {
      console.log('Snap failed to load');
    }
  });
</script>