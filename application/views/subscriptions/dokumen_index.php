<div class="container mt-4">
    <h4><?= $title; ?></h4>
    <a href="<?= base_url('subcriptions/add_dokumen'); ?>" class="btn btn-primary mb-3">+ Tambah Dokumen</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Pelanggan</th>
                <th>Jenis Dokumen</th>
                <th>Nomor Dokumen</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($dokumen as $d): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $d->nama_pelanggan; ?></td>
                    <td><?= $d->jenis_dokumen; ?></td>
                    <td><?= $d->nomor_dokumen; ?></td>
                    <td>
                        <a href="<?= base_url('subcriptions/edit_dokumen/'.$d->id_dokumen); ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="<?= base_url('subcriptions/delete_dokumen/'.$d->id_dokumen); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
