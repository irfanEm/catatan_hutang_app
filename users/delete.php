<?php
require_once '../config/db.php';

// Pastikan ID ada di URL dan valid
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Periksa apakah user ada di database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Lakukan soft delete dengan memperbarui kolom `deleted_at`
        $stmt = $pdo->prepare("UPDATE users SET deleted_at = NOW() WHERE id = ?");
        $stmt->execute([$userId]);

        // Redirect ke halaman daftar user dengan pesan sukses
        header('Location: index.php?success=User%20berhasil%20dihapus');
        exit;
    } else {
        // Jika user tidak ditemukan
        header('Location: index.php?error=User%20tidak%20ditemukan');
        exit;
    }
} else {
    // Jika ID tidak ada di URL
    header('Location: index.php?error=ID%20user%20tidak%20valid');
    exit;
}
