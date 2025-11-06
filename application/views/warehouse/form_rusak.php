<div class="main-panel">
    <div class="content">
        <div class="container my-4">
            <h3 class="text-center mb-4 display-6 fw-bold"><?= $title ?></h3>

            <!-- Notifikasi Error -->
            <?php if(validation_errors()): ?>
                <div class="alert alert-danger"><?= validation_errors() ?></div>
            <?php endif; ?>

            <form action="<?= $action ?>" method="post">
                <div class="mb-3">
                    <label for="id_warehouse_masuk" class="form-label">Nama Barang</label>
                    <select name="id_warehouse_masuk" id="id_warehouse_masuk" class="form-select selectpicker" data-live-search="true" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach($barang_masuk as $bm): ?>
                            <option value="<?= $bm->id_warehouse_masuk ?>"
                                <?= (isset($barang_rusak) && $barang_rusak->id_warehouse_masuk == $bm->id_warehouse_masuk) ? 'selected' : '' ?>>
                                <?= $bm->nama_barang ?> (Stok: <?= $bm->jumlah ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah Rusak</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" 
                           value="<?= isset($barang_rusak) ? $barang_rusak->jumlah : '' ?>" required>
                </div>

                <div class="mb-3">
                    <label for="tanggal_rusak" class="form-label">Tanggal Rusak</label>
                    <input type="date" name="tanggal_rusak" id="tanggal_rusak" class="form-control"
                           value="<?= isset($barang_rusak) ? $barang_rusak->tanggal_rusak : date('Y-m-d') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" class="form-control"><?= isset($barang_rusak) ? $barang_rusak->keterangan : '' ?></textarea>
                </div>

                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="<?= site_url('warehouse/rusak') ?>" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
