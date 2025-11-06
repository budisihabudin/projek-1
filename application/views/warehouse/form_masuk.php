<div class="main-panel">
  <div class="content">
    <div class="container my-4">
      <h3 class="mb-4"><?= $title ?></h3>

      <form method="post" action="<?= $action; ?>">
        <div class="mb-3">
          <label>Nama Barang</label>
          <input type="text" name="nama_barang" class="form-control" required
                 value="<?= $barang->nama_barang ?? set_value('nama_barang'); ?>">
        </div>

        <div class="mb-3">
          <label>Jumlah</label>
          <input type="number" name="jumlah" class="form-control" required
                 value="<?= $barang->jumlah ?? set_value('jumlah'); ?>">
        </div>

        <div class="mb-3">
          <label>Tanggal Masuk</label>
          <input type="date" name="tanggal_masuk" class="form-control" required
                 value="<?= $barang->tanggal_masuk ?? set_value('tanggal_masuk'); ?>">
        </div>

        <div class="mb-3">
          <label>Keterangan</label>
          <textarea name="keterangan" class="form-control"><?= $barang->keterangan ?? set_value('keterangan'); ?></textarea>
        </div>

        <div class="d-flex gap-2 mt-3">
          <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
          <a href="<?= site_url('warehouse/masuk'); ?>" class="btn btn-secondary"><i class="fa fa-times"></i> Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>
