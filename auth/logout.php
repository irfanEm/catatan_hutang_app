<?php
session_start();
session_unset();     // Hapus semua data session
session_destroy();   // Hancurkan sesi

header('Location: login.php');
exit;
