<div class="main-panel">
    <div class="content">
        <div class="container my-4">
            <h3 class="text-center mb-4 display-6 fw-bold"><?= $title ?></h3>


            <div class="mb-3 d-flex justify-content-between">
                <a href="<?= site_url('warehouse/create_rusak'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Barang Rusak</a>
                <form method="get" class="d-flex">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari nama/keterangan..." value="<?= $this->input->get('keyword'); ?>">
                    <button class="btn btn-secondary ml-2">Cari</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Barang</th>
                            <th>Jumlah Rusak</th>
                            <th>Tanggal Rusak</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($barang)): ?>
                            <?php $no = ($this->input->get('per_page') ?? 0) + 1; ?>
                            <?php foreach($barang as $b): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $b->nama_barang ?></td>
                                    <td><?= $b->jumlah ?></td>
                                    <td><?= $b->tanggal_rusak ?></td>
                                    <td><?= $b->keterangan ?></td>
                                    <td>
                                        <a href="<?= site_url('warehouse/edit_rusak/'.$b->id_rusak); ?>" class="btn btn-sm btn-warning"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="<?= site_url('warehouse/delete_rusak/'.$b->id_rusak); ?>" onclick="return confirm('Hapus data?');" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?= $this->pagination->create_links(); ?>
        </div>
    </div>
</div>
