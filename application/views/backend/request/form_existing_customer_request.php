<style>
.gradient-bg {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 2rem 0;
  margin: -1rem -1rem 2rem -1rem;
  border-radius: 0.75rem 0.75rem 0 0;
}

.paket-card {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  border: none;
  border-radius: 15px;
  color: white;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  transition: transform 0.3s ease;
}

.paket-card:hover {
  transform: translateY(-5px);
}

.customer-profile-card {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 15px;
  color: white;
  padding: 2rem;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.profile-header {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.profile-avatar {
  width: 80px;
  height: 80px;
  background: rgba(255,255,255,0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  font-weight: bold;
  backdrop-filter: blur(10px);
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1rem;
}

.info-item {
  background: rgba(255,255,255,0.1);
  border-radius: 10px;
  padding: 1rem;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.2);
}

.form-floating-custom {
  position: relative;
  margin-bottom: 1.5rem;
}

.form-floating-custom .form-control {
  border-radius: 10px;
  border: 2px solid #e3e6f0;
  padding: 1.75rem 1rem 0.75rem 1rem;
  transition: all 0.3s ease;
  height: auto;
}

.form-floating-custom .form-control:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-floating-custom label {
  position: absolute;
  top: 1rem;
  left: 1rem;
  color: #6c757d;
  transition: all 0.3s ease;
  font-size: 0.875rem;
  font-weight: 500;
}

.form-floating-custom .form-control:focus + label,
.form-floating-custom .form-control:not(:placeholder-shown) + label {
  top: 0.5rem;
  font-size: 0.75rem;
  color: #667eea;
  background: white;
  padding: 0 0.5rem;
  border-radius: 5px;
}

.btn-submit {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  border-radius: 10px;
  padding: 0.75rem 2rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
  transition: all 0.3s ease;
  box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.btn-submit:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
  background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}

.duration-buttons {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.duration-btn {
  background: linear-gradient(135deg, #e3e6f0 0%, #d1d5e0 100%);
  border: none;
  border-radius: 8px;
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  transition: all 0.3s ease;
  cursor: pointer;
}

.duration-btn:hover {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  transform: translateY(-2px);
}

.duration-btn.active {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.main-card {
  border: none;
  border-radius: 15px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.1);
  overflow: hidden;
}

.header-gradient {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 1.5rem;
  color: white;
  border-radius: 15px 15px 0 0;
}

.step-indicator {
  display: flex;
  justify-content: center;
  margin-bottom: 2rem;
}

.step {
  display: flex;
  align-items: center;
  color: #6c757d;
}

.step.active {
  color: #667eea;
  font-weight: bold;
}

.step-number {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background: #e3e6f0;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 0.5rem;
  font-size: 0.875rem;
}

.step.active .step-number {
  background: #667eea;
  color: white;
}

.step-line {
  width: 100px;
  height: 2px;
  background: #e3e6f0;
  margin: 0 1rem;
}

.step.active + .step-line {
  background: #667eea;
}
</style>

<div class="container my-4">
  <div class="card main-card">
    <!-- Modern Header -->
    <div class="header-gradient">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h4 class="mb-0"><i class="fas fa-wifi me-2"></i><?= $title; ?></h4>
          <p class="mb-0 opacity-75 mt-2">Ajukan pemasangan internet untuk lokasi Anda</p>
        </div>
        <div class="col-md-4 text-end">
          <div class="d-inline-block">
            <small class="d-block opacity-75">Estimasi Pengerjaan</small>
            <h6 class="mb-0">3-5 Hari Kerja</h6>
          </div>
        </div>
      </div>
    </div>

    <div class="card-body p-4">
      <!-- Step Indicator -->
      <div class="step-indicator">
        <div class="step active">
          <div class="step-number">1</div>
          <span>Paket Dipilih</span>
        </div>
        <div class="step-line"></div>
        <div class="step active">
          <div class="step-number">2</div>
          <span>Data Customer</span>
        </div>
        <div class="step-line"></div>
        <div class="step">
          <div class="step-number">3</div>
          <span>Konfirmasi</span>
        </div>
      </div>

      <!-- 🔹 Modern Paket Info Card -->
      <?php if (!empty($paket)): ?>
        <div class="paket-card p-4 mb-4">
          <div class="row align-items-center">
            <div class="col-md-8">
              <h5 class="mb-2"><i class="fas fa-wifi me-2"></i><?= $paket->nama_paket; ?></h5>
              <p class="mb-3 opacity-90"><?= $paket->deskripsi; ?></p>
              <div class="d-flex gap-3">
                <span class="badge bg-light text-dark">
                  <i class="fas fa-tachometer-alt me-1"></i> <?= $paket->kecepatan; ?> Mbps
                </span>
                <span class="badge bg-light text-dark">
                  <i class="fas fa-check-circle me-1"></i> <?= ucfirst($paket->status); ?>
                </span>
              </div>
            </div>
            <div class="col-md-4 text-end">
              <h3 class="mb-0">Rp <?= number_format($paket->harga, 0, ',', '.'); ?></h3>
              <small class="opacity-75">per bulan</small>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <!-- 🔹 Customer Profile Card -->
      <?php if (!empty($customer)): ?>
        <div class="customer-profile-card mb-4">
          <div class="profile-header">
            <div class="profile-avatar">
              <?= strtoupper(substr($customer->nama, 0, 1)); ?>
            </div>
            <div>
              <h5 class="mb-1"><?= $customer->nama; ?></h5>
              <p class="mb-0 opacity-75">Kode Customer: <?= $customer->kode_customer; ?></p>
            </div>
          </div>

          <div class="info-grid">
            <div class="info-item">
              <small class="opacity-75 d-block">📞 Kontak</small>
              <div class="fw-bold"><?= $customer->no_hp; ?></div>
            </div>
            <div class="info-item">
              <small class="opacity-75 d-block">✉️ Email</small>
              <div class="fw-bold"><?= $customer->email ?? 'Tidak ada'; ?></div>
            </div>
            <div class="info-item">
              <small class="opacity-75 d-block">📍 Alamat</small>
              <div class="fw-bold"><?= $customer->alamat ?? 'Tidak ada'; ?></div>
            </div>
            <?php if (!empty($customer->instansi)): ?>
            <div class="info-item">
              <small class="opacity-75 d-block">🏢 Instansi</small>
              <div class="fw-bold"><?= $customer->instansi; ?></div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>

      <form method="post" action="<?= site_url('subscriptions/tambah_request'); ?>">
        <input type="hidden" name="id_paket" value="<?= $paket->id_paket; ?>">

        <div class="row">
          <!-- 🔹 Sales Selection -->
          <div class="col-md-6">
            <div class="form-floating-custom">
              <select name="id_sales" id="id_sales" class="form-control" required>
                <option value="">Pilih Sales...</option>
                <?php foreach ($sales as $s): ?>
                  <option value="<?= $s->id_employee; ?>"><?= $s->nama_lengkap; ?></option>
                <?php endforeach; ?>
              </select>
              <label for="id_sales">
                <i class="fas fa-user-tie me-2"></i>Sales
              </label>
            </div>

            <div class="mt-4">
              <h6 class="mb-3"><i class="fas fa-users me-2"></i>Pilih Sales Terdekat</h6>
              <div class="row">
                <?php foreach (array_slice($sales, 0, 3) as $s): ?>
                <div class="col-md-12 mb-3">
                  <div class="card border-0 shadow-sm">
                    <div class="card-body">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="id_sales" value="<?= $s->id_employee; ?>" id="sales_<?= $s->id_employee; ?>">
                        <label class="form-check-label" for="sales_<?= $s->id_employee; ?>">
                          <strong><?= $s->nama_lengkap; ?></strong><br>
                          <small class="text-muted">
                            <i class="fas fa-phone me-1"></i><?= $s->telepon; ?>
                            <i class="fas fa-envelope ms-2 me-1"></i><?= $s->email; ?>
                          </small>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <!-- 🔹 Date and Duration Inputs -->
          <div class="col-md-6">
            <div class="form-floating-custom">
              <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control" required>
              <label for="tgl_mulai">
                <i class="fas fa-calendar-alt me-2"></i>Tanggal Mulai
              </label>
            </div>

            <div class="form-floating-custom">
              <input type="number" name="lama_bulan" id="lama_bulan" min="1" class="form-control" placeholder="1" required>
              <label for="lama_bulan">
                <i class="fas fa-clock me-2"></i>Lama Langganan (bulan)
              </label>
            </div>

            <!-- Quick Duration Buttons -->
            <div class="mt-3">
              <small class="text-muted d-block mb-2">Pilih Durasi Cepat:</small>
              <div class="duration-buttons">
                <button type="button" class="duration-btn" onclick="setDuration(1)">1 Bulan</button>
                <button type="button" class="duration-btn" onclick="setDuration(3)">3 Bulan</button>
                <button type="button" class="duration-btn" onclick="setDuration(6)">6 Bulan</button>
                <button type="button" class="duration-btn" onclick="setDuration(12)">1 Tahun</button>
                <button type="button" class="duration-btn" onclick="setDuration(24)">2 Tahun</button>
              </div>
            </div>

            <!-- Package Summary -->
            <div class="mt-4 p-3 bg-light rounded-lg">
              <h6 class="mb-2"><i class="fas fa-calculator me-2"></i>Estimasi Biaya</h6>
              <div class="d-flex justify-content-between">
                <span>Harga per Bulan:</span>
                <strong>Rp <?= number_format($paket->harga, 0, ',', '.'); ?></strong>
              </div>
              <div class="d-flex justify-content-between mt-1">
                <span>Estimasi Total:</span>
                <strong class="text-primary" id="total_estimate">Rp 0</strong>
              </div>
              <small class="text-muted">*Total akan dihitung berdasarkan durasi yang dipilih</small>
            </div>
          </div>
        </div>

        <div class="text-end mt-4">
          <button type="button" class="btn btn-light btn-lg me-2" onclick="history.back()">
            <i class="fas fa-arrow-left me-2"></i>Kembali
          </button>
          <button type="submit" class="btn btn-submit">
            <i class="fas fa-paper-plane me-2"></i>Ajukan Pemasangan
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
// Set minimum date to today
const today = new Date().toISOString().split('T')[0];
document.getElementById('tgl_mulai').setAttribute('min', today);

// Package price
const packagePrice = <?= $paket->harga ?? 0; ?>;

// Quick duration function
function setDuration(months) {
  document.getElementById('lama_bulan').value = months;

  // Update total estimate
  const total = packagePrice * months;
  document.getElementById('total_estimate').textContent = 'Rp ' + total.toLocaleString('id-ID');

  // Visual feedback for duration buttons
  document.querySelectorAll('.duration-btn').forEach(btn => {
    btn.classList.remove('active');
  });
  event.target.classList.add('active');

  // Highlight the input field
  const input = document.getElementById('lama_bulan');
  input.style.borderColor = '#667eea';
  input.style.backgroundColor = '#f8f9ff';

  setTimeout(() => {
    input.style.borderColor = '';
    input.style.backgroundColor = '';
  }, 1000);
}

// Auto-update total when duration changes
document.getElementById('lama_bulan').addEventListener('input', function() {
  const months = parseInt(this.value) || 0;
  const total = packagePrice * months;
  document.getElementById('total_estimate').textContent = 'Rp ' + total.toLocaleString('id-ID');
});

// Add floating labels behavior
document.querySelectorAll('.form-floating-custom .form-control').forEach(input => {
  if (input.value) {
    input.classList.add('has-value');
  }

  input.addEventListener('blur', function() {
    if (this.value) {
      this.classList.add('has-value');
    } else {
      this.classList.remove('has-value');
    }
  });
});

// Initialize with default duration
setDuration(1);
</script>