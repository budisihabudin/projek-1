<div class="px-3">
  <div class="container-fluid">

  

    <!-- Card Utama -->
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm">
          <div class="card-header pt-3 pb-2">
            <h4 class="card-title mb-2 mt-3"><?= $title ?? 'Daftar User Sistem'; ?></h4>
          </div>

          <div class="card-body">

            <!-- Form Pencarian -->
            <form method="get" action="<?= site_url('users'); ?>" class="mb-3">
              <div class="input-group">
                <input type="text" name="keyword" class="form-control"
                       placeholder="Cari username / role..."
                       value="<?= htmlspecialchars($this->input->get('keyword'), ENT_QUOTES, 'UTF-8'); ?>">
                <button class="btn btn-primary" type="submit">
                  <i class="fa fa-search"></i> Cari
                </button>
              </div>
            </form>

            <!-- Tabel User -->
            <div class="table-responsive shadow-sm rounded">
              <table class="table table-bordered table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                  <tr>
                    <th class="text-center">No</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($users)): ?>
                    <?php $no = ($this->uri->segment(3) && is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) + 1 : 1; ?>
                    <?php foreach ($users as $u): ?>
                      <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= htmlspecialchars($u->username, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><span class="badge bg-info text-white"><?= ucfirst($u->role); ?></span></td>
                        <td>
                          <span class="badge bg-<?= $u->status == 'active' ? 'success' : 'secondary'; ?> text-white">
                            <?= ucfirst($u->status); ?>
                          </span>
                        </td>

                        <td class="text-center">
                          <?php if ($u->status == 'active'): ?>
                            <a href="<?= site_url('users/disable/'.$u->id_user); ?>"
                               class="btn btn-sm btn-warning mb-1">
                              <i class="fa fa-ban"></i> Nonaktifkan
                            </a>
                          <?php else: ?>
                            <a href="<?= site_url('users/enable/'.$u->id_user); ?>"
                               class="btn btn-sm btn-success mb-1">
                              <i class="fa fa-check"></i> Aktifkan
                            </a>
                          <?php endif; ?>

                          <a href="<?= site_url('users/delete/'.$u->id_user); ?>"
                             class="btn btn-sm btn-danger mb-1"
                             onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                            <i class="fa fa-trash"></i> Hapus
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" class="text-center text-muted">Tidak ada data user.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
              <?= $this->pagination->create_links(); ?>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<style>
@media (max-width: 768px) {
  table {
    font-size: 14px;
  }
  .btn-sm {
    padding: 4px 8px;
    font-size: 12px;
  }
}
.card {
  border-radius: 12px;
  border: none;
}
.page-title {
  font-weight: 600;
}
</style>
