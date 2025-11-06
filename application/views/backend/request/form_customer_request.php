<div class="container my-4">
  <div class="card shadow-sm border-0">
    <div class="card-header bg-light">
      <h5 class="mb-0"><?= $title; ?></h5>
    </div>
    <div class="card-body">

      <!-- 🔹 Informasi Paket yang Dipilih -->
      <?php if (!empty($paket)): ?>
        <div class="alert alert-info">
          <h6 class="mb-1"><strong>Paket Dipilih:</strong> <?= $paket->nama_paket; ?></h6>
          <p class="mb-1"><?= $paket->deskripsi; ?></p>
          <ul class="mb-0">
            <li><strong>Kecepatan:</strong> <?= $paket->kecepatan; ?> Mbps</li>
            <li><strong>Harga:</strong> Rp <?= number_format($paket->harga, 0, ',', '.'); ?></li>
            <li><strong>Status:</strong> <?= ucfirst($paket->status); ?></li>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post" action="<?= site_url('subscriptions/tambah_request'); ?>">

        <input type="hidden" name="id_paket" value="<?= $paket->id_paket; ?>">

        <div class="mb-3">
          <label>Nama Pelanggan</label>
          <input type="text" name="nama_pelanggan" class="form-control" placeholder="Masukkan nama pelanggan" required>
        </div>

        <div class="mb-3">
          <label>Alamat</label>
          <textarea name="alamat" class="form-control" placeholder="Masukkan alamat lengkap" required></textarea>
        </div>

        <div class="mb-3">
          <label>No HP</label>
          <input type="text" name="no_hp" class="form-control" placeholder="0812xxxxxxx" required>
        </div>

        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" placeholder="example@email.com">
        </div>

        <div class="mb-3">
          <label>Instansi</label>
          <input type="text" name="instansi" class="form-control" placeholder="Nama instansi / perusahaan">
        </div>

        <div class="mb-3">
          <label>PIC (Penanggung Jawab)</label>
          <input type="text" name="pic" class="form-control" placeholder="Nama PIC">
        </div>

        <!-- 🔹 Dropdown Sales -->
        <div class="mb-3">
          <label>Sales</label>
          <select name="id_sales" class="form-control sales-select" required>
            <option value="">-- Pilih Sales --</option>
            <?php foreach ($sales as $s): ?>
              <option value="<?= $s->id_employee; ?>">
                <?= $s->nama_lengkap; ?> - <?= $s->telepon; ?> - <?= $s->email; ?> - <?= $s->alamat; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label>Tanggal Mulai</label>
          <input type="date" name="tgl_mulai" class="form-control" required>
        </div>

        <div class="mb-3">
          <label>Lama Langganan (bulan)</label>
          <input type="number" name="lama_bulan" min="1" class="form-control" placeholder="1" required>
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-primary">
            <i class="fa fa-save"></i> Simpan Request
          </button>
        </div>

      </form>
    </div>
  </div>
</div>
