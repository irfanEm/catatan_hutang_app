<?php
session_start();
require_once '../config/db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // Validasi dasar
    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        $error_message = "Semua field wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Format email tidak valid.";
    } elseif ($password !== $confirm) {
        $error_message = "Konfirmasi password tidak cocok.";
    } else {
        // Cek apakah email sudah digunakan
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error_message = "Email sudah terdaftar.";
        } else {
            // Simpan user baru
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashed_password]);

            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_name'] = $name;

            header('Location: ../dashboard.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Catatan Hutang App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f3f5;
        }
        .register-container {
            max-width: 400px;
            margin: 80px auto;
            padding: 2rem;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }
        .form-control {
            font-size: 0.95rem;
            border-radius: 8px;
        }
        .btn-primary {
            width: 100%;
            border-radius: 8px;
        }
        .app-title {
            font-size: 1.4rem;
            font-weight: bold;
            color: #343a40;
            margin-bottom: 1rem;
            text-align: center;
        }
        .form-title {
            font-size: 1.1rem;
            font-weight: 500;
            color: #495057;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .login-link {
            font-size: 0.9rem;
            display: block;
            text-align: center;
            margin-top: 1rem;
        }
    </style>
</head>
<body>

<div class="register-container">
    <div class="app-title">Catatan Hutang App</div>
    <div class="form-title">Register</div>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger small"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label small text-muted">Nama Lengkap</label>
            <input type="text" class="form-control shadow-sm" id="name" name="name" required autofocus>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label small text-muted">Email</label>
            <input type="email" class="form-control shadow-sm" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label small text-muted">Password</label>
            <input type="password" class="form-control shadow-sm" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label small text-muted">Konfirmasi Password</label>
            <input type="password" class="form-control shadow-sm" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary shadow-sm">Daftar</button>
    </form>

    <a href="login.php" class="login-link text-primary text-decoration-none">Sudah punya akun? Login</a>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
