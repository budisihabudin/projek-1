<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lupa Password</title>
<link rel="icon" type="image/png" href="<?= base_url('assets/img/logo_transparan.png') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    body {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .card {
        width: 100%;
        max-width: 400px;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        padding: 30px;
        background-color: #fff;
    }
    .card h4 {
        font-weight: bold;
        color: #343a40;
    }
    .btn-custom {
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.2);
    }
    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        border-color: #007bff;
    }
    @media (max-width: 480px) {
        .card {
            padding: 20px;
            border-radius: 15px;
        }
    }
</style>
</head>
<body>
<div class="card">
    <h4 class="text-center mb-4">Lupa Password</h4>

    <!-- Flashdata -->
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('auth/forgot_password'); ?>">
        <div class="mb-3">
            <label for="identifier" class="form-label">Nomor HP atau Username</label>
            <input type="text" id="identifier" name="identifier" class="form-control" placeholder="Masukkan nomor HP atau username" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 btn-custom">Lanjut</button>
        <a href="<?= base_url('auth/login'); ?>" class="btn btn-secondary w-100 btn-custom mt-2">Kembali Login</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
