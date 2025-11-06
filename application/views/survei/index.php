<div class="main-panel">
  <div class="content">
    <div class="container my-4">
      <h3 class="text-center mb-4"><?= $title; ?></h3>

      <form method="get" class="mb-3">
        <div class="input-group">
          <input type="text" name="keyword" value="<?= $keyword; ?>" class="form-control" placeholder="Cari ID, Customer, Paket atau Aktivitas...">
          <button class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
        </div>
      </form>

      <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped align-middle">
          <thead class="table-primary text-center">
            <tr>
              <th>Customer</th>
              <th>Paket</th>
              <th>Teknisi</th>
              <th>Tanggal Langganan</th>
              <th>Lama Langganan</th>
              <th>Status</th>
              <th>Aktivitas</th>
              <th>Opsi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($survei as $s): ?>
            <tr>
              <td><?= $s->nama_customer ?></td>
              <td><?= $s->nama_paket ?></td>
              <td><?= $s->nama_teknisi ?? '-' ?></td>
              <td><?= date('d M Y', strtotime($s->tgl_langganan)) ?></td>
              <td class="text-center"><?= $s->lama_langganan ?> bulan</td>
              <td class="text-center"><?= ucfirst($s->status) ?></td>
              <td class="text-center">
                <?php 
                  switch($s->aktivitas) {
                    case 'belum survei': $badge_class = 'bg-warning text-dark'; break;
                    case 'sudah survei': $badge_class = 'bg-info text-white'; break;
                    case 'approval': $badge_class = 'bg-success text-white'; break;
                    case 'cancel': $badge_class = 'bg-danger text-white'; break;
                    default: $badge_class = 'bg-secondary text-white';
                  }
                ?>
                <span class="badge <?= $badge_class ?>"><?= ucfirst($s->aktivitas) ?></span>
              </td>
              <td class="text-center">
                <div class="d-flex flex-wrap justify-content-center">

                  <?php if ($this->session->userdata('role') =="surveyor"): ?>
                    <?php if($s->aktivitas == 'belum survei' || $s->aktivitas == 'sudah survei'): ?>
                    <a href="<?= base_url('survei/edit/'.$s->id_histori) ?>" class="btn btn-sm btn-warning m-1" title="Edit Data">
                        <i class="fas fa-edit"></i> <span class="d-none d-sm-inline">Edit</span>
                      </a>
                      <?php if (!empty($s->foto_survei)): ?>
                        <a href="<?= base_url('uploads/survei/' . $s->foto_survei) ?>" target="_blank" class="btn btn-info btn-sm" title="Lihat File">
                                <i class="fas fa-eye"></i> Lihat
                            </a>
                      <?php endif ?>
                      
                  <?php endif; ?>
                  <?php endif ?>

                                  
                  

                  <?php if ($this->session->userdata('role') =="admin" || $this->session->userdata('role') =="sales"): ?>
                    <?php if($s->aktivitas == 'sudah survei'): ?>
                    <a href="<?= base_url('survei/dokumen_detail/'.$s->id_histori) ?>" class="btn btn-sm btn-primary m-1" title="Lihat dan Setujui Dokumen">
                      <i class="fas fa-file-alt"></i> <span class="d-none d-sm-inline">Dokumen</span>
                    </a>
                    <?php endif; ?>
                  <?php endif ?>

                  
                <!--   <a href="<?//= site_url('approval_langganan/approve/'.$s->id_histori) ?>" class="btn btn-sm btn-success m-1" onclick="return confirm('Setujui langganan ini?')" title="Setujui Langganan">
                    <i class="fas fa-check"></i> <span class="d-none d-sm-inline">Approve</span>
                  </a>
                  <a href="<?//= site_url('approval_langganan/reject/'.$s->id_histori) ?>" class="btn btn-sm btn-danger m-1" onclick="return confirm('Tolak langganan ini?')" title="Tolak Langganan">
                    <i class="fas fa-times"></i> <span class="d-none d-sm-inline">Reject</span>
                  </a> -->
                  

                </div>
              </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <?php 
        $total_page = ceil($total/$limit);
        if($total_page > 1): ?>
        <nav class="mt-3">
          <ul class="pagination justify-content-center">
            <?php for($i=1; $i<=$total_page; $i++): ?>
              <li class="page-item <?= ($i==$page)?'active':''; ?>">
                <a class="page-link" href="<?= base_url('survei?keyword='.$keyword.'&page='.$i) ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
          </ul>
        </nav>
      <?php endif; ?>

    </div>
  </div>
</div>