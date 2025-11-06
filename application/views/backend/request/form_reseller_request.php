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

.customer-info-card {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 15px;
  color: white;
  padding: 1.5rem;
  margin-top: 1rem;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  animation: slideInUp 0.5s ease;
}

@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.info-item {
  background: rgba(255,255,255,0.1);
  border-radius: 8px;
  padding: 0.75rem;
  margin-bottom: 0.5rem;
  backdrop-filter: blur(10px);
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

.date-input-wrapper {
  position: relative;
}

.date-input-wrapper::before {
  content: "📅";
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
}

.duration-input-wrapper {
  position: relative;
}

.duration-input-wrapper::before {
  content: "⏰";
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
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
</style>

<div class="container my-4">
  <div class="card main-card">
    <!-- Modern Header -->
    <div class="header-gradient">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i><?= $title; ?></h4>
          <p class="mb-0 opacity-75 mt-2">Ajukan pemasangan untuk customer terdaftar</p>
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

      <form method="post" action="<?= site_url('subscriptions/tambah_request'); ?>">
        <input type="hidden" name="id_paket" value="<?= $paket->id_paket; ?>">

        <div class="row">
          <!-- 🔹 Customer Selection dengan Modern Design -->
          <div class="col-md-6">
            <div class="form-floating-custom">
              <select name="id_customer" id="id_customer" class="form-control" required>
                <option value="">Pilih Customer...</option>
                <?php foreach ($customers as $customer): ?>
                  <option value="<?= $customer->id_customer; ?>">
                    <?= $customer->kode_customer; ?> - <?= $customer->nama; ?> (<?= $customer->no_hp; ?>)
                  </option>
                <?php endforeach; ?>
              </select>
              <label for="id_customer">
                <i class="fas fa-users me-2"></i>Pilih Customer
              </label>
            </div>

            <!-- 🔹 Customer Info Display dengan Animasi -->
            <div id="customer_info" class="customer-info-card" style="display: none;">
              <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>Informasi Customer</h6>
              <div class="info-item">
                <small class="opacity-75">Kode Customer</small>
                <div class="fw-bold" id="info_kode"></div>
              </div>
              <div class="info-item">
                <small class="opacity-75">Nama Lengkap</small>
                <div class="fw-bold" id="info_nama"></div>
              </div>
              <div class="info-item">
                <small class="opacity-75">Kontak</small>
                <div class="fw-bold" id="info_hp"></div>
              </div>
              <div class="info-item">
                <small class="opacity-75">Email</small>
                <div class="fw-bold" id="info_email"></div>
              </div>
              <div class="info-item">
                <small class="opacity-75">Alamat</small>
                <div class="fw-bold" id="info_alamat"></div>
              </div>
            </div>
          </div>

          <!-- 🔹 Sales Selection & Date Inputs -->
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

            <div class="row">
              <div class="col-md-6">
                <div class="form-floating-custom">
                  <div class="date-input-wrapper">
                    <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control" required>
                    <label for="tgl_mulai">
                      <i class="fas fa-calendar-alt me-2"></i>Tanggal Mulai
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating-custom">
                  <div class="duration-input-wrapper">
                    <input type="number" name="lama_bulan" id="lama_bulan" min="1" class="form-control" placeholder="1" required>
                    <label for="lama_bulan">
                      <i class="fas fa-clock me-2"></i>Lama (bulan)
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Quick Duration Buttons -->
            <div class="mt-3">
              <small class="text-muted d-block mb-2">Pilih Durasi Cepat:</small>
              <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDuration(1)">1 Bulan</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDuration(3)">3 Bulan</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDuration(6)">6 Bulan</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDuration(12)">1 Tahun</button>
              </div>
            </div>
          </div>
        </div>

        <div class="text-end mt-4">
          <button type="button" class="btn btn-light btn-lg me-2" onclick="history.back()">
            <i class="fas fa-arrow-left me-2"></i>Batal
          </button>
          <button type="submit" class="btn btn-submit">
            <i class="fas fa-paper-plane me-2"></i>Ajukan Request
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
// Customer data for JavaScript
const customerData = {
  <?php foreach ($customers as $customer): ?>
    <?= $customer->id_customer; ?>: {
      kode: '<?= htmlspecialchars($customer->kode_customer); ?>',
      nama: '<?= htmlspecialchars($customer->nama); ?>',
      hp: '<?= htmlspecialchars($customer->no_hp); ?>',
      email: '<?= htmlspecialchars($customer->email ?? ''); ?>',
      alamat: '<?= htmlspecialchars($customer->alamat ?? ''); ?>'
    },
  <?php endforeach; ?>
};

// Show customer info when selected with animation
document.getElementById('id_customer').addEventListener('change', function() {
  const customerId = this.value;
  const infoDiv = document.getElementById('customer_info');

  if (customerId && customerData[customerId]) {
    const customer = customerData[customerId];

    document.getElementById('info_kode').textContent = customer.kode;
    document.getElementById('info_nama').textContent = customer.nama;
    document.getElementById('info_hp').textContent = customer.hp;
    document.getElementById('info_email').textContent = customer.email || 'Tidak ada';
    document.getElementById('info_alamat').textContent = customer.alamat || 'Tidak ada';

    infoDiv.style.display = 'block';
    infoDiv.style.animation = 'slideInUp 0.5s ease';
  } else {
    infoDiv.style.display = 'none';
  }
});

// Set minimum date to today
const today = new Date().toISOString().split('T')[0];
document.getElementById('tgl_mulai').setAttribute('min', today);

// Quick duration function
function setDuration(months) {
  document.getElementById('lama_bulan').value = months;

  // Add visual feedback
  const input = document.getElementById('lama_bulan');
  input.style.borderColor = '#667eea';
  input.style.backgroundColor = '#f8f9ff';

  setTimeout(() => {
    input.style.borderColor = '';
    input.style.backgroundColor = '';
  }, 1000);
}

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
</script>