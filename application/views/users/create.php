<div class="main-panel">
  <div class="content">
    <div class="page-inner mt--5">

      <!-- Card: Tambah / Edit User -->
      <div class="row">
        <div class="col-6 mx-auto">
          <div class="card shadow-sm">
            <div class="card-header pt-3 pb-2">
              <h4 class="card-title mb-2 mt-3"><?= $title ?? 'Tambah User'; ?></h4>
            </div>

            <div class="card-body">

              <?= validation_errors('<div class="alert alert-danger">','</div>'); ?>

              <form action="<?= $action ?? site_url('users/store'); ?>" method="post">

                <!-- Username & Password -->
                <div class="mb-3">
                  <label class="form-label">Username</label>
                  <input type="text" name="username" class="form-control" required
                         value="<?= set_value('username', $user->username ?? ''); ?>"
                         placeholder="Masukkan username">
                </div>

                <div class="mb-3">
                  <label class="form-label">Password <?= isset($user) ? '(isi jika ingin ganti)' : ''; ?></label>
                  <input type="password" name="password" class="form-control"
                         placeholder="Masukkan password" <?= isset($user) ? '' : 'required'; ?>>
                </div>

                <!-- Role -->
                <div class="mb-3">
                  <label class="form-label">Role</label>
                  <select name="role" class="form-control" required>
                    <option value="">-- Pilih Role --</option>
                    <?php 
                    $roles = ['admin','pelanggan','sales','finance','teknisi','surveyor','hrd'];
                    foreach($roles as $role_option): ?>
                      <option value="<?= $role_option ?>"
                        <?= set_select('role', $role_option, isset($user) && $user->role == $role_option); ?>>
                        <?= ucfirst($role_option); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <!-- Status -->
                <div class="mb-3">
                  <label class="form-label">Status</label>
                  <select name="status" class="form-control" required>
                    <option value="active" <?= set_select('status','active', isset($user) && $user->status=='active'); ?>>Active</option>
                    <option value="nonaktif" <?= set_select('status','nonaktif', isset($user) && $user->status=='nonaktif'); ?>>Nonaktif</option>
                  </select>
                </div>

                <!-- Tombol -->
                <div class="d-flex gap-2 mt-3">
                  <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Simpan
                  </button>
                  <a href="<?= site_url('users'); ?>" class="btn btn-secondary">
                    <i class="fa fa-times"></i> Batal
                  </a>
                </div>

              </form>

            </div> <!-- end card-body -->
          </div> <!-- end card -->
        </div>
      </div>
      <!-- end card -->

    </div>
  </div>
</div>
<!-- end main content -->
