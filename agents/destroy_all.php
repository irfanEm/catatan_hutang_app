<?php
require_once '../config/db.php';

$stmt = $pdo->prepare("DELETE FROM agents WHERE deleted_at IS NOT NULL");
if ($stmt->execute()) {
    header("Location: deleted.php?success=Semua agen terhapus permanen.");
} else {
    header("Location: deleted.php?error=Gagal menghapus semua agen.");
}
exit;
