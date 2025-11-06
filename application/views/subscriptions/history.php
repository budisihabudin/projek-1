<div class="container-fluid px-3 py-4">
  <h3 class="mb-4"><?= $title ?? 'Riwayat Langganan'; ?></h3>

  <!-- Form Pencarian -->
  <div class="row mb-3 g-2">
    <div class="col-12 col-md">
      <form method="get" action="<?= site_url('subscriptions/history'); ?>" class="d-flex">
        <input type="text" name="keyword" 
               class="form-control me-2" 
               placeholder="Cari nama / kode customer..." 
               value="<?= htmlspecialchars($keyword ?? '', ENT_QUOTES); ?>">
        <button class="btn btn-secondary" type="submit">
          <i class="fa fa-search"></i>
        </button>
      </form>
    </div>
  </div>

  <!-- Tabel Riwayat -->
  <div class="table-responsive shadow-sm rounded">
    <table class="table table-bordered table-striped align-middle mb-0">
      <thead class="table-dark text-center">
        <tr>
          <th>No</th>
          <th>Kode Customer</th>
          <th>Nama</th>
          <th>Alamat</th>
          <th>Paket</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($riwayat)): ?>
          <?php $no = 1 + ($this->input->get('page') ?? 0); ?>
          <?php foreach ($riwayat as $r): ?>
            <tr>
              <td class="text-center"><?= $no++; ?></td>
              <td><?= $r->kode_customer; ?></td>
              <td><?= $r->nama; ?></td>
              <td><?= $r->alamat; ?></td>
              <td><?= $r->id_paket ?? '-'; ?></td>
              <td class="text-center">
                <span class="badge 
                  <?= $r->status == 'aktif' ? 'bg-success' : 
                     ($r->status == 'pending' ? 'bg-warning text-dark' : 
                     ($r->status == 'nonaktif' ? 'bg-danger' : 'bg-secondary')); ?>">
                  <?= ucfirst($r->status); ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="text-center">Tidak ada data riwayat langganan.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="mt-3 d-flex justify-content-center">
    <?= $pagination; ?>
  </div>
</div>

<!-- Responsif di HP -->
<style>
@media (max-width: 768px) {
  .table-responsive {
    font-size: 14px;
  }
  h3 {
    font-size: 18px;
  }
  .btn {
    font-size: 14px;
    padding: 6px 10px;
  }
  .form-control {
    font-size: 14px;
  }
}
</style>
