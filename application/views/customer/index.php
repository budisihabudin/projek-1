<!-- main content -->
<div class="main-panel">
  <div class="content">
    <div class="page-inner mt--5">

      <!-- Card: Data Customer -->
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header pt-3 pb-2">
              <h4 class="card-title mb-2 mt-3"><?= $title ?? 'Data Customer'; ?></h4>
            </div>

            <div class="card-body">

              <!-- Form Pencarian -->
              <form method="get" action="<?= site_url('customer'); ?>" class="mb-3">
                <div class="input-group">
                  <input type="text" name="keyword" value="<?= $this->input->get('keyword') ?? '' ?>" 
                         class="form-control" placeholder="Cari customer...">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
              </form>

              <!-- Tombol Tambah Customer -->
              <?php if ($this->session->userdata('role') =="admin"): ?>
              <div class="mb-3">
                <a href="<?= site_url('customer/create'); ?>" class="btn btn-success">
                  <i class="fa fa-plus"></i> Tambah Customer
                </a>
              </div>  
              <?php endif ?>
              

              <!-- Tabel Responsif -->
              <div class="table-responsive shadow-sm rounded">
                <table class="table table-bordered table-striped align-middle mb-0">
                  <thead class="table-dark">
                    <tr>
                      <th class="text-center">No</th>
                      <th>Kode</th>
                      <th>Nama</th>
                      <th>Email</th>
                      <th>No HP</th>
                      <th>Status</th>
                      <th class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($customer)): ?>
                      <?php $no = ($this->uri->segment(3) && is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) + 1 : 1; ?>
                      <?php foreach ($customer as $c): ?>
                        <tr>
                          <td class="text-center"><?= $no++; ?></td>
                          <td><?= htmlspecialchars($c->kode_customer, ENT_QUOTES, 'UTF-8'); ?></td>
                          <td><?= htmlspecialchars($c->nama, ENT_QUOTES, 'UTF-8'); ?></td>
                          <td><?= htmlspecialchars($c->email, ENT_QUOTES, 'UTF-8'); ?></td>
                          <td><?= htmlspecialchars($c->no_hp, ENT_QUOTES, 'UTF-8'); ?></td>
                          <td>
                            <span class="badge bg-<?= $c->status == 'aktif' ? 'success' : 'danger'; ?> text-white">
                              <?= ucfirst($c->status); ?>
                            </span>
                          </td>
                          <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                              <a href="<?= site_url('customer/delete/'.$c->id_customer); ?>" 
                                 class="btn btn-danger btn-sm mb-1"
                                 onclick="return confirm('Yakin ingin hapus customer ini?');">
                                <i class="fa fa-trash"></i>
                              </a>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="7" class="text-center">Tidak ada data customer</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              <div class="mt-3">
                <?= $pagination ?? ''; ?>
              </div>

            </div>
          </div>
        </div>
      </div>
      <!-- end card -->

    </div>
  </div>
</div>
<!-- end main content -->
