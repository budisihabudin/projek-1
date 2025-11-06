<!-- main content -->
<div class="main-panel">
  <div class="content">
    <div class="page-inner mt--5">

      <!-- Card: Form Tambah/Edit Employee -->
      <div class="row">
        <div class="col-12 col-md-8 mx-auto">
          <div class="card shadow-sm">
            <div class="card-header pt-3 pb-2">
              <h4 class="card-title mb-2 mt-3"><?= $title ?? 'Tambah Employee'; ?></h4>
            </div>

            <div class="card-body">
              <?= validation_errors('<div class="alert alert-danger">','</div>'); ?>

              <form method="post" action="<?= $action; ?>">

                <!-- Nama Lengkap -->
                <div class="mb-3">
                  <label class="form-label">Nama Lengkap</label>
                  <input type="text" name="nama_lengkap" class="form-control" 
                         value="<?= $employee->nama_lengkap ?? set_value('nama_lengkap'); ?>" required>
                </div>

                <!-- Jabatan -->
                <div class="mb-3">
                  <label class="form-label">Jabatan</label>
                  <select name="id_jabatan" class="form-control" required>
                    <option value="">-- Pilih Jabatan --</option>
                    <?php foreach($jabatan as $j): ?>
                      <option value="<?= $j->id_jabatan ?>" 
                        <?= (isset($employee) && $employee->id_jabatan == $j->id_jabatan) ? 'selected' : ''; ?>>
                        <?= $j->nama_jabatan ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <hr>
                <h5>Login Account</h5>

                <!-- Username -->
                <div class="mb-3">
                  <label class="form-label">Username</label>
                  <input type="text" name="username" class="form-control" 
                         value="<?= $employee->username ?? set_value('username'); ?>" required>
                </div>

                <!-- Password -->
                <div class="mb-3">
                  <label class="form-label">Password <?= isset($employee) ? '(isi jika ingin ganti)' : ''; ?></label>
                  <input type="password" name="password" class="form-control" <?= isset($employee) ? '' : 'required'; ?>>
                </div>

                <!-- Role -->
                <div class="mb-3">
                  <label class="form-label">Role</label>
                  <select name="role" class="form-control" required>
                    <?php $roles = ['admin','sales','finance','customer','teknisi','hrd','surveyor','noc']; ?>
                    <?php foreach($roles as $role_option): ?>
                      <option value="<?= $role_option ?>" 
                        <?= (isset($employee) && $employee->role == $role_option) ? 'selected' : ''; ?>>
                        <?= ucfirst($role_option); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <!-- Tombol Simpan & Batal -->
                <div class="d-flex gap-2 mt-3">
                  <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                  <a href="<?= site_url('employee'); ?>" class="btn btn-secondary"><i class="fa fa-times"></i> Batal</a>
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
