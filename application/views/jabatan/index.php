<div class="main-panel">
  <div class="content">
    <div class="page-inner mt--5">
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header">
              <h4 class="card-title mt-3"><?= $title ?></h4>
            </div>
            <div class="card-body">

              <form method="get" action="<?= site_url('jabatan'); ?>" class="mb-3">
                <div class="input-group">
                  <input type="text" name="keyword" class="form-control" placeholder="Cari jabatan..." value="<?= $this->input->get('keyword'); ?>">
                  <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
              </form>

              <div class="mb-3">
                <a href="<?= site_url('jabatan/create'); ?>" class="btn btn-success">
                  <i class="fa fa-plus"></i> Tambah Jabatan
                </a>
              </div>

              <div class="table-responsive">
                <table class="table table-bordered table-striped">
                  <thead class="table-dark">
                    <tr>
                      <th class="text-center">No</th>
                      <th>Nama Jabatan</th>
                      <th class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($jabatans)): 
                        $no = ($this->uri->segment(3) && is_numeric($this->uri->segment(3))) ? $this->uri->segment(3)+1 : 1;
                        foreach($jabatans as $j): ?>
                      <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= htmlspecialchars($j->nama_jabatan, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="text-center">
                          <a href="<?= site_url('jabatan/edit/'.$j->id_jabatan) ?>" class="btn btn-sm btn-primary mb-1"><i class="fa fa-edit"></i> Edit</a>
                          <a href="<?= site_url('jabatan/delete/'.$j->id_jabatan) ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Hapus jabatan ini?')"><i class="fa fa-trash"></i> Hapus</a>
                        </td>
                      </tr>
                    <?php endforeach; else: ?>
                      <tr>
                        <td colspan="3" class="text-center">Tidak ada data jabatan</td>
                      </tr>
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
