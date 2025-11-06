<div class="main-panel">
    <div class="content">
        <div class="container my-4">
            <h3 class="text-center mb-4 display-6 fw-bold">Daftar Paket Internet</h3>

            <!-- Form Pencarian -->
            <form method="get" action="<?= site_url('dashboard') ?>" class="mb-3">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari paket..." value="<?= htmlspecialchars($keyword ?? '', ENT_QUOTES) ?>">
                    <button class="btn btn-primary">Cari</button>
                </div>
            </form>

            <?php if (!empty($paket)): ?>
                <div class="d-flex overflow-auto gap-3 py-2">
                    <?php foreach ($paket as $p): ?>
                        <div class="card shadow-sm gradient-card flex-shrink-0 paket-card">
                            <div class="card-body text-center">
                                <h5 class="card-title mb-2 text-white"><?= htmlspecialchars($p->nama_paket, ENT_QUOTES, 'UTF-8') ?></h5>
                                <p class="card-text text-light"><?= htmlspecialchars($p->deskripsi, ENT_QUOTES, 'UTF-8') ?></p>

                                <ul class="list-unstyled mb-3 text-start text-white">
                                    <li><strong>Harga:</strong> Rp<?= number_format($p->harga, 0, ',', '.') ?></li>
                                    <li><strong>Kecepatan:</strong> <?= $p->kecepatan ?> Mbps</li>
                                    <li>
                                        <strong>Status:</strong>
                                        <span class="badge <?= ($p->status == 'aktif') ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= ucfirst($p->status) ?>
                                        </span>
                                    </li>
                                </ul>

                                <?php if ($p->status == 'aktif'): ?>
                                    <a href="<?= site_url('subscriptions/add/'.$p->id_paket); ?>" class="btn btn-primary btn-sm paket-btn">
                                        <i class="fas fa-shopping-cart me-1"></i> Langganan
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm w-100" disabled>Tidak Tersedia</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-muted">Belum ada paket yang tersedia.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Scrollbar horizontal */
.overflow-auto::-webkit-scrollbar {
    height: 8px;
}
.overflow-auto::-webkit-scrollbar-thumb {
    background-color: #ccc;
    border-radius: 10px;
}
.overflow-auto::-webkit-scrollbar-thumb:hover {
    background-color: #999;
}

/* Card hover effect */
.card:hover {
    transform: translateY(-5px);
    transition: 0.3s ease;
}

/* Gradasi background card */
.gradient-card {
    background: linear-gradient(135deg, #4e54c8, #8f94fb);
    color: white;
    border: none;
}

/* Ukuran dan jarak card */
.paket-card {
    width: 18rem;
    min-width: 18rem;
    margin-right: 1rem;
}

/* Tombol full-width di card */
.paket-card .btn {
    margin-top: 0.5rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .overflow-auto {
        flex-wrap: wrap !important;
        justify-content: center;
    }
    .paket-card {
        width: 100% !important;
        min-width: auto !important;
        margin-bottom: 1rem;
    }
}
</style>
