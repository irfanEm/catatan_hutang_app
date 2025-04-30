<?php
session_start();
require_once '../config/db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error_message = "Email dan password wajib diisi.";
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: ../dashboard.php');
            exit;
        } else {
            $error_message = "Email atau password salah.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Catatan Hutang App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f3f5;
        }
        .login-container {
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
        .register-link {
            font-size: 0.9rem;
            display: block;
            text-align: center;
            margin-top: 1rem;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="app-title">Catatan Hutang App</div>
    <div class="form-title">Login</div>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger small"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label small text-muted">Email</label>
            <input type="email" class="form-control shadow-sm" id="email" name="email" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label small text-muted">Password</label>
            <input type="password" class="form-control shadow-sm" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary shadow-sm">Login</button>
    </form>

    <a href="register.php" class="register-link text-primary text-decoration-none">Belum punya akun? Daftar</a>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
