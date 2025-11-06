<!-- main content -->
<div class="main-panel">
  <div class="content">
    <div class="page-inner mt--5">

      <!-- Card: Form Tambah / Edit User -->
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header pt-3 pb-2">
              <h4 class="card-title mb-2 mt-3"><?= $title ?? 'Tambah User'; ?></h4>
            </div>

            <div class="card-body">
              <form method="post" action="<?= $action; ?>">

                <!-- Data Login -->
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required 
                           placeholder="Masukkan username" value="<?= $user->username ?? ''; ?>">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>Password <?= isset($user) ? '(isi jika ingin ganti)' : ''; ?></label>
                    <input type="password" name="password" class="form-control" 
                           placeholder="Masukkan password" <?= isset($user) ? '' : 'required'; ?>>
                  </div>
                </div>

                <!-- Role -->
                <div class="mb-3">
                  <label>Role</label>
                  <select name="role" class="form-control" required>
                    <option value="">-- Pilih Role --</option>
                    <?php 
                    $roles = ['admin','pelanggan','sales','finance','teknisi','surveyor','hrd'];
                    foreach($roles as $role_option): ?>
                      <option value="<?= $role_option ?>" 
                        <?= (isset($user) && $user->role == $role_option) ? 'selected' : ''; ?>>
                        <?= ucfirst($role_option); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <!-- Status -->
                <div class="mb-3">
                  <label>Status</label>
                  <select name="status" class="form-control" required>
                    <option value="active" <?= (isset($user) && $user->status == 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="nonaktif" <?= (isset($user) && $user->status == 'nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
                  </select>
                </div>

                <!-- Tombol -->
                <div class="d-flex gap-2 mt-3">
                  <button type="submit" class="btn btn-success">Simpan</button>
                  <a href="<?= site_url('users'); ?>" class="btn btn-secondary">Kembali</a>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- end card -->

    </div>
  </div>
</div>
<!-- end main content -->
