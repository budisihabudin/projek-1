<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= isset($title) ? $title : 'ERP ISP'; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Toastr -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  <style>
    body {
      background: linear-gradient(135deg, #4e73df, #1cc88a);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
    }

    .auth-card {
      max-width: 950px;
      width: 100%;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 8px 25px rgba(0,0,0,0.2);
      background: #fff;
      animation: fadeInUp 0.8s ease;
    }

    /* Gambar kiri */
    .auth-image {
      background: url('https://images.unsplash.com/photo-1581090700227-1e37b190418e?auto=format&fit=crop&w=900&q=80') center/cover no-repeat;
      position: relative;
      min-height: 450px;
    }

    /* Overlay transparan biar elegan */
    .auth-image::after {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.35);
    }

    .auth-header h3 {
      font-weight: 700;
      color: #4e73df;
    }

    .auth-header p {
      color: #6c757d;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
      .auth-image {
        display: none;
      }
      .auth-card {
        max-width: 420px;
      }
    }
  </style>
</head>
<body>
