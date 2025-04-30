<?php
require_once '../config/db.php';

$stmt = $pdo->prepare("DELETE FROM payment_methods WHERE deleted_at IS NOT NULL");
if ($stmt->execute()) {
    header("Location: deleted.php?success=Semua metode pembayaran berhasil dihapus permanen.");
} else {
    header("Location: deleted.php?error=Gagal menghapus metode pembayaran.");
}
exit;
