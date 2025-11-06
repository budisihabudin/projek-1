<div class="container-fluid px-3 my-3">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white">
          <h5 class="mb-0"><i class="fa fa-credit-card me-2"></i>Pembayaran Langganan</h5>
        </div>
        <div class="card-body">

          <!-- Detail Paket -->
          <div class="row mb-4">
            <div class="col-md-6">
              <h6>Detail Paket</h6>
              <table class="table table-sm table-borderless">
                <tr>
                  <td><strong>Nama Paket:</strong></td>
                  <td><?= $paket->nama_paket; ?></td>
                </tr>
                <tr>
                  <td><strong>Kecepatan:</strong></td>
                  <td><?= $paket->kecepatan; ?> Mbps</td>
                </tr>
                <tr>
                  <td><strong>Deskripsi:</strong></td>
                  <td><?= $paket->deskripsi; ?></td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <h6>Informasi Pembayaran</h6>
              <table class="table table-sm table-borderless">
                <tr>
                  <td><strong>Order ID:</strong></td>
                  <td><?= $order_id; ?></td>
                </tr>
                <tr>
                  <td><strong>Jumlah:</strong></td>
                  <td class="text-success fw-bold">Rp <?= number_format($paket->harga, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                  <td><strong>Metode:</strong></td>
                  <td>Payment Gateway (Midtrans)</td>
                </tr>
              </table>
            </div>
          </div>

          <!-- Detail Customer -->
          <div class="alert alert-info" role="alert">
            <h6 class="alert-heading"><i class="fa fa-user me-2"></i>Informasi Customer:</h6>
            <div class="row">
              <div class="col-md-6">
                <strong>Nama:</strong> <?= $customer->nama; ?><br>
                <strong>Email:</strong> <?= $customer->email; ?>
              </div>
              <div class="col-md-6">
                <strong>No. HP:</strong> <?= $customer->no_hp; ?><br>
                <strong>Alamat:</strong> <?= $customer->alamat; ?>
              </div>
            </div>
          </div>

          <!-- Payment Methods Info -->
          <div class="alert alert-light" role="alert">
            <h6 class="alert-heading"><i class="fa fa-info-circle me-2"></i>Metode Pembayaran Tersedia:</h6>
            <div class="row">
              <div class="col-md-6">
                <ul class="mb-0">
                  <li><i class="fa fa-credit-card me-1"></i> Kartu Kredit/Debit</li>
                  <li><i class="fa fa-university me-1"></i> Transfer Virtual Account (BCA, BNI, BRI, dll)</li>
                  <li><i class="fa fa-mobile-alt me-1"></i> E-Wallet (GoPay, ShopeePay, DANA)</li>
                </ul>
              </div>
              <div class="col-md-6">
                <ul class="mb-0">
                  <li><i class="fa fa-qrcode me-1"></i> QRIS</li>
                  <li><i class="fa fa-clock me-1"></i> Cicilan 0% (Kartu Kredit)</li>
                  <li><i class="fa fa-shield-alt me-1"></i> Pembayaran Aman & Terenkripsi</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Button Bayar Sekarang -->
          <div class="text-center">
            <button id="pay-button" class="btn btn-success btn-lg">
              <i class="fa fa-lock me-2"></i>Bayar Sekarang
            </button>
            <p class="text-muted small mt-2">
              <i class="fa fa-shield-alt me-1"></i>Pembayaran aman dengan enkripsi SSL
            </p>
          </div>

          <!-- Back Button -->
          <div class="text-center mt-4">
            <a href="<?= site_url('subscriptions'); ?>" class="btn btn-outline-secondary">
              <i class="fa fa-arrow-left me-2"></i>Kembali ke Daftar Paket
            </a>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Midtrans Snap Script -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= $this->config->item('client_key', 'midtrans'); ?>"></script>

<script>
document.getElementById('pay-button').onclick = function() {
    // Tampilkan loading
    const btn = this;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Memproses...';
    btn.disabled = true;

    // Snap Popup
    snap.pay('<?= $snap_token; ?>', {
        onSuccess: function(result) {
            console.log('Payment success:', result);
            // Redirect ke halaman sukses
            window.location.href = '<?= site_url("subscriptions/payment_success?order_id=" . $order_id . "&status_code=200&transaction_status=settlement"); ?>';
        },
        onPending: function(result) {
            console.log('Payment pending:', result);
            // Redirect ke halaman pending
            window.location.href = '<?= site_url("subscriptions/payment_pending?order_id=" . $order_id . "&status_code=201&transaction_status=pending"); ?>';
        },
        onError: function(result) {
            console.log('Payment error:', result);
            // Redirect ke halaman error
            window.location.href = '<?= site_url("subscriptions/payment_error?order_id=" . $order_id . "&status_code=400&transaction_status=deny"); ?>';
        },
        onClose: function() {
            console.log('Payment popup closed');
            // Kembalikan button ke normal
            btn.innerHTML = originalText;
            btn.disabled = false;

            // Tampilkan notifikasi
            alert('Anda telah menutup jendela pembayaran. Silakan coba lagi.');
        }
    });
};
</script>