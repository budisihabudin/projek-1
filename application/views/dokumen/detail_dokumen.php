<div class="main-panel">
  <div class="content">
    <div class="container my-4">
      <div class="card shadow-sm p-4">
        
        <h3 class="text-center mb-1">Kelola Dokumen Langganan</h3>
        <p class="text-center text-muted mb-4">Histori #<?= $histori->id_histori ?> | Customer: <?= $histori->nama_customer ?></p>


        <h5 class="fw-bold mb-3">Upload Dokumen Baru</h5>
        <div class="p-3 mb-4 border rounded bg-light">
            <?= form_open_multipart('survei/upload_dokumen') ?>
                <input type="hidden" name="id_histori" value="<?= $histori->id_histori ?>">

                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="tipe_dokumen" class="form-label fw-semibold">Jenis Dokumen</label>
                        <select name="tipe_dokumen" id="tipe_dokumen" class="form-control" required>
                            <option value="">-- Pilih Jenis Dokumen --</option>
                            <option value="KTP">KTP</option>
                            <option value="Bukti Transfer">Bukti Transfer</option>
                            <option value="Perjanjian">Dokumen Perjanjian/Kontrak</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="col-md-7 mb-3">
                        <label for="file_dokumen" class="form-label fw-semibold">Pilih File (Max 5MB)</label>
                        <div class="input-group">
                            <input type="file" name="file_dokumen" class="form-control" required>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-upload me-1"></i> Upload
                            </button>
                        </div>
                        <small class="text-muted">Format: PDF, DOCX, JPG, PNG.</small>
                    </div>
                </div>
            <?= form_close() ?>
        </div>

        <hr class="my-4">

        <h5 class="fw-bold mb-3">Daftar Dokumen Diunggah</h5>
        <?php if ($dokumen): ?>
            <ul class="list-group">
                <?php foreach ($dokumen as $doc): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <strong><?= $doc->tipe_dokumen ?></strong> 
                            <small class="d-block text-muted">Diunggah: <?= date('d M Y H:i', strtotime($doc->tgl_upload)) ?></small>
                        </div>
                        
                        <div class="ms-3">
                            <?php 
                                $badge = ['pending' => 'bg-warning text-dark', 'disetujui' => 'bg-success', 'ditolak' => 'bg-danger'];
                                $class = $badge[strtolower($doc->status_approval)] ?? 'bg-secondary';
                            ?>
                            <span class="badge <?= $class ?> me-2"><?= ucfirst($doc->status_approval) ?></span>
                            
                            <a href="<?= base_url('uploads/dokumen_langganan/' . $doc->file_path) ?>" target="_blank" class="btn btn-info btn-sm" title="Lihat File">
                                <i class="fas fa-eye"></i> Lihat
                            </a>
                            
                            </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-info">Belum ada dokumen yang diunggah untuk histori ini.</div>
        <?php endif; ?>

        <div class="text-end mt-4">
            <a href="<?= base_url('survei') ?>" class="btn btn-secondary">
              <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Survei
            </a>

              <a href="<?= base_url('approval_langganan/approve_dokumen/'.$histori->id_histori) ?>" class="btn btn-warning" title="Approve Data">
                    <i class="fas fa-check"></i> <span class="d-none d-sm-inline">Approve Dokumen</span>
                  </a>
        </div>

      </div>
    </div>
  </div>
</div>