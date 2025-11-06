<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Beranda - MLM Investasi</title>
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo_transparan.png') ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { background-color: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding-bottom: 70px; }
    .navbar-brand { font-weight: 700; }
    .carousel-item img { height: 350px; object-fit: cover; filter: brightness(70%); }
    .carousel-caption h1 { font-weight: 700; font-size: 2rem; }
    .carousel-caption p { font-size: 1rem; }
    .section { padding: 3rem 0; }

    /* Scroll container */
    .scroll-container {
        display: flex;
        gap: 1rem;
        overflow-x: auto;
        padding: 10px 0;
        align-items: flex-start;
        scrollbar-width: thin;
    }
    .scroll-container::-webkit-scrollbar { height: 8px; }
    .scroll-container::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }

    /* Card Produk & Promo */
    .card-produk, .card-promo {
        min-width: 250px;
        flex: 0 0 auto;
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: transform 0.4s ease, box-shadow 0.4s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        background: #fff;
        cursor: pointer;
        min-height: 350px;
    }
    .card-produk:hover, .card-promo:hover { transform: translateY(-10px); box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2); }
    .card-body { display: flex; flex-direction: column; justify-content: space-between; height: 100%; }
    .card-body h5 { font-weight: 800; color: #495057; letter-spacing: 0.5px; }
    .card-body .price { font-size: 1.2rem; font-weight: 900; color: #28a745; }
    .btn-custom { background: linear-gradient(45deg, #007bff, #0056b3); border: none; color: white; font-weight: 600; }
    .btn-custom:hover { background: linear-gradient(45deg, #0056b3, #007bff); }
    .card-promo img { height: 180px; object-fit: cover; }

    /* Bottom Nav */
    .bottom-nav { position: fixed; bottom: 0; left: 0; width: 100%; background: #fff; border-top: 1px solid #ddd; display: flex; justify-content: space-around; align-items: center; padding: 8px 0; z-index: 9999; }
    .bottom-nav a { color: #6c757d; text-decoration: none; font-size: 0.85rem; display: flex; flex-direction: column; align-items: center; }
    .bottom-nav a i { font-size: 1.3rem; margin-bottom: 3px; }
    .bottom-nav a.active { color: #007bff; font-weight: bold; }

    /* Kontak Section */
    .contact-section { background: #007bff; color: #fff; border-radius: 15px; padding: 2rem; }
    .contact-section h3 { margin-bottom: 1.5rem; }
    .contact-section a { color: #fff; text-decoration: none; }
    .contact-section a:hover { text-decoration: underline; }

    /* Banner */
    .banner-image { max-height: 250px; object-fit: cover; }
    .responsive-text { font-size: 1.5rem; }
    
    /* Responsive */
    @media (max-width: 768px) { 
      .banner-image { max-height: 180px; } 
      .responsive-text { font-size: 1rem; padding: 0 10px; } 
      .carousel-item img { height: 200px; } 
      .carousel-caption h1 { font-size: 1.2rem; } 
      .carousel-caption p { font-size: 0.9rem; } 
      h3.display-6 { font-size: 1.4rem; } 
      .card-produk, .card-promo { min-width: 220px; } 
      .card-body h5 { font-size: 1rem; } 
      .card-body .price { font-size: 1rem; } 
    }
    @media (max-width: 576px) { 
      .banner-image { max-height: 150px; } 
      .responsive-text { font-size: 0.9rem; line-height: 1.4; padding: 0 15px; } 
    }
    @media (max-width: 480px) { 
      .carousel-caption h1 { font-size: 1rem; } 
      .carousel-caption p { font-size: 0.8rem; } 
      .card-produk, .card-promo { min-width: 180px; } 
      .btn { font-size: 0.8rem; padding: 5px 10px; } 
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="<?= base_url('assets/img/logo_transparan.png') ?>" alt="Logo MLM" height="40" class="me-2">
      RICH KINGDOM ID
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (!$this->session->userdata('logged_in')): ?>
          <li class="nav-item"><a class="nav-link" href="<?= site_url('auth/login') ?>"><i class="fas fa-sign-in-alt me-1"></i> Login</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= site_url('auth/register') ?>"><i class="fas fa-user-plus me-1"></i> Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Banner Section -->
<div class="container-fluid p-0">
  <div class="position-relative">
    <img src="<?= base_url('assets/gt.jpg'); ?>" class="d-block w-100 banner-image" alt="Banner Utama">
    <div class="position-absolute top-50 start-50 translate-middle text-center text-white">
      <p class="lead responsive-text">Welcome to RICH KINGDOM ID</p>
    </div>
  </div>
</div>

<!-- Tentang Kami Section -->
<div class="container section about-section bg-white rounded-3 shadow-sm mt-4">
 <h3 class="text-center mb-4 display-6 fw-bold text-secondary">Tentang Perusahaan Kami</h3>
 <div class="row align-items-center">
  <div class="col-md-6 text-center mt-3 mt-md-0">
     <img src="<?= base_url('assets/g.jpg'); ?>" alt="Tim Kami" class="img-fluid rounded-3">
   </div>
   <div class="col-md-6">
     <?php if (!empty($tentang)): ?>
        <?php foreach($tentang as $t): ?>
          <p class="lead" style="margin: 10px; text-align: justify;"><?= $t->isi_tentang; ?></p>
        <?php endforeach; ?>
     <?php else: ?>
        <p class="lead" style="margin: 10px;">Belum ada informasi tentang perusahaan.</p>
     <?php endif; ?>
   </div>
 </div>
</div>

<!-- Produk Section -->
<div class="container section product-section">
  <h3 class="text-center mb-4 display-6 fw-bold">Pilihan Produk Terbaik Kami</h3>
  <div class="scroll-container" id="produkContainer">
    <?php foreach($produk as $p): ?>
      <?php 
        $createdAt = new DateTime($p->created_at);
        $expiredAt = clone $createdAt;
        $expiredAt->modify("+{$p->durasi} days");
        $today = new DateTime();
        if ($today > $expiredAt || $p->status == 0) continue;
        $persen = ($p->persen < 1) ? $p->persen : $p->persen / 100;
        $profitHarian = $p->harga * $persen / $p->durasi;
      ?>
      <div class="card card-produk" 
       data-nama="<?= htmlspecialchars($p->nama_produk, ENT_QUOTES) ?>" 
       data-harga="Rp<?= number_format($p->harga, 0, ',', '.') ?>" 
       data-profit="Rp<?= number_format($profitHarian, 0, ',', '.') ?>"
       data-total-profit="Rp<?= number_format($profitHarian * $p->durasi, 0, ',', '.') ?>"
       data-durasi="<?= $p->durasi ?> Hari"
       data-deskripsi="<?= htmlspecialchars($p->deskripsi, ENT_QUOTES) ?>">

        <div class="card-body text-center">
          <h5 class="card-title"><?= $p->nama_produk ?></h5>
          <p class="text-muted"><?= $p->deskripsi ?></p>
          <p class="mb-0">Harga: <strong>Rp<?= number_format($p->harga, 0, ',', '.') ?></strong></p>
          <p class="mb-0 text-success">Profit per Hari: <strong>Rp<?= number_format($profitHarian, 0, ',', '.') ?></strong></p>
          <p class="mb-0 text-success">Total Profit: <strong>Rp<?= number_format($profitHarian*$p->durasi, 0, ',', '.') ?></strong></p>
          <p class="mb-0">Kadaluarsa: <strong><?= $p->durasi ?> Hari</strong></p>
          <button class="btn btn-sm btn-outline-primary mt-2 btn-preview">Preview</button>
          <?php if ($this->session->userdata('phone')!= null){ ?>
              <a href="<?= site_url('transaksi') ?>" class="btn btn-primary mt-2 btn-sm"><i class="fas fa-shopping-cart me-1"></i> Beli</a>
          <?php }else{ ?>
              <a href="<?= site_url('auth/login') ?>" class="btn btn-primary mt-2 btn-sm"><i class="fas fa-shopping-cart me-1"></i> Beli</a>
          <?php } ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Promo Section -->
<div class="container section promo-section">
  <h3 class="text-center mb-4 display-6 fw-bold text-primary">Promo Spesial</h3>
  <div class="scroll-container" id="promoContainer">
    <?php if (!empty($promo)): ?>
      <?php foreach ($promo as $pr): ?>
        <div class="card card-promo" data-judul="<?= $pr->judul ?>" data-deskripsi="<?= $pr->deskripsi ?>" data-gambar="<?= base_url($pr->gambar) ?>">
          <img src="<?= base_url($pr->gambar) ?>" class="card-img-top" alt="<?= $pr->judul ?>">
          <div class="card-body text-center">
            <h5 class="card-title"><?= $pr->judul ?></h5>
            <p class="text-muted"><?= $pr->deskripsi ?></p>
            <button class="btn btn-sm btn-outline-primary mt-2 btn-preview-promo">Preview</button>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-muted">Belum ada promo tersedia.</p>
    <?php endif; ?>
  </div>
</div>

<!-- Kontak Kami Section -->
<div class="container section contact-section">
  <h3 class="text-center">Kontak Kami</h3>
  <p class="text-center mb-2">Punya pertanyaan? Hubungi tim kami melalui:</p>
  <div class="row justify-content-center text-center">
    <?php if (!empty($tentang)): ?>
      <?php $k = $tentang[0]; ?>
      <div class="col-md-3 mb-3">
        <i class="fas fa-phone fa-2x mb-2"></i>
        <a href="https://wa.me/<?= $k->telepon ?>?text=Halo%20saya%20tertarik%20dengan%20informasi%20Anda" target="_blank">Chat via WhatsApp</a>
      </div>
      <div class="col-md-3 mb-3">
        <i class="fas fa-envelope fa-2x mb-2"></i>
        <p><a href="mailto:<?= $k->email ?>"><?= $k->email ?></a></p>
      </div>
      <div class="col-md-3 mb-3">
        <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
        <p><?= $k->alamat ?></p>
      </div>
    <?php else: ?>
      <div class="col-12">
        <p>Belum ada data kontak tersedia.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Modal Preview Produk -->
<div class="modal fade" id="previewProdukModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <h4 id="previewProdukNama" class="mb-3"></h4>
        <p><strong>Harga:</strong> <span id="previewProdukHarga"></span></p>
        <p><strong>Profit per Hari:</strong> <span id="previewProdukProfit"></span></p>
        <p><strong>Total Profit:</strong> <span id="previewProdukTotalProfit"></span></p>
        <p><strong>Kadaluarsa:</strong> <span id="previewProdukDurasi"></span></p>
      </div>

    </div>
  </div>
</div>

<!-- Modal Preview Promo -->
<div class="modal fade" id="previewPromoModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Promo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <img id="previewPromoGambar" src="" alt="Promo" class="img-fluid rounded mb-3">
        <h4 id="previewPromoJudul"></h4>
        <p id="previewPromoDeskripsi"></p>
      </div>
    </div>
  </div>
</div>

<!-- Bottom Navigation -->
<div class="bottom-nav">
    <a href="<?= site_url('home') ?>" class="<?= ($this->uri->segment(1) == 'home') ? 'active' : '' ?>"><i class="fas fa-home"></i><span>Beranda</span></a>
    <?php if ($this->session->userdata('logged_in')): ?>
        <a href="<?= site_url('topup') ?>" class="<?= ($this->uri->segment(1) == 'topup') ? 'active' : '' ?>"><i class="fas fa-wallet"></i><span>Topup</span></a>
        <a href="<?= site_url('dashboard/produk') ?>" class="<?= ($this->uri->segment(1) == 'dashboard' && $this->uri->segment(2) == 'produk') ? 'active' : '' ?>"><i class="fas fa-coins"></i><span>Invest</span></a>
        <a href="<?= site_url('transaksi/riwayat') ?>" class="<?= ($this->uri->segment(1) == 'transaksi' && $this->uri->segment(2) == 'riwayat') ? 'active' : '' ?>"><i class="fas fa-history"></i><span>Riwayat</span></a>
        <a href="<?= site_url('bonus') ?>" class="<?= ($this->uri->segment(1) == 'bonus') ? 'active' : '' ?>"><i class="fas fa-gift"></i><span>Bonus</span></a>
        <a href="<?= site_url('referal') ?>" class="<?= ($this->uri->segment(1) == 'referal') ? 'active' : '' ?>"><i class="fas fa-share-alt"></i><span>Referal</span></a>
        <a href="<?= site_url('member/tim') ?>" class="<?= ($this->uri->segment(2) == 'tim') ? 'active' : '' ?>"><i class="fas fa-users"></i><span>Tim</span></a>
        <a href="<?= site_url('member/profit') ?>" class="<?= ($this->uri->segment(2) == 'profit') ? 'active' : '' ?>">
            <i class="fas fa-sack-dollar"></i><span>Profit</span>
        </a>
        <a href="<?= site_url('member/profile') ?>" class="<?= ($this->uri->segment(2) == 'profile') ? 'active' : '' ?>"><i class="fas fa-user"></i><span>Akun</span></a>
    <?php else: ?>
        <a href="<?= site_url('home/produk') ?>" class="<?= ($this->uri->segment(1) == 'home' && $this->uri->segment(2) == 'produk') ? 'active' : '' ?>"><i class="fas fa-coins"></i><span>Invest</span></a>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Preview Produk
document.querySelectorAll('.btn-preview').forEach(btn => {
  btn.addEventListener('click', function() {
    const card = this.closest('.card-produk');
    document.getElementById('previewProdukNama').textContent = card.dataset.nama;
    document.getElementById('previewProdukHarga').textContent = card.dataset.harga;
    document.getElementById('previewProdukProfit').textContent = card.dataset.profit;
    document.getElementById('previewProdukTotalProfit').textContent = card.dataset.totalProfit; // Tambahan
    document.getElementById('previewProdukDurasi').textContent = card.dataset.durasi;
    new bootstrap.Modal(document.getElementById('previewProdukModal')).show();
  });
});


  // Preview Promo
  document.querySelectorAll('.btn-preview-promo').forEach(btn => {
    btn.addEventListener('click', function() {
      const card = this.closest('.card-promo');
      document.getElementById('previewPromoJudul').textContent = card.dataset.judul;
      document.getElementById('previewPromoDeskripsi').textContent = card.dataset.deskripsi;
      document.getElementById('previewPromoGambar').src = card.dataset.gambar;
      new bootstrap.Modal(document.getElementById('previewPromoModal')).show();
    });
  });
</script>
</body>
</html>
