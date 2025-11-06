<div class="px-3">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12">
        <div class="card shadow-sm border-0">
          <div class="card-body">

            <div class="card mt-3 border-0 shadow-sm">
              <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><?= $title ?? 'Data Request Pemasangan'; ?></h5>
                <a href="<?= site_url('request/add'); ?>" class="btn btn-primary btn-sm">
                  <i class="fa fa-plus"></i> Tambah Request
                </a>
              </div>

              <div class="card-body">

                <!-- Form Pencarian -->
                <form method="get" class="mb-3">
                  <div class="input-group">
                    <input type="text" name="keyword" class="form-control"
                           placeholder="Cari nama / paket..."
                           value="<?= htmlspecialchars($this->input->get('keyword'), ENT_QUOTES, 'UTF-8'); ?>">
                    <button class="btn btn-primary" type="submit">
                      <i class="fa fa-search"></i> Cari
                    </button>
                  </div>
                </form>

                <!-- Tabel -->
                <div class="table-responsive shadow-sm rounded">
                  <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead >
                      <?php
                      // Debug: Cek role user
                      $current_role = $this->session->userdata('role');
                      // echo "<!-- DEBUG: Current Role = " . $current_role . " -->";
                      ?>
                      <?php if ($current_role == 'customer'): ?>
                      <!-- Customer View - Sederhana -->
                      <tr>
                        <th>Paket Layanan</th>
                        <th>Status Berlangganan</th>
                        <th>Tanggal Aktif</th>
                        <th>Tagihan</th>
                        <th class="text-center">Aksi</th>
                      </tr>
                      <?php else: ?>
                      <!-- Admin/Employee View - Lengkap -->
                      <tr>
                        <th>Nama Customer</th>
                        <th>Paket</th>
                        <th>Tanggal Mulai</th>
                        <th>Lama (bulan)</th>
                        <th>Status Sales</th>
                        <th>Status Survey</th>
                        <th>Status NOC</th>
                        <th>Status Finance</th>
                        <th class="text-center">Aksi</th>
                      </tr>
                      <?php endif; ?>
                    </thead>
                    <tbody>
                      <?php if(!empty($requests)): ?>
                        <?php foreach($requests as $r): ?>
                          <?php if ($this->session->userdata('role') == 'customer'): ?>
                          <!-- Customer View - User Friendly -->
                          <tr>
                            <td><?= htmlspecialchars($r->nama_paket, ENT_QUOTES); ?></td>
                            <td>
                              <?php
                              // Status yang customer-friendly
                              if ($r->approval_finance == 'approved') {
                                  echo '<span class="badge bg-success"><i class="fa fa-check"></i> Aktif</span>';
                              } elseif ($r->approval_finance == 'pending' && $r->approval_sales == 'approved' && $r->approval_survei == 'approved' && $r->approval_noc == 'approved') {
                                  echo '<span class="badge bg-info"><i class="fa fa-file-invoice"></i> Menunggu Invoice</span>';
                              } else {
                                  echo '<span class="badge bg-warning"><i class="fa fa-clock"></i> Sedang Diproses</span>';
                              }
                              ?>
                            </td>
                            <td><?= $r->id_invoice ? date('d-m-Y', strtotime($r->tgl_langganan)) : '-'; ?></td>
                            <td>
                              <?php if ($r->id_invoice): ?>
                                <span class="badge bg-primary"><?= $r->id_invoice; ?></span>
                              <?php else: ?>
                                <span class="text-muted">Belum Ada</span>
                              <?php endif; ?>
                            </td>
                            <td class="text-center">
                              <?php if ($r->id_invoice): ?>
                                <a href="<?= site_url('tagihan/bulanan'); ?>" class="btn btn-sm btn-primary">
                                  <i class="fa fa-credit-card"></i> Bayar
                                </a>
                              <?php else: ?>
                                <button class="btn btn-sm btn-secondary" disabled>
                                  <i class="fa fa-clock"></i> Menunggu
                                </button>
                              <?php endif; ?>
                            </td>
                          </tr>
                          <?php else: ?>
                          <!-- Admin/Employee View - Lengkap -->
                          <tr>
                            <td><?= htmlspecialchars($r->nama_customer, ENT_QUOTES); ?></td>
                            <td><?= htmlspecialchars($r->nama_paket, ENT_QUOTES); ?></td>
                            <td><?= date('d-m-Y', strtotime($r->tgl_langganan)); ?></td>
                            <td><?= htmlspecialchars($r->lama_bulan, ENT_QUOTES); ?></td>
                            <td>
                              <?php
                              $sales_class = 'bg-secondary';
                              $sales_icon = 'fa-times';
                              if ($r->approval_sales == 'approved') {
                                  $sales_class = 'bg-success';
                                  $sales_icon = 'fa-check';
                              } elseif ($r->approval_sales == 'pending') {
                                  $sales_class = 'bg-warning';
                                  $sales_icon = 'fa-clock';
                              } elseif ($r->approval_sales == 'rejected') {
                                  $sales_class = 'bg-danger';
                                  $sales_icon = 'fa-times';
                              }
                              ?>
                              <span class="badge <?= $sales_class; ?>">
                                  <i class="fa <?= $sales_icon; ?>"></i> <?= ucfirst($r->approval_sales); ?>
                              </span>
                            </td>
                            <td>
                              <?php
                              $survey_class = 'bg-secondary';
                              $survey_icon = 'fa-times';
                              if ($r->approval_survei == 'approved') {
                                  $survey_class = 'bg-success';
                                  $survey_icon = 'fa-check';
                              } elseif ($r->approval_survei == 'pending') {
                                  $survey_class = 'bg-warning';
                                  $survey_icon = 'fa-clock';
                              } elseif ($r->approval_survei == 'rejected') {
                                  $survey_class = 'bg-danger';
                                  $survey_icon = 'fa-times';
                              }
                              ?>
                              <span class="badge <?= $survey_class; ?>">
                                  <i class="fa <?= $survey_icon; ?>"></i> <?= ucfirst($r->approval_survei); ?>
                              </span>
                            </td>
                            <td>
                              <?php
                              $noc_class = 'bg-secondary';
                              $noc_icon = 'fa-times';
                              if ($r->approval_noc == 'approved') {
                                  $noc_class = 'bg-success';
                                  $noc_icon = 'fa-check';
                              } elseif ($r->approval_noc == 'pending') {
                                  $noc_class = 'bg-warning';
                                  $noc_icon = 'fa-clock';
                              } elseif ($r->approval_noc == 'rejected') {
                                  $noc_class = 'bg-danger';
                                  $noc_icon = 'fa-times';
                              }
                              ?>
                              <span class="badge <?= $noc_class; ?>">
                                  <i class="fa <?= $noc_icon; ?>"></i> <?= ucfirst($r->approval_noc); ?>
                              </span>
                            </td>
                            <td>
                              <?php
                              $finance_class = 'bg-secondary';
                              $finance_icon = 'fa-times';
                              if ($r->approval_finance == 'approved') {
                                  $finance_class = 'bg-success';
                                  $finance_icon = 'fa-check';
                              } elseif ($r->approval_finance == 'pending') {
                                  $finance_class = 'bg-warning';
                                  $finance_icon = 'fa-clock';
                              } elseif ($r->approval_finance == 'rejected') {
                                  $finance_class = 'bg-danger';
                                  $finance_icon = 'fa-times';
                              }
                              ?>
                              <span class="badge <?= $finance_class; ?>">
                                  <i class="fa <?= $finance_icon; ?>"></i> <?= ucfirst($r->approval_finance); ?>
                              </span>
                            </td>
                            <td class="text-center">
                            <td class="text-center">
                              <button class="btn btn-info btn-sm btn-detail" data-id="<?= $r->id_request; ?>">
                                <i class="fa fa-eye"></i> Detail
                              </button>

                              <?php // SALES APPROVAL BUTTON ?>
                              <?php if ($this->session->userdata('role') == "sales" && $r->approval_sales == "pending"): ?>
                                <a href="<?= site_url('request/approve_sales_req/'.$r->id_request); ?>"
                                   class="btn btn-sm btn-success"
                                   onclick="return confirm('Approve request untuk <?= htmlspecialchars($r->nama_customer, ENT_QUOTES); ?>?');">
                                  <i class="fa fa-check"></i> Approve Sales
                                </a>
                                <a href="<?= site_url('request/reject_sales_req/'.$r->id_request); ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Reject request untuk <?= htmlspecialchars($r->nama_customer, ENT_QUOTES); ?>?');">
                                  <i class="fa fa-times"></i> Reject Sales
                                </a>
                              <?php endif; ?>

                              <?php // SURVEYOR APPROVAL BUTTON (Manager Surveyor) ?>
                              <?php if ($this->session->userdata('nama_jabatan') == "Manager Surveyor" && $r->approval_sales == "approved" && $r->approval_survei == "pending"): ?>
                                <a href="<?= site_url('request/approve_survei/'.$r->id_request); ?>"
                                   class="btn btn-sm btn-success"
                                   onclick="return confirm('Approve survei untuk <?= htmlspecialchars($r->nama_customer, ENT_QUOTES); ?>?');">
                                  <i class="fa fa-check"></i> Approve Survey
                                </a>
                                <a href="<?= site_url('request/reject_survei/'.$r->id_request); ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Reject survei untuk <?= htmlspecialchars($r->nama_customer, ENT_QUOTES); ?>?');">
                                  <i class="fa fa-times"></i> Reject Survey
                                </a>
                              <?php endif; ?>

                              <?php // NOC APPROVAL BUTTON ?>
                              <?php if ($this->session->userdata('role') == "noc" && $r->approval_sales == "approved" && $r->approval_survei == "approved" && $r->approval_noc == "pending"): ?>
                                <a href="<?= site_url('request/approve_noc/'.$r->id_request); ?>"
                                   class="btn btn-sm btn-success"
                                   onclick="return confirm('Approve instalasi untuk <?= htmlspecialchars($r->nama_customer, ENT_QUOTES); ?>?');">
                                  <i class="fa fa-check"></i> Approve NOC
                                </a>
                                <a href="<?= site_url('request/reject_noc/'.$r->id_request); ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Reject instalasi untuk <?= htmlspecialchars($r->nama_customer, ENT_QUOTES); ?>?');">
                                  <i class="fa fa-times"></i> Reject NOC
                                </a>
                              <?php endif; ?>

                              <?php // FINANCE APPROVAL BUTTON ?>
                              <?php if ($this->session->userdata('role') == "finance" && $r->approval_sales == "approved" && $r->approval_survei == "approved" && $r->approval_noc == "approved" && $r->approval_finance == "pending"): ?>
                                <a href="<?= site_url('finance/generate_inv/'.$r->id_request); ?>"
                                   class="btn btn-sm btn-success"
                                   onclick="return confirm('Generate invoice untuk <?= htmlspecialchars($r->nama_customer, ENT_QUOTES); ?> - <?= htmlspecialchars($r->nama_paket, ENT_QUOTES); ?> ?');">
                                  <i class="fa fa-file-invoice"></i> Generate Invoice
                                </a>
                              <?php endif; ?>

                              <?php // DELETE BUTTON FOR SALES ?>
                              <?php if ($this->session->userdata('role') =="sales" && $r->approval_sales == "pending"): ?>
                              <a href="<?= site_url('request/delete/'.$r->id_request); ?>" class="btn btn-danger btn-sm"
                                 onclick="return confirm('Yakin ingin menghapus data ini?')">
                                <i class="fa fa-trash"></i> Hapus
                              </a>
                              <?php endif ?>
                            </td>
                          </tr>
                          <?php endif; // End role conditional ?>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <?php if ($this->session->userdata('role') == 'customer'): ?>
                        <tr><td colspan="5" class="text-center text-muted">Belum ada data request pemasangan.</td></tr>
                        <?php else: ?>
                        <tr><td colspan="9" class="text-center text-muted">Belum ada data request pemasangan.</td></tr>
                        <?php endif; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>

                <div class="mt-3"><?= $pagination ?? ''; ?></div>

              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="detailModalLabel"><i class="fa fa-info-circle"></i> Detail Request</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="detailContent">
        <!-- Data akan dimuat AJAX di sini -->
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Memuat detail...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Script AJAX Detail -->
<script>
$(document).ready(function() {
    $('.btn-detail').click(function() {
        var id = $(this).data('id');
        $('#detailContent').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">Memuat detail...</p>
            </div>
        `);

        $.ajax({
            url: '<?= site_url('request/detail_request'); ?>',
            type: 'GET',
            data: {id: id},
            dataType: 'json',
            success: function(res) {
                let html = `
                <table class="table table-borderless table-striped mb-0">
                    <tr>
                        <th width="30%">Nama Customer</th>
                        <td>${res.nama}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>${res.alamat}</td>
                    </tr>
                    <tr>
                        <th>No HP</th>
                        <td>${res.no_hp}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>${res.email}</td>
                    </tr>
                    <tr>
                        <th>Instansi</th>
                        <td>${res.instansi}</td>
                    </tr>
                    <tr>
                        <th>PIC</th>
                        <td>${res.pic}</td>
                    </tr>
                    <tr>
                        <th>Paket</th>
                        <td>${res.nama_paket}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Mulai</th>
                        <td>${new Date(res.tgl_langganan).toLocaleDateString('id-ID')}</td>
                    </tr>
                    <tr>
                        <th>Lama Langganan</th>
                        <td>${res.lama_bulan} bulan</td>
                    </tr>
                </table>`;
                $('#detailContent').html(html);

                // Tampilkan modal menggunakan Bootstrap 5 API
                var myModal = new bootstrap.Modal(document.getElementById('detailModal'));
                myModal.show();
            },
            error: function() {
                $('#detailContent').html('<p class="text-danger text-center">Gagal memuat detail request.</p>');
            }
        });
    });
});
</script>

