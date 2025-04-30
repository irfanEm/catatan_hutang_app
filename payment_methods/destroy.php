<?php
require_once '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: deleted.php?error=ID tidak ditemukan.");
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM payment_methods WHERE id = :id AND deleted_at IS NOT NULL");
$stmt->execute(['id' => $id]);

header("Location: deleted.php?success=Data berhasil dihapus permanen.");
exit;
