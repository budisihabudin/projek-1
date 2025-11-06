<div class="px-3">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12">
        <div class="card shadow-sm border-0">
          <div class="card-body">

            <div class="card mt-3 border-0 shadow-sm">
              <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><?= $title ?? 'Data Request Survei'; ?></h5>
                <?php if ($this->session->userdata('role') =="sales"): ?>
                  <a href="<?= site_url('request/add_survei'); ?>" class="btn btn-primary btn-sm">
                  <i class="fa fa-plus"></i> Tambah Survei
                </a>
                <?php endif ?>
              </div>

              <div class="card-body">

                <!-- Form Pencarian -->
                <form method="get" class="mb-3">
                  <div class="input-group">
                    <input type="text" name="keyword" class="form-control"
                           placeholder="Cari nama customer / paket..."
                           value="<?= htmlspecialchars($this->input->get('keyword'), ENT_QUOTES, 'UTF-8'); ?>">
                    <button class="btn btn-primary" type="submit">
                      <i class="fa fa-search"></i> Cari
                    </button>
                  </div>
                </form>

                <!-- Tabel -->
                <div class="table-responsive shadow-sm rounded">
                  <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                      <tr>
                        <th>Nama Customer</th>
                        <th>Paket</th>
                        <th>Tanggal Survei</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(!empty($surveis)): ?>
                        <?php foreach($surveis as $s): ?>
                          <tr>
                            <td><?= htmlspecialchars($s->nama_customer, ENT_QUOTES); ?></td>
                            <td><?= htmlspecialchars($s->nama_paket, ENT_QUOTES); ?></td>
                            <td><?= $s->tgl_survei ? date('d-m-Y', strtotime($s->tgl_survei)) : '-'; ?></td>
                            <td><?= ucfirst($s->status); ?></td>
                            <td class="text-center">
                              <?php if ($this->session->userdata('nama_jabatan') =="Manager Surveyor"): ?>
                                <a href="<?php echo base_url('request/approval_survei/'.$s->id_survei); ?>" class="btn btn-success btn-sm m-1">Approval</a>
                              <?php endif ?>
                              <button class="btn btn-info btn-sm btn-detail" data-id="<?= $s->id_survei; ?>">
                                <i class="fa fa-eye"></i> Detail
                              </button>
                              <?php if ($this->session->userdata('nama_jabatan') =="Staff Surveyor"): ?>
                                  <a href="<?= site_url('request/dokumen_survei/'.$s->id_survei); ?>" class="btn btn-sm btn-primary">
                                      <i class="fa fa-upload"></i> Upload Dokumen Survei
                                  </a>
                              <?php endif ?>

                              <?php if ($this->session->userdata('nama_jabatan') =="Manager Surveyor"): ?>
                                <a href="<?= base_url('request/pilih_surveyor/'.$s->id_survei) ?>" class="btn btn-warning btn-sm m-1">Lakukan Surveis</a>
                              <?php endif ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted">Belum ada data survei.</td></tr>
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

<!-- Modal Detail Survei -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Survei</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="detailContent"></div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('.btn-detail').click(function() {
        var id = $(this).data('id');

        $.ajax({
            url: '<?= site_url('request/detail_survei'); ?>',
            type: 'GET',
            data: {id: id},
            dataType: 'json',
            success: function(res) {
                let html = `
                    <p><strong>Nama Customer:</strong> ${res.nama_customer}</p>
                    <p><strong>Paket:</strong> ${res.nama_paket}</p>
                    <p><strong>Tanggal Survei:</strong> ${res.tgl_survei ? new Date(res.tgl_survei).toLocaleDateString() : '-'}</p>
                    <p><strong>Status:</strong> ${res.status}</p>
                    <p><strong>Catatan:</strong> ${res.catatan ?? '-'}</p>
                `;
                $('#detailContent').html(html);
                var myModal = new bootstrap.Modal(document.getElementById('detailModal'));
                myModal.show();
            },
            error: function() {
                alert('Gagal memuat detail survei.');
            }
        });
    });
});
</script>
