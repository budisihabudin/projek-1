<div class="container-fluid px-3">
    <h3 class="mb-3"><?= $title ?? 'Edit Dokumen Customer'; ?></h3>

    <form method="post" action="<?= site_url('request/update_dokumen_customer/'.$dokumen->id_dokumen_customer); ?>" enctype="multipart/form-data">
        
        <!-- Hidden field untuk id customer -->
        <input type="hidden" name="id_customer" value="<?= $dokumen->id_customer; ?>">

        <!-- Customer (readonly) -->
        <div class="mb-4">
            <label class="form-label fw-bold text-primary">Customer</label>
            <input type="text" class="form-control form-control-lg" value="<?= htmlspecialchars($dokumen->nama_customer ?? ''); ?>" readonly>
        </div>

        <!-- Upload file dokumen -->
        <div class="mb-3">
            <label for="foto_customer" class="form-label">Ganti Dokumen (Opsional)</label>
            <input type="file" name="foto_customer" class="form-control">
            <?php if($dokumen->foto_customer): ?>
                <small>File saat ini: 
                    <a href="<?= base_url('uploads/customer/'.$dokumen->foto_customer) ?>" target="_blank">
                        <?= $dokumen->foto_customer ?>
                    </a>
                </small>
            <?php endif; ?>
        </div>

        <!-- Keterangan dokumen -->
        <div class="mb-3">
            <label for="keterangan_foto" class="form-label">Keterangan Dokumen</label>
            <textarea name="keterangan_foto" class="form-control" placeholder="Isi keterangan dokumen..."><?= htmlspecialchars($dokumen->keterangan_foto ?? ''); ?></textarea>
        </div>

        <!-- Tombol aksi -->
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Perubahan</button>
        <a href="<?= site_url('request/dokumen_customer'); ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>
