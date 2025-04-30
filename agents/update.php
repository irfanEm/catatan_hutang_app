<?php
include '../config/db.php';

$id = $_POST['id'];
$kode_agen = $_POST['kode_agen'];
$nama_agen = $_POST['nama_agen'];

try {
    $stmt = $pdo->prepare("UPDATE agents SET kode_agen = ?, nama_agen = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$kode_agen, $nama_agen, $id]);
    header("Location: index.php?success=Data berhasil diupdate");
} catch (Exception $e) {
    header("Location: index.php?error=Gagal update data");
}
?>
