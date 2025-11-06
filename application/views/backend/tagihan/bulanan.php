<div class="container-fluid px-3 my-3">

  <!-- TABEL REQUEST PAKET -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-info text-white">
          <h5 class="mb-0"><i class="fa fa-list me-2"></i>Request Paket - Proses Approval</h5>
        </div>
        <div class="card-body">

          <!-- Search Form -->
          <form method="get" class="mb-3">
            <div class="input-group">
              <input type="text" name="keyword" class="form-control"
                placeholder="Cari nama customer / paket..."
                value="<?= htmlspecialchars($keyword ?? '', ENT_QUOTES); ?>">
              <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Cari</button>
            </div>
          </form>

          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead class="table-light">
                <tr>
                  <th>Tanggal Request</th>
                  <th>Nama Customer</th>
                  <th>Paket</th>
                  <th>Status Sales</th>
                  <th>Status Survey</th>
                  <th Status NOC</th>
                  <th>Status Finance</th>
                  <th class="text-center">Proses</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($request_paket)): foreach ($request_paket as $r): ?>
                  <tr>
                    <td><?= date('d-m-Y', strtotime($r->created_at)); ?></td>
                    <td><?= $r->nama_customer; ?></td>
                    <td><?= $r->nama_paket; ?></td>
                    <td>
                      <?php
                      $sales_class = 'bg-secondary';
                      $sales_icon = 'fa-times';
                      if ($r->approval_sales == 'approved') {
                        $sales_class = 'bg-success';
                        $sales_icon = 'fa-check';
                      } elseif ($r->approval_sales == 'pending') {
                        $sales_class = 'bg-warning';
                        $sales_icon = 'fa-clock';
                      } elseif ($r->approval_sales == 'rejected') {
                        $sales_class = 'bg-danger';
                        $sales_icon = 'fa-times';
                      }
                      ?>
                      <span class="badge <?= $sales_class; ?>">
                        <i class="fa <?= $sales_icon; ?>"></i> <?= ucfirst($r->approval_sales); ?>
                      </span>
                    </td>
                    <td>
                      <?php
                      $survey_class = 'bg-secondary';
                      $survey_icon = 'fa-times';
                      if ($r->approval_survei == 'approved') {
                        $survey_class = 'bg-success';
                        $survey_icon = 'fa-check';
                      } elseif ($r->approval_survei == 'pending') {
                        $survey_class = 'bg-warning';
                        $survey_icon = 'fa-clock';
                      } elseif ($r->approval_survei == 'rejected') {
                        $survey_class = 'bg-danger';
                        $survey_icon = 'fa-times';
                      }
                      ?>
                      <span class="badge <?= $survey_class; ?>">
                        <i class="fa <?= $survey_icon; ?>"></i> <?= ucfirst($r->approval_survei); ?>
                      </span>
                    </td>
                    <td>
                      <?php
                      $noc_class = 'bg-secondary';
                      $noc_icon = 'fa-times';
                      if ($r->approval_noc == 'approved') {
                        $noc_class = 'bg-success';
                        $noc_icon = 'fa-check';
                      } elseif ($r->approval_noc == 'pending') {
                        $noc_class = 'bg-warning';
                        $noc_icon = 'fa-clock';
                      } elseif ($r->approval_noc == 'rejected') {
                        $noc_class = 'bg-danger';
                        $noc_icon = 'fa-times';
                      }
                      ?>
                      <span class="badge <?= $noc_class; ?>">
                        <i class="fa <?= $noc_icon; ?>"></i> <?= ucfirst($r->approval_noc); ?>
                      </span>
                    </td>
                    <td>
                      <?php
                      $finance_class = 'bg-secondary';
                      $finance_icon = 'fa-times';
                      if ($r->approval_finance == 'approved') {
                        $finance_class = 'bg-success';
                        $finance_icon = 'fa-check';
                      } elseif ($r->approval_finance == 'pending') {
                        $finance_class = 'bg-warning';
                        $finance_icon = 'fa-clock';
                      } elseif ($r->approval_finance == 'rejected') {
                        $finance_class = 'bg-danger';
                        $finance_icon = 'fa-times';
                      }
                      ?>
                      <span class="badge <?= $finance_class; ?>">
                        <i class="fa <?= $finance_icon; ?>"></i> <?= ucfirst($r->approval_finance); ?>
                      </span>
                    </td>
                    <td class="text-center">
                      <?php
                      // Cek status overall
                      $all_approved = ($r->approval_sales == 'approved' &&
                                     $r->approval_survei == 'approved' &&
                                     $r->approval_noc == 'approved' &&
                                     $r->approval_finance == 'approved');

                      $any_rejected = ($r->approval_sales == 'rejected' ||
                                      $r->approval_survei == 'rejected' ||
                                      $r->approval_noc == 'rejected' ||
                                      $r->approval_finance == 'rejected');

                      $ready_for_finance = ($r->approval_sales == 'approved' &&
                                          $r->approval_survei == 'approved' &&
                                          $r->approval_noc == 'approved' &&
                                          $r->approval_finance == 'pending');

                      if ($all_approved) {
                        echo '<span class="badge bg-success"><i class="fa fa-check-circle"></i> Selesai</span>';
                      } elseif ($any_rejected) {
                        echo '<span class="badge bg-danger"><i class="fa fa-times-circle"></i> Ditolak</span>';
                      } elseif ($ready_for_finance && ($user_role == 'finance' || $user_role == 'admin')) {
                        // Show generate invoice button for Finance
                        echo '<button class="btn btn-success btn-sm btn-generate-invoice" ';
                        echo 'data-id="' . $r->id_request . '" ';
                        echo 'data-customer="' . htmlspecialchars($r->nama_customer, ENT_QUOTES) . '" ';
                        echo 'data-paket="' . htmlspecialchars($r->nama_paket, ENT_QUOTES) . '">';
                        echo '<i class="fa fa-file-invoice"></i> Generate Invoice';
                        echo '</button>';
                      } else {
                        echo '<span class="badge bg-warning"><i class="fa fa-clock"></i> Proses</span>';
                      }
                      ?>
                    </td>
                  </tr>
                <?php endforeach;
                else: ?>
                  <tr>
                    <td colspan="8" class="text-center text-muted">Tidak ada data request paket.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <?php if (isset($pagination_request)): ?>
            <div class="mt-3"><?= $pagination_request; ?></div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>

  <!-- TABEL TAGIHAN PAKET -->
  <div class="row">
    <div class="col-12">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="fa fa-file-invoice-dollar me-2"></i>Tagihan Paket Bulanan</h5>
        </div>
        <div class="card-body">

          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead class="table-light">
                <tr>
                  <th>ID Invoice</th>
                  <th>Nama Customer</th>
                  <th>Paket</th>
                  <th>Tanggal Mulai</th>
                  <th>Tanggal Berakhir</th>
                  <th>Status Bayar</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($tagihan_bulanan)): foreach ($tagihan_bulanan as $t): ?>
                  <tr>
                    <td>
                      <small class="text-muted"><?= $t->id_invoice ?: '-'; ?></small>
                      <?php if ($t->payment_status == 'pending' && $t->id_invoice): ?>
                        <span class="badge bg-warning ms-1" id="status-badge-<?= $t->id_berlangganan; ?>">
                          <i class="fa fa-clock fa-spin"></i> Pending
                        </span>
                      <?php endif; ?>
                    </td>
                    <td><?= $t->nama; ?></td>
                    <td><?= $t->nama_paket; ?></td>
                    <td><?= date('d-m-Y', strtotime($t->tgl_mulai)); ?></td>
                    <td><?= date('d-m-Y', strtotime($t->tgl_berakhir)); ?></td>
                    <td>
                      <?php
                      $badge_class = 'bg-warning';
                      if ($t->status_bayar == 'sudah bayar') {
                          $badge_class = 'bg-success';
                      } elseif ($t->status_bayar == 'menunggu konfirmasi') {
                          $badge_class = 'bg-info';
                      }
                      ?>
                      <span class="badge <?= $badge_class; ?>" id="payment-status-<?= $t->id_berlangganan; ?>">
                        <?= ucfirst(str_replace('_', ' ', $t->status_bayar)); ?>
                      </span>
                      <?php if ($t->payment_time): ?>
                        <br><small class="text-muted"><?= date('d-m H:i', strtotime($t->payment_time)); ?></small>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <!-- Tombol Bayar Manual - DISABLED jika sudah bayar -->
                      <?php if ($t->status_bayar == 'sudah bayar' || $t->payment_status == 'lunas'): ?>
                        <button class="btn btn-outline-secondary btn-sm" disabled>
                          <i class="fa fa-check"></i> Sudah Bayar
                        </button>
                      <?php else: ?>
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                          data-bs-target="#modalManual<?= $t->id_berlangganan; ?>">
                          <i class="fa fa-upload"></i> Bayar Manual
                        </button>
                      <?php endif; ?>

                      <!-- Tombol Bayar Otomatis -->
                      <?php if ($t->status_bayar == 'belum bayar' && $t->status_berlangganan == 'aktif'): ?>
                        <?php if (isset($t->id_berlangganan)): ?>
                          <button class="btn btn-success btn-sm btn-pay-now"
                            data-berlangganan-id="<?= $t->id_berlangganan; ?>"
                            data-customer="<?= $t->nama; ?>"
                            data-harga="<?= $t->harga; ?>"
                            data-paket="<?= $t->nama_paket; ?>">
                            <i class="fa fa-credit-card"></i> Bayar Sekarang
                          </button>
                        <?php endif; ?>
                      <?php else: ?>
                        <?php if ($t->status_bayar == 'menunggu konfirmasi' || $t->payment_status == 'pending'): ?>
                          <!-- Tombol Refresh Status untuk pending payments -->
                          <button class="btn btn-info btn-sm btn-refresh-status"
                            data-order-id="<?= $t->id_invoice; ?>"
                            data-berlangganan-id="<?= $t->id_berlangganan; ?>">
                            <i class="fa fa-refresh"></i> Refresh Status
                          </button>
                        <?php elseif ($t->status_bayar == 'sudah bayar' || $t->payment_status == 'lunas'): ?>
                          <button class="btn btn-success btn-sm" disabled>
                            <i class="fa fa-check"></i> Sudah Lunas
                          </button>
                        <?php else: ?>
                          <button class="btn btn-secondary btn-sm" disabled>
                            <i class="fa fa-pause"></i> Tidak Aktif
                          </button>
                        <?php endif; ?>
                      <?php endif; ?>
                    </td>
                  </tr>

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

                <?php endforeach;
                else: ?>
                  <tr>
                    <td colspan="7" class="text-center text-muted">Tidak ada data tagihan.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <?php if (isset($pagination_tagihan)): ?>
            <div class="mt-3"><?= $pagination_tagihan; ?></div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>

