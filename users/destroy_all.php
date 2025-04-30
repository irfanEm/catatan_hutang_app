<?php
require_once '../config/db.php';

// Hapus semua user kecuali user yang sedang login
session_start();
$currentUserId = $_SESSION['user_id'];

$stmt = $pdo->prepare("DELETE FROM users WHERE id != :id");
$stmt->execute(['id' => $currentUserId]);

header("Location: deleted.php?success=Semua user berhasil dihapus permanen, kecuali user yang sedang login.");
exit;
