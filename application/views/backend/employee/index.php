<div class="container-fluid">
    

    <div class="row">
        <div class="col-lg-12">
            
            <div class="card mt-3 border-0 shadow-sm">
                
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Tabel Data Employee</h5>
                    <a href="<?= site_url('employee/add'); ?>" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Tambah Employee
                    </a>
                </div>

                <div class="card-body">

                    <form method="get" class="mb-3">
                        <div class="input-group"> 
                            <input type="text" name="keyword" class="form-control"
                                   placeholder="Cari nama / role..."
                                   value="<?= htmlspecialchars($this->input->get('keyword')); ?>">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-search"></i> Cari
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive shadow-sm rounded">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <th>Jabatan</th> 
                                    <th>Role</th>
                                    <th>Created At</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($employees)): ?>
                                    <?php foreach($employees as $e): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($e->nama_lengkap); ?></td>
                                            <td><?= htmlspecialchars($e->nama_jabatan ?? $e->id_jabatan); ?></td> 
                                            <td><?= htmlspecialchars($e->role); ?></td>
                                            <td><?= date('d-m-Y H:i', strtotime($e->created_at ?? '')); ?></td>
                                            <td class="text-center">
                                                <a href="<?= site_url('employee/edit/'.$e->id_employee); ?>" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i> Edit</a>
                                                <a href="<?= site_url('employee/delete/'.$e->id_employee); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')"><i class="fa fa-trash"></i> Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center text-muted">Belum ada data employee.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <?= $pagination ?? ''; ?>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>