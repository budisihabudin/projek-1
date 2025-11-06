<div class="container-fluid">
    <h3><?= $title; ?></h3>
    <form method="post" action="<?= site_url('employee/update/'.$employee->id_employee); ?>">
        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" required value="<?= htmlspecialchars($employee->nama_lengkap); ?>">
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <input type="text" name="alamat" class="form-control" required value="<?= htmlspecialchars($employee->alamat); ?>">
        </div>
        <div class="mb-3">
            <label>Telepon</label>
            <input type="number" name="telepon" class="form-control" required value="<?= htmlspecialchars($employee->telepon); ?>">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($employee->email); ?>">
        </div>
        <div class="mb-3">
            <label>ID Jabatan</label>
            <input type="text" name="id_jabatan" class="form-control" required value="<?= htmlspecialchars($employee->id_jabatan); ?>">
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin" <?= ($employee->role == 'admin') ? 'selected' : '' ?>>Admin</option>
                <option value="sales" <?= ($employee->role == 'sales') ? 'selected' : '' ?>>Sales</option>
                <option value="finance" <?= ($employee->role == 'finance') ? 'selected' : '' ?>>Finance</option>
                <option value="noc" <?= ($employee->role == 'noc') ? 'selected' : '' ?>>Noc</option>
                <option value="thd" <?= ($employee->role == 'thd') ? 'selected' : '' ?>>Thd</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= site_url('employee'); ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>
