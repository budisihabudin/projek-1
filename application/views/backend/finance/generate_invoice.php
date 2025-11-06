<div class="container-fluid px-3 my-3">
  <div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">
        <i class="fa fa-file-invoice me-2"></i>Generate Invoice - <?= $request->nama_customer ?>
      </h5>
      <span class="badge bg-light text-dark">#<?= $request->id_request ?></span>
    </div>
    <div class="card-body">

      <!-- Invoice Information -->
      <div class="row mb-4">
        <div class="col-md-6">
          <h6 class="text-muted mb-2">Informasi Pelanggan</h6>
          <table class="table table-sm">
            <tr>
              <td style="width: 120px"><strong>Nama</strong></td>
              <td><?= $request->nama_customer ?></td>
            </tr>
            <tr>
              <td><strong>Alamat</strong></td>
              <td><?= $request->alamat ?></td>
            </tr>
            <tr>
              <td><strong>No. HP</strong></td>
              <td><?= $request->no_hp ?></td>
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <h6 class="text-muted mb-2">Informasi Berlangganan</h6>
          <table class="table table-sm">
            <tr>
              <td style="width: 120px"><strong>Paket</strong></td>
              <td><?= $request->nama_paket ?></td>
            </tr>
            <tr>
              <td><strong>Harga</strong></td>
              <td>Rp <?= number_format($request->harga, 0, ',', '.') ?></td>
            </tr>
            <tr>
              <td><strong>Periode</strong></td>
              <td><?= $lama_langganan ?> Bulan</td>
            </tr>
          </table>
        </div>
      </div>

      <!-- Invoice Preview -->
      <div class="card bg-light mb-4">
        <div class="card-body">
          <h6 class="text-muted mb-3">Preview Invoice</h6>

          <div class="invoice-preview border p-3 bg-white" style="font-family: Arial, sans-serif;">
            <!-- Invoice Header -->
            <div class="text-center mb-3">
              <h4 class="mb-1">INVOICE</h4>
              <p class="text-muted mb-0">No. INV-<?= date('Ym') ?>-<?= str_pad($request->id_customer, 4, '0', STR_PAD_LEFT) ?></p>
              <small class="text-muted">Tanggal: <?= date('d F Y') ?></small>
            </div>

            <!-- Bill To -->
            <div class="mb-3">
              <strong>Bill To:</strong><br>
              <?= $request->nama_customer ?><br>
              <?= $request->alamat ?><br>
              <?= $request->no_hp ?>
            </div>

            <!-- Invoice Table -->
            <table class="table table-sm table-bordered">
              <thead class="table-light">
                <tr>
                  <th>No</th>
                  <th>Deskripsi</th>
                  <th>Periode</th>
                  <th>Jumlah</th>
                  <th>Harga</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $total = 0;
                $start_date = new DateTime($request->tgl_langganan);
                for ($i = 0; $i < $lama_langganan; $i++):
                  $bulan = $start_date->format('F Y');
                  $start_date->modify('+1 month');
                ?>
                <tr>
                  <td><?= $i + 1 ?></td>
                  <td>Biaya Langganan Internet</td>
                  <td><?= $bulan ?></td>
                  <td>1</td>
                  <td>Rp <?= number_format($request->harga, 0, ',', '.') ?></td>
                  <td>Rp <?= number_format($request->harga, 0, ',', '.') ?></td>
                </tr>
                <?php $total += $request->harga; endfor; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="5" class="text-end">Total:</th>
                  <th>Rp <?= number_format($total, 0, ',', '.') ?></th>
                </tr>
              </tfoot>
            </table>

            <!-- Footer -->
            <div class="text-center mt-3">
              <small class="text-muted">Terima kasih atas kepercayaan Anda</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="row">
        <div class="col-md-6">
          <form method="post" action="<?= site_url('finance/process_generate_invoice/' . $request->id_request) ?>">
            <input type="hidden" name="lama_langganan" value="<?= $lama_langganan ?>">
            <button type="submit" class="btn btn-success btn-lg w-100">
              <i class="fa fa-check-circle me-2"></i>Generate Invoice & Create Berlangganan
            </button>
          </form>
        </div>
        <div class="col-md-6">
          <button type="button" class="btn btn-info btn-lg w-100" onclick="window.print()">
            <i class="fa fa-print me-2"></i>Cetak Invoice
          </button>
        </div>
      </div>

    </div>
  </div>
</div>

<style>
@media print {
  .btn, .card-header, .no-print {
    display: none !important;
  }
  .invoice-preview {
    box-shadow: none !important;
    border: 1px solid #000 !important;
  }
  body {
    font-size: 12px;
  }
}
</style>