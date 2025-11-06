
<div class="container my-4">
  <div class="card shadow-sm border-0">
    <div class="card-header bg-light">
      <h5 class="mb-0"><?= $title; ?></h5>
    </div>
    <div class="card-body">
      <form method="post" action="<?= site_url('request/tambah_request'); ?>">

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

        <div class="mb-3">
          <label>Paket Langganan</label>
         <select name="id_paket" class="form-control paket-select" required>
    <option value="">-- Pilih Paket --</option>
    <?php foreach ($paket as $p): ?>
        <option value="<?= $p->id_paket; ?>"><?= $p->nama_paket; ?></option>
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

 