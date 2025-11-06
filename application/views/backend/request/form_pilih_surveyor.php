<div class="container my-4">
    
    
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-map-marker-alt me-2"></i> Penunjukan Surveyor
            </h5>
        </div>
        
        <div class="card-body p-4">
            
            <h4 class="text-secondary mb-3">
                Request ID: #<?= $survei->id_request ?>
            </h4>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-dark">Nama Pelanggan:</label>
                    <p class="text-muted"><?= htmlspecialchars($survei->nama_customer) ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-dark">Paket Layanan:</label>
                    <p class="text-muted"><?= htmlspecialchars($survei->nama_paket) ?></p>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold text-dark">Tanggal Request Survei:</label>
                    <p class="text-muted">
                        <i class="far fa-calendar-alt me-1"></i> 
                        <?= date('d F Y', strtotime($survei->tgl_survei)) ?>
                    </p>
                </div>
            </div>

            <hr class="my-4">
            
            <form action="<?= site_url('request/proses_pilih_surveyor') ?>" method="POST">
                
                <input type="hidden" name="id_survei" value="<?= $survei->id_survei ?>">
                
                <div class="mb-4">
                    <label for="id_employee" class="form-label fw-bold text-primary">Pilih Staf Surveyor</label>
                    <select class="form-control surveyor-select form-control-lg" id="id_employee" name="id_employee" required>
                        <option value="">-- Pilih Surveyor yang akan ditugaskan --</option>
                        <?php if (count($surveyor_list) > 0): ?>
                            <?php foreach($surveyor_list as $s): ?>
                                <option value="<?= $s->id_employee ?>">
                                    <?= htmlspecialchars($s->nama_lengkap) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled class="text-danger">❌ Tidak ada Staf Surveyor yang memenuhi kriteria (Role: surveyor & Jabatan: Staff Surveyor).</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="mt-5 d-flex justify-content-end">
                    <a href="<?= site_url('request/survei_index') ?>" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-user-check me-1"></i> Tugaskan Surveyor
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">