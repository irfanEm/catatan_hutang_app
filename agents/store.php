<?php
include '../config/db.php';

$kode_agen = $_POST['kode_agen'];
$nama_agen = $_POST['nama_agen'];

try {
    $stmt = $pdo->prepare("INSERT INTO agents (kode_agen, nama_agen) VALUES (?, ?)");
    $stmt->execute([$kode_agen, $nama_agen]);
    header("Location: index.php?success=Data berhasil disimpan");
} catch (Exception $e) {
    header("Location: index.php?error=Gagal menyimpan data");
}
?>