</div>

<!-- jQuery terlebih dahulu -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Midtrans Snap Script - Pastikan ini terload dengan benar -->
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-PRFxISSmbVPIm6Se"></script>

<script>
  // Tunggu Snap terload
  window.addEventListener('load', function() {
    console.log('Snap loaded:', typeof snap !== 'undefined');
    if (typeof snap !== 'undefined') {
      console.log('Midtrans Snap ready to use!');
    }
  });
</script>

<script>
  $(document).ready(function() {
    console.log('Document ready, payment buttons found:', $('.btn-pay-now').length);

    // Generate Invoice Button Handler
    $('.btn-generate-invoice').click(function(e) {
      e.preventDefault();

      var btn = $(this);
      var idRequest = btn.data('id');
      var customerName = btn.data('customer');
      var paketName = btn.data('paket');

      console.log('Generate Invoice clicked - ID:', idRequest, 'Customer:', customerName, 'Paket:', paketName);

      // Confirm before generating invoice
      if (!confirm('Generate invoice untuk ' + customerName + ' - ' + paketName + ' ?')) {
        return;
      }

      // Disable button and show loading
      var originalText = btn.html();
      btn.html('<i class="fa fa-spinner fa-spin"></i> Generating...');
      btn.prop('disabled', true);

      // AJAX call to approve finance and generate invoice
      $.ajax({
        url: '<?= site_url('request/ajax_approve_finance'); ?>',
        method: 'POST',
        data: {
          id_request: idRequest
        },
        dataType: 'json',
        success: function(response) {
          console.log('Generate invoice response:', response);

          if (response.success) {
            // Show success message
            alert('Invoice berhasil digenerate! Invoice No: ' + response.invoice_no + '\nCustomer: ' + customerName);

            // Reload page to show updated data
            window.location.reload();
          } else {
            // Show error message
            alert('Gagal generate invoice: ' + response.message);
            btn.html(originalText);
            btn.prop('disabled', false);
          }
        },
        error: function(xhr, status, error) {
          console.log('AJAX Error:', error);
          console.log('Response Text:', xhr.responseText);
          alert('Terjadi kesalahan saat generate invoice. Silakan coba lagi.');
          btn.html(originalText);
          btn.prop('disabled', false);
        }
      });
    });

    $('.btn-pay-now').click(function(e) {
      e.preventDefault();

      var btn = $(this);
      var berlanggananId = btn.data('berlangganan-id');
      var customerName = btn.data('customer') || 'Pelanggan';
      var harga = btn.data('harga');
      var paket = btn.data('paket');

      console.log('Payment clicked - ID:', berlanggananId, 'Customer:', customerName, 'Harga:', harga);

      // Validasi data
      if (!berlanggananId) {
        alert('Data berlangganan tidak lengkap. Silakan refresh halaman.');
        return;
      }

      // Cek apakah Snap available
      if (typeof snap === 'undefined') {
        alert('Payment gateway tidak tersedia. Silakan refresh halaman.');
        return;
      }

      // Tampilkan loading
      var originalText = btn.html();
      btn.html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
      btn.prop('disabled', true);

      // Langsung request snap token real
      requestSnapToken(berlanggananId, customerName, harga, paket, btn, originalText);
    });

    function requestSnapToken(berlanggananId, customerName, harga, paket, btn, originalText) {
      console.log('Requesting snap token for berlangganan:', berlanggananId);

      var ajaxUrl = '<?= site_url('payment/token'); ?>/' + berlanggananId;
      console.log('AJAX URL:', ajaxUrl);

      $.ajax({
        url: ajaxUrl,
        method: 'GET',
        dataType: 'json',
        beforeSend: function(xhr) {
          console.log('Sending request to:', ajaxUrl);
        },
        success: function(response) {
          console.log('AJAX Response:', response);

          if (response.token) {
            console.log('Snap token received, opening popup...');
            console.log('Token length:', response.token.length);

            // Check if snap is available
            if (typeof snap !== 'undefined') {
              console.log('Snap object available, opening popup...');

              // Buka Snap popup langsung - tanpa alert
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
                },
                onPending: function(result) {
                  console.log('Payment pending:', result);
                  btn.html(originalText);
                  btn.prop('disabled', false);
                },
                onError: function(result) {
                  console.log('Payment error:', result);
                  btn.html(originalText);
                  btn.prop('disabled', false);
                },
                onClose: function() {
                  console.log('Payment popup closed');
                  btn.html(originalText);
                  btn.prop('disabled', false);
                }
              });
            } else {
              console.error('Snap is not loaded!');
              console.log('typeof snap:', typeof snap);
              console.log('window.snap:', typeof window.snap);
              btn.html(originalText);
              btn.prop('disabled', false);
            }
          } else if (response.error) {
            // Handle session expired error
            if (response.redirect) {
              console.error('Session expired, redirecting to login...');
              alert('Session Anda telah expired. Silakan login kembali.');
              window.location.href = response.redirect;
            } else {
              console.error('Server error:', response.error);
              alert('Error: ' + response.error);
            }
            btn.html(originalText);
            btn.prop('disabled', false);
          } else {
            console.error('Unknown response format:', response);
            btn.html(originalText);
            btn.prop('disabled', false);
          }
        },
        error: function(xhr, status, error) {
          console.error('AJAX Error:', {
            status: status,
            error: error,
            responseText: xhr.responseText,
            statusCode: xhr.status
          });
          btn.html(originalText);
          btn.prop('disabled', false);
        }
      });
    }

    // Function untuk update payment status tanpa reload
    function updatePaymentStatus(berlanggananId, orderId, newStatus) {
      console.log('Updating payment status:', {
        berlanggananId: berlanggananId,
        orderId: orderId,
        newStatus: newStatus
      });

      // Update status badge
      var statusBadge = $('#status-badge-' + berlanggananId);
      var paymentStatus = $('#payment-status-' + berlanggananId);

      if (statusBadge.length) {
        statusBadge.removeClass('bg-warning').addClass('bg-success');
        statusBadge.html('<i class="fa fa-check"></i> Lunas');
      }

      if (paymentStatus.length) {
        paymentStatus.removeClass('bg-warning bg-info').addClass('bg-success');
        paymentStatus.text('Sudah bayar');
      }

      // Disable payment button
      $('.btn-pay-now[data-berlangganan-id="' + berlanggananId + '"]')
        .removeClass('btn-success')
        .addClass('btn-secondary')
        .html('<i class="fa fa-check"></i> Sudah Lunas')
        .prop('disabled', true);

      // Disable manual upload button
      $('.btn[data-bs-target="#modalManual' + berlanggananId + '"]')
        .removeClass('btn-outline-primary')
        .addClass('btn-outline-secondary')
        .html('<i class="fa fa-check"></i> Sudah Bayar')
        .prop('disabled', true)
        .removeAttr('data-bs-target');

      // Debug: Call payment check status endpoint
      setTimeout(function() {
        checkPaymentStatus(orderId, berlanggananId);
      }, 2000);
    }

    // Refresh Status button handler
    $('.btn-refresh-status').click(function(e) {
      e.preventDefault();
      var btn = $(this);
      var orderId = btn.data('order-id');
      var berlanggananId = btn.data('berlangganan-id');

      if (!orderId) {
        alert('Order ID tidak valid');
        return;
      }

      // Tampilkan loading
      var originalHtml = btn.html();
      btn.html('<i class="fa fa-spinner fa-spin"></i> Checking...');
      btn.prop('disabled', true);

      // Call manual trigger update API
      $.ajax({
        url: '<?= site_url('payment/manual_trigger_update'); ?>/' + orderId,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
          console.log('Manual update response:', response);

          if (response.success) {
            // Reload halaman untuk update status
            showNotification('Status berhasil diperbarui! ' + response.message, 'success');
            setTimeout(function() {
              location.reload();
            }, 2000);
          } else {
            showNotification('Gagal update status: ' + (response.error || 'Unknown error'), 'error');
          }
        },
        error: function(xhr, status, error) {
          console.error('Manual update error:', error);
          showNotification('Terjadi kesalahan saat refresh status', 'error');
        },
        complete: function() {
          // Restore button
          btn.html(originalHtml);
          btn.prop('disabled', false);
        }
      });
    });

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
        notification.alert('close');
      }, 5000);
    }

    // Debug: Log semua payment status
    console.log('Payment Debug - Current page loaded at:', new Date().toISOString());
    $('.btn-pay-now').each(function() {
      var id = $(this).data('berlangganan-id');
      console.log('Payment button found for ID:', id);
    });

  });
</script>