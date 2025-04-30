<?php include __DIR__ . '/../config/db.php';
session_start();

// Cek apakah pengguna sudah login, jika tidak, arahkan ke halaman login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Aplikasi Hutang</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../assets/bootstrap5.3.5/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/bootstrap5.3.5/icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/DataTables/datatables.min.css" rel="stylesheet">
  <!-- <script src="../node_modules/chart.js/dist/chart.umd.js"></script> -->
  <script src="../assets/chart.umd.js"></script>
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .card .card-title {
      font-size: 1.5rem;
    }
    .dashboard-icon {
      font-size: 2.2rem;
      opacity: 0.2;
      position: absolute;
      top: 10px;
      right: 15px;
    }
    .nav-link:hover {
      background-color: rgba(255,255,255,0.1);
    }

    .nav-link:hover {
        background-color: #f8f9fa;
        border-radius: 6px;
        transition: 0.2s;
    }

    .nav-link.active {
        background-color: #e9ecef;
        border-left: 4px solid #0d6efd;
        border-radius: 6px;
    }

  </style>
  
</head>
<body>
<div class="d-flex">
<!-- Sidebar -->
<aside class="bg-white shadow-sm border-end" style="width: 240px; min-height: 100vh; position: fixed; top: 0; left: 0;">
    <div class="p-4 border-bottom">
        <h5 class="fw-bold text-primary mb-0">💰 Catatan Hutang</h5>
    </div>
    <ul class="nav flex-column p-3">
    <li class="nav-item mb-2">
        <a href="/dashboard.php" class="nav-link text-dark d-flex align-items-center gap-2 <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active fw-semibold' : '' ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item mb-2">
        <a href="/agents/index.php" class="nav-link text-dark d-flex align-items-center gap-2 <?= strpos($_SERVER['REQUEST_URI'], '/agents') !== false ? 'active fw-semibold' : '' ?>">
            <i class="bi bi-person-lines-fill"></i> Agen
        </a>
    </li>
    <li class="nav-item mb-2">
        <a href="/payment_methods/index.php" class="nav-link text-dark d-flex align-items-center gap-2 <?= strpos($_SERVER['REQUEST_URI'], '/payment_methods') !== false ? 'active fw-semibold' : '' ?>">
            <i class="bi bi-credit-card"></i> Metode Bayar
        </a>
    </li>
    <li class="nav-item mb-2">
        <a href="/hutang/index.php" class="nav-link text-dark d-flex align-items-center gap-2 <?= strpos($_SERVER['REQUEST_URI'], '/hutang') !== false ? 'active fw-semibold' : '' ?>">
            <i class="bi bi-journal-text"></i> Hutang
        </a>
    </li>
    <li class="nav-item mb-2">
        <a href="/users/index.php" class="nav-link text-dark d-flex align-items-center gap-2 <?= strpos($_SERVER['REQUEST_URI'], '/users') !== false ? 'active fw-semibold' : '' ?>">
            <i class="bi bi-person-circle"></i> Users
        </a>
    </li>
    <li class="nav-item mt-4">
        <a href="/auth/logout.php" class="nav-link text-danger d-flex align-items-center gap-2">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </li>
</ul>

</aside>

<main class="container-fluid p-4" style="margin-left: 240px;">


