<!DOCTYPE html>
<html>
<head>
    <title>Form Pembayaran</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</head>
<body>

<div class="container mt-5">
    <h3>Pembayaran Berlangganan</h3>
    <p>ID Berlangganan: <strong><?= $id_berlangganan; ?></strong></p>

    <div class="mt-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalManual">
            <i class="fa fa-upload"></i> Bayar Manual
        </button>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalOtomatis">
            <i class="fa fa-credit-card"></i> Bayar Otomatis
        </button>
    </div>
</div>

<!-- Modal Bayar Manual -->
<div class="modal fade" id="modalManual" tabindex="-1" aria-labelledby="modalManualLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalManualLabel">Bayar Manual</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= site_url('pembayaran/submit_manual/'.$id_berlangganan) ?>" method="post" enctype="multipart/form-data">
        <div class="modal-body">
            <div class="form-group mb-3">
                <label>Upload Bukti Pembayaran</label>
                <input type="file" name="bukti_bayar" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
            </div>
            <div class="form-group mb-3">
                <label>Keterangan (opsional)</label>
                <textarea name="keterangan" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Kirim Pembayaran</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Bayar Otomatis -->
<div class="modal fade" id="modalOtomatis" tabindex="-1" aria-labelledby="modalOtomatisLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalOtomatisLabel">Bayar Otomatis</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= site_url('pembayaran/submit_otomatis/'.$id_berlangganan) ?>" method="post">
        <div class="modal-body">
            <div class="alert alert-info">
                Pembayaran otomatis akan diproses melalui sistem. Pastikan data sudah benar sebelum melanjutkan.
            </div>
            <div class="form-group mb-3">
                <label>Nominal Pembayaran</label>
                <input type="text" name="nominal" class="form-control" placeholder="Masukkan nominal..." required>
            </div>
            <div class="form-group mb-3">
                <label>Metode Pembayaran</label>
                <select name="metode" class="form-control" required>
                    <option value="">-- Pilih Metode --</option>
                    <option value="midtrans">Midtrans</option>
                    <option value="xendit">Xendit</option>
                    <option value="bank_transfer">Transfer Bank</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">Proses Pembayaran</button>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
