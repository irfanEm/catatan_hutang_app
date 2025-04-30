<?php
include '../config/db.php';

$id = $_GET['id'];

try {
    $stmt = $pdo->prepare("UPDATE payment_methods SET deleted_at = NULL WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: index.php?success=Data berhasil direstore");
} catch (Exception $e) {
    header("Location: index.php?error=Gagal restore data");
}
?>
