<div class="container">
    <h3 class="mb-4"><?= $title ?></h3>

    <form method="post">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?= isset($user) ? $user->username : '' ?>" required>
        </div>

        <div class="mb-3">
            <label>Password <?= isset($user) ? '(kosongkan jika tidak diganti)' : '' ?></label>
            <input type="password" name="password" class="form-control" <?= isset($user) ? '' : 'required' ?>>
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin" <?= isset($user) && $user->role=='admin' ? 'selected' : '' ?>>Admin</option>
                <option value="customer" <?= isset($user) && $user->role=='customer' ? 'selected' : '' ?>>Customer</option>
                <option value="teknisi" <?= isset($user) && $user->role=='teknisi' ? 'selected' : '' ?>>Teknisi</option>
                <option value="cs" <?= isset($user) && $user->role=='cs' ? 'selected' : '' ?>>CS</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="<?= site_url('users'); ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>
