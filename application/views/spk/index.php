<div class="main-panel">
  <div class="content">
    <div class="page-inner mt--5">

      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header pt-3 pb-2">
              <h4 class="card-title mb-2 mt-3"><?= $title ?? 'Data SPK'; ?></h4>
            </div>

            <div class="card-body">
              
              <div class="mb-4">
                <a href="<?= site_url('spk/create'); ?>" class="btn btn-success">
                  <i class="fa fa-plus me-1"></i> Tambah SPK
                </a>
              </div>
              
              <form method="get" action="<?= site_url('spk'); ?>" class="mb-4">
                <div class="input-group">
                  <input type="text" name="q" value="<?= $keyword ?? '' ?>" 
                        class="form-control" placeholder="Cari kode SPK...">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                  <?php if (!empty($keyword)): ?>
                    <a href="<?= site_url('spk'); ?>" class="btn btn-secondary">Reset</a>
                  <?php endif; ?>
                </div>
              </form>

              <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                  <thead class="table-dark">
                    <tr>
                      <th class="text-center">No</th>
                      <th>Kode</th>
                      <th>SPK</th>
                      <th class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($spk)): ?>
                      <?php 
                        // Menggunakan input get 'per_page' untuk pagination offset
                        $no = ($this->input->get('per_page') && is_numeric($this->input->get('per_page'))) ? $this->input->get('per_page') + 1 : 1; 
                      ?>
                      <?php foreach($spk as $row): ?>
                        <tr>
                          <td class="text-center"><?= $no++; ?></td>
                          <td><?= htmlspecialchars($row->kode_spk, ENT_QUOTES, 'UTF-8'); ?></td>
                          <td><?= htmlspecialchars($row->spk, ENT_QUOTES, 'UTF-8'); ?></td>
                          
                          <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                              <a href="<?= site_url('spk/edit/'.$row->id_spk); ?>" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                              </a>

                              <a href="<?= site_url('spk/delete/'.$row->id_spk); ?>" class="btn btn-danger btn-sm" title="Hapus"
                                 onclick="return confirm('Yakin hapus SPK <?= $row->kode_spk ?>?')">
                                <i class="fa fa-trash"></i>
                              </a>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="8" class="text-center">Data SPK tidak ditemukan</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>

              <div class="mt-4">
                <?= $pagination ?? ''; ?>
              </div>

            </div>
          </div>
        </div>
      </div>
      </div>
  </div>
</div>