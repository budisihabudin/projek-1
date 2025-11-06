<div class="px-3">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-sm border-0 mt-3">
          <div class="card-header bg-light">
            <h5 class="card-title mb-0"><?= $title ?? 'Tambah Survei'; ?></h5>
          </div>
          <div class="card-body">
            <form action="<?= site_url('request/tambah_survei'); ?>" method="POST">
              
              <div class="mb-3">
                <label for="id_request" class="form-label">Request Pemasangan</label>
                <select name="id_request" id="id_request" class="form-select paket-select" required>
                  <option value="">-- Pilih Request --</option>
                  <?php
                  $this->db->select('r.id_request, c.nama AS nama_customer, p.nama_paket');
                  $this->db->from('tb_request_pemasangan r');
                  $this->db->join('customer c', 'c.id_customer = r.id_customer', 'left');
                  $this->db->join('paket p', 'p.id_paket = r.id_paket', 'left');
                  $requests = $this->db->get()->result();
                  foreach($requests as $r) {
                      echo "<option value='{$r->id_request}'>{$r->nama_customer} - {$r->nama_paket}</option>";
                  }
                  ?>
                </select>
              </div>

              <div class="mb-3">
                <label for="tgl_survei" class="form-label">Tanggal Survei</label>
                <input type="date" name="tgl_survei" id="tgl_survei" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="catatan" class="form-label">Catatan</label>
                <textarea name="catatan" id="catatan" class="form-control" rows="4"></textarea>
              </div>

              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
              <a href="<?= site_url('request/survei_index'); ?>" class="btn btn-secondary">Batal</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Select2 JS -->
<script>
$(document).ready(function() {
    $('.paket-select').select2({
        placeholder: "-- Pilih Request Pemasangan --",
        allowClear: true,
        width: '100%'
    });
});
</script>
