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
        // Restore user dengan mengupdate kolom `deleted_at` menjadi NULL
        $stmt = $pdo->prepare("UPDATE users SET deleted_at = NULL WHERE id = ?");
        $stmt->execute([$userId]);

        // Redirect ke halaman deleted.php dengan pesan sukses
        header('Location: deleted.php?success=User%20berhasil%20direstore');
        exit;
    } else {
        // Jika user tidak ditemukan
        header('Location: deleted.php?error=User%20tidak%20ditemukan');
        exit;
    }
} else {
    // Jika ID tidak ada di URL
    header('Location: deleted.php?error=ID%20user%20tidak%20valid');
    exit;
}
