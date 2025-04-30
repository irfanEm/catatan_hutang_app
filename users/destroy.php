<?php
require_once '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: deleted.php?error=ID tidak ditemukan.");
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
$stmt->execute(['id' => $id]);

header("Location: deleted.php?success=User berhasil dihapus permanen.");
exit;
