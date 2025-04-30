<?php
session_start(); // Mulai session

// Cek apakah user sudah login
if (isset($_SESSION['user_id'])) {
    // Jika sudah login, redirect ke dashboard
    header('Location: dashboard.php');
    exit;
} else {
    // Jika belum login, redirect ke halaman login
    header('Location: auth/login.php');
    exit;
}
