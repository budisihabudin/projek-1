<div class="container-fluid px-3 my-3">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-<?= $status == 'success' ? 'success' : ($status == 'error' ? 'danger' : 'warning') ?> text-white text-center">
          <h5 class="mb-0"><?= $title; ?></h5>
        </div>
        <div class="card-body text-center py-5">

          <!-- Icon Status -->
          <?php if ($status == 'success'): ?>
            <div class="mb-4">
              <div class="success-animation">
                <i class="fa fa-check-circle text-success" style="font-size: 80px;"></i>
              </div>
            </div>
          <?php elseif ($status == 'error'): ?>
            <div class="mb-4">
              <i class="fa fa-times-circle text-danger" style="font-size: 80px;"></i>
            </div>
          <?php else: ?>
            <div class="mb-4">
              <i class="fa fa-clock text-warning" style="font-size: 80px;"></i>
            </div>
          <?php endif; ?>

          <!-- Pesan Status -->
          <h4 class="mb-3"><?= $message; ?></h4>

          <!-- Detail Transaksi (jika ada) -->
          <?php if (isset($transaction) && $transaction): ?>
            <div class="alert alert-light text-start">
              <h6>Detail Transaksi:</h6>
              <table class="table table-sm">
                <tr>
                  <td><strong>Order ID:</strong></td>
                  <td><?= $transaction->order_id ?? 'N/A'; ?></td>
                </tr>
                <tr>
                  <td><strong>Transaction ID:</strong></td>
                  <td><?= $transaction->transaction_id ?? 'N/A'; ?></td>
                </tr>
                <tr>
                  <td><strong>Payment Type:</strong></td>
                  <td><?= $transaction->payment_type ?? 'N/A'; ?></td>
                </tr>
                <tr>
                  <td><strong>Amount:</strong></td>
                  <td>Rp <?= number_format($transaction->gross_amount ?? 0, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                  <td><strong>Time:</strong></td>
                  <td><?= $transaction->transaction_time ?? 'N/A'; ?></td>
                </tr>
              </table>
            </div>
          <?php endif; ?>

          <!-- Tombol Aksi -->
          <div class="mt-4">
            <?php if ($status == 'success'): ?>
              <a href="<?= site_url('dashboard'); ?>" class="btn btn-primary">
                <i class="fa fa-home me-2"></i>Back to Dashboard
              </a>
            <?php else: ?>
              <a href="<?= site_url('tagihan/bulanan'); ?>" class="btn btn-primary">
                <i class="fa fa-arrow-left me-2"></i>Kembali ke Tagihan
              </a>
              <a href="<?= site_url('dashboard'); ?>" class="btn btn-secondary ms-2">
                <i class="fa fa-home me-2"></i>Dashboard
              </a>
            <?php endif; ?>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<style>
.success-animation {
  animation: fadeInScale 0.6s ease-in-out;
}

@keyframes fadeInScale {
  0% {
    opacity: 0;
    transform: scale(0.3);
  }
  50% {
    opacity: 1;
    transform: scale(1.05);
  }
  70% {
    transform: scale(0.9);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

.fa-check-circle {
  animation: checkmark 0.6s ease-in-out;
}

@keyframes checkmark {
  0% {
    stroke-dashoffset: 100;
  }
  100% {
    stroke-dashoffset: 0;
  }
}
</style>