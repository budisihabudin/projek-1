<div class="container-fluid px-3">
    <h3 class="mb-3"><?= $title ?? 'Edit Request Instalasi'; ?></h3>

    <form method="post" action="<?= site_url('request/update_instalasi/'.$request->id_request); ?>">
        <div class="row">
            <!-- Kiri: Instalasi / NOC -->
            <div class="col-md-6">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Data Instalasi / NOC</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Paket</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($request->nama_paket); ?>" readonly>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Langganan</label>
                                <input type="date" class="form-control" value="<?= htmlspecialchars($request->tgl_langganan); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lama (Bulan)</label>
                                <input type="number" class="form-control" value="<?= htmlspecialchars($request->lama_bulan); ?>" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Approval NOC</label>
                            <input type="text" class="form-control" value="<?= ucfirst($request->approval_noc); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">IP Customer</label>
                            <input type="text" placeholder="Masukkan IP Customer" name="ip_customer" class="form-control" value="<?= htmlspecialchars($request->ip_customer ?? ''); ?>" required>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Kanan: Identitas Customer -->
            <div class="col-md-6">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Identitas Customer</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Customer</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($request->nama); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kode Customer</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($request->kode_customer); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" rows="2" readonly><?= htmlspecialchars($request->alamat); ?></textarea>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">No HP</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($request->no_hp); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control" value="<?= htmlspecialchars($request->email); ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>

        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
        <a href="<?= site_url('request/instalasi'); ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>
