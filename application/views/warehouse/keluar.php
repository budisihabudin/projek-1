<div class="main-panel">
    <div class="content">
        <div class="container my-4">
            <h3 class="text-center mb-4 display-6 fw-bold"><?= $title ?></h3>

           
            <!-- Tombol Tambah -->
            <div class="mb-3">
                <a href="<?= site_url('warehouse/create_keluar') ?>" class="btn btn-primary">Tambah Barang Keluar</a>
            </div>

            <!-- Form Pencarian -->
            <form method="get" action="<?= site_url('warehouse/keluar') ?>" class="mb-3">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari nama barang / keterangan..." value="<?= $this->input->get('keyword') ?>">
                    <button class="btn btn-secondary" type="submit">Cari</button>
                </div>
            </form>

            <!-- Tabel Barang Keluar -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Tanggal Keluar</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($barang)): ?>
                            <?php $no = $this->input->get('per_page') ?? 0; foreach($barang as $b): $no++; ?>
                                <tr>
                                    <td class="text-center"><?= $no ?></td>
                                    <td><?= $b->nama_barang ?></td>
                                    <td class="text-center"><?= $b->jumlah ?></td>
                                    <td class="text-center"><?= $b->tanggal_keluar ?></td>
                                    <td><?= $b->keterangan ?></td>
                                    <td class="text-center">
                                        <a href="<?= site_url('warehouse/edit_keluar/'.$b->id_warehouse_keluar) ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="<?= site_url('warehouse/delete_keluar/'.$b->id_warehouse_keluar) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus barang keluar ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center">Data tidak ditemukan</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?= $pagination ?? '' ?>
        </div>
    </div>
</div>
