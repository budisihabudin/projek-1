<div class="container-fluid px-3">
    <h3 class="mb-3"><?= $title; ?></h3>

    <form method="post" action="<?= site_url('request/simpan_dokumen_customer'); ?>" enctype="multipart/form-data">

         <div class="mb-4">
                    <label for="id_customer" class="form-label fw-bold text-primary">Pilih Customer</label>
                    <select class="form-control customer-select form-control-lg" id="id_customer" name="id_customer" required>
                        <option value="">-- Pilih Customer yang akan ditugaskan --</option>
                        <?php if (count($customer_done_survei_reques) > 0): ?>
                            <?php foreach($customer_done_survei_reques as $c): ?>
                                <option value="<?= $c->id_customer ?>">
                                    <?= htmlspecialchars($c->nama_customer) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled class="text-danger">❌ Tidak ada customer yang memenuhi kriteria.</option>
                        <?php endif; ?>
                    </select>
                </div>

        <div class="mb-3">
            <label for="foto_customer" class="form-label">Pilih Dokumen</label>
            <input type="file" name="foto_customer[]" multiple class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="keterangan_foto" class="form-label">Keterangan Dokumen</label>
            <textarea name="keterangan_foto[]" class="form-control" placeholder="Isi keterangan dokumen..." required></textarea>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button>
        <a href="<?= site_url('request/dokumen_customer'); ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>
