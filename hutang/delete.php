<?php
include '../config/db.php';

$id = $_GET['id'];

try {
    $stmt = $pdo->prepare("UPDATE hutang SET deleted_at = NOW() WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: index.php?success=Data berhasil dihapus");
} catch (Exception $e) {
    header("Location: index.php?error=Gagal menghapus data");
}
?>
