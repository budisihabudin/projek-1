<div class="main-panel">
  <div class="content">
    <div class="page-inner mt--5">
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header">
              <h4 class="card-title mt-3"><?= $title; ?></h4>
              <div class="mt-3">
                <a href="<?= site_url('employee/create'); ?>" class="btn btn-success btn-sm">
                  <i class="fa fa-plus"></i> Tambah Employee
                </a>
              </div>
            </div>
            <div class="card-body">

              <form method="get" class="mb-3">
                <div class="input-group">
                  <input type="text" name="keyword" class="form-control" placeholder="Cari nama / username / jabatan..." 
                         value="<?= $this->input->get('keyword'); ?>">
                  <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
              </form>

              <div class="table-responsive">
                <table class="table table-bordered table-striped">
                  <thead class="table-dark">
                    <tr>
                      <th>No</th>
                      <th>Nama Lengkap</th>
                      <th>Jabatan</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($employees)): $no = ($this->uri->segment(3)) ? $this->uri->segment(3)+1 : 1; ?>
                      <?php foreach($employees as $e): ?>
                        <tr>
                          <td><?= $no++; ?></td>
                          <td><?= $e->nama_lengkap; ?></td>
                          <td><?= $e->nama_jabatan; ?></td>
                          <td>
                            <a href="<?= site_url('employee/edit/'.$e->id_employee); ?>" class="btn btn-warning btn-sm mb-1">
                              <i class="fa fa-edit"></i> Edit
                            </a>
                            <a href="<?= site_url('employee/delete/'.$e->id_employee); ?>" 
                               onclick="return confirm('Yakin hapus?');" 
                               class="btn btn-danger btn-sm mb-1">
                               <i class="fa fa-trash"></i> Hapus
                            </a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>

              <div class="mt-3">
                <?= $this->pagination->create_links(); ?>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
