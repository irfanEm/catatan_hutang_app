<?php
require_once '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: deleted.php?error=ID tidak ditemukan.");
    exit;
}

$id = $_GET['id'];

// Hapus permanen jika sudah dihapus sebelumnya (soft delete)
$stmt = $pdo->prepare("DELETE FROM hutang WHERE id = :id AND deleted_at IS NOT NULL");
$stmt->execute(['id' => $id]);

header("Location: deleted.php?success=Data hutang berhasil dihapus permanen.");
exit;
