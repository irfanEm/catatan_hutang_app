<?php
include '../config/db.php';

$kode_payment_method = $_POST['kode_payment_method'];
$nama_payment_method = $_POST['nama_payment_method'];

try {
    $stmt = $pdo->prepare("INSERT INTO payment_methods (kode_payment_method, nama_payment_method) VALUES (?, ?)");
    $stmt->execute([$kode_payment_method, $nama_payment_method]);
    header("Location: index.php?success=Data berhasil disimpan");
} catch (Exception $e) {
    header("Location: index.php?error=Gagal menyimpan data");
}
?>
