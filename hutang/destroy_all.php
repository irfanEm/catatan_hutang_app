<?php
require_once '../config/db.php';

// Hapus semua data yang sudah di-soft delete
$stmt = $pdo->prepare("DELETE FROM hutang WHERE deleted_at IS NOT NULL");
$stmt->execute();

header("Location: deleted.php?success=Semua data hutang terhapus permanen.");
exit;
