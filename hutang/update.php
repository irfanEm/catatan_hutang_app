<?php
require '../config/db.php';

$id = $_POST['id'];
$debt_id = $_POST['debt_id'];
$agen_id = $_POST['agen_id'];
$payment_method_id = $_POST['payment_method_id'];
$tanggal_hutang = $_POST['tanggal_hutang'];
$tanggal_jatuh_tempo = $_POST['tanggal_jatuh_tempo'];
$sisa_hutang = $_POST['sisa_hutang'];
$now = date('Y-m-d H:i:s');

try {
    $stmt = $pdo->prepare("UPDATE hutang SET debt_id = ?, agen_id = ?, payment_method_id = ?, tanggal_hutang = ?, tanggal_jatuh_tempo = ?, sisa_hutang = ?, updated_at = ? WHERE id = ?");
    $stmt->execute([$debt_id, $agen_id, $payment_method_id, $tanggal_hutang, $tanggal_jatuh_tempo, $sisa_hutang, $now, $id]);
    header("Location: index.php?success=Hutang berhasil diupdate");
} catch (PDOException $e) {
    header("Location: index.php?error=Gagal update: " . $e->getMessage());
}
