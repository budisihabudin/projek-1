<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Warning</title>
<link rel="icon" type="image/png" href="<?= base_url('assets/img/logo_transparan.png') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">
        <h1 class="display-4 text-danger">⚠ Maaf!</h1>
        <p class="lead"><?= $message ?></p>
        <a href="<?= site_url('dashboard') ?>" class="btn btn-primary mt-3">Kembali ke Dashboard</a>
    </div>
</body>
</html>
