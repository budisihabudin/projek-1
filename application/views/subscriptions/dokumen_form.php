<div class="container mt-4">
    <h4><?= $title; ?></h4>
    <form method="post">
        <div class="form-group mb-2">
            <label>Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" class="form-control" value="<?= isset($dokumen) ? $dokumen->nama_pelanggan : '' ?>" required>
        </div>
        <div class="form-group mb-2">
            <label>Jenis Dokumen</label>
            <input type="text" name="jenis_dokumen" class="form-control" value="<?= isset($dokumen) ? $dokumen->jenis_dokumen : '' ?>" required>
        </div>
        <div class="form-group mb-2">
            <label>Nomor Dokumen</label>
            <input type="text" name="nomor_dokumen" class="form-control" value="<?= isset($dokumen) ? $dokumen->nomor_dokumen : '' ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="<?= base_url('subcriptions/dokumen'); ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>
