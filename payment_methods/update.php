<?php
include '../config/db.php';

$id = $_POST['id'];
$kode_payment_method = $_POST['kode_payment_method'];
$nama_payment_method = $_POST['nama_payment_method'];

try {
    $stmt = $pdo->prepare("UPDATE payment_methods SET kode_payment_method = ?, nama_payment_method = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$kode_payment_method, $nama_payment_method, $id]);
    header("Location: index.php?success=Data berhasil diupdate");
} catch (Exception $e) {
    header("Location: index.php?error=Gagal update data");
}
?>
