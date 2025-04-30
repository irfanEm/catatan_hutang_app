<?php
require '../config/db.php';

$debt_id = $_POST['debt_id'];
$agen_id = $_POST['agen_id'];
$payment_method_id = $_POST['payment_method_id'];
$tanggal_hutang = $_POST['tanggal_hutang'];
$tanggal_jatuh_tempo = $_POST['tanggal_jatuh_tempo'];
$sisa_hutang = $_POST['sisa_hutang'];
$now = date('Y-m-d H:i:s');

try {
    $stmt = $pdo->prepare("INSERT INTO hutang (debt_id, agen_id, payment_method_id, tanggal_hutang, tanggal_jatuh_tempo, sisa_hutang, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$debt_id, $agen_id, $payment_method_id, $tanggal_hutang, $tanggal_jatuh_tempo, $sisa_hutang, $now, $now]);
    header("Location: index.php?success=Hutang berhasil ditambahkan");
} catch (PDOException $e) {
    header("Location: index.php?error=Gagal menyimpan hutang: " . $e->getMessage());
}
