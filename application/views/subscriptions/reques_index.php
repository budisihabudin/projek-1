<!-- main content -->
<div class="main-panel">
    <div class="content">
        <div class="container my-4">
            <h3 class="text-center mb-4 display-6 fw-bold"><?= $title; ?></h3>

            <!-- Riwayat Request -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3 fw-bold">Riwayat Request Instalasi</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Paket</th>
                                    <th>Kecepatan</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($histori)): ?>
                                    <?php $no=1; foreach($histori as $h): ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= htmlspecialchars($h->nama_paket, ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= $h->kecepatan; ?> Mbps</td>
                                            <td>Rp <?= number_format($h->harga); ?></td>
                                            <td>
                                                <?php if ($h->status == 'pending'): ?>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                <?php elseif ($h->status == 'approved'): ?>
                                                    <span class="badge bg-success">Approved</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Rejected</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('d-m-Y H:i', strtotime($h->tgl_langganan)); ?></td>
                                            <td>
                                                <?php if ($h->status == 'pending'): ?>
                                                    <a href="<?= base_url('subcriptions/batal_request/'.$h->id_histori); ?>" 
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Batalkan request ini?')">
                                                       <i class="fas fa-times me-1"></i> Batalkan
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Belum ada request yang diajukan.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

 