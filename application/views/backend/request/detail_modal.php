<div>
    <p><strong>Nama Customer:</strong> <?= $request->nama; ?></p>
    <p><strong>Alamat:</strong> <?= $request->alamat; ?></p>
    <p><strong>No HP:</strong> <?= $request->no_hp; ?></p>
    <p><strong>Email:</strong> <?= $request->email; ?></p>
    <p><strong>Instansi:</strong> <?= $request->instansi; ?></p>
    <p><strong>PIC:</strong> <?= $request->pic; ?></p>
    <p><strong>Paket:</strong> <?= $request->nama_paket; ?></p>
    <p><strong>Tanggal Mulai:</strong> <?= date('d-m-Y', strtotime($request->tgl_langganan)); ?></p>
    <p><strong>Lama Langganan:</strong> <?= $request->lama_bulan; ?> bulan</p>
</div>
