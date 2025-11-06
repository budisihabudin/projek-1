<div class="container-fluid px-4 py-4">
  <h3 class="mb-4"><?= $title ?></h3>

  <form method="post" action="<?= site_url('subscriptions/simpan_berlangganan') ?>">
    <input type="hidden" name="id_customer" value="<?= $customer->id_customer ?>">
    <input type="hidden" name="id_paket" value="<?= $paket->id_paket ?>">

    <div class="row">
      <div class="col-md-6">
        <p><strong>Nama:</strong> <?= $customer->nama ?></p>
        <p><strong>Email:</strong> <?= $customer->email ?></p>
        <p><strong>Alamat:</strong> <?= $customer->alamat ?></p>

        <label>Tanggal Mulai</label>
        <input type="date" name="tgl_mulai" class="form-control mb-3" required>

        <label>Lama Langganan (bulan)</label>
        <input type="number" name="lama_langganan" class="form-control mb-3" min="1" required>

        <button class="btn btn-primary mt-2">Kirim Permintaan Berlangganan</button>
      </div>

      <div class="col-md-6">
        <div class="card shadow-sm">
          <!-- <img src="<?//= base_url('uploads/paket/'.$paket->gambar) ?>" class="card-img-top"> -->
          <div class="card-body">
            <h5><?= $paket->nama_paket ?></h5>
            <p><?= $paket->deskripsi ?></p>
            <p class="text-muted">Rp <?= number_format($paket->harga,0,',','.') ?>/bulan</p>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
