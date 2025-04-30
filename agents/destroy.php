<?php
require_once '../config/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: deleted.php?error=ID tidak valid");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM agents WHERE id = ?");
if ($stmt->execute([$id])) {
    header("Location: deleted.php?success=Agen berhasil dihapus permanen.");
} else {
    header("Location: deleted.php?error=Gagal menghapus agen.");
}
exit;
