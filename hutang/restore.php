<?php
require '../config/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("UPDATE hutang SET deleted_at = NULL WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php?success=Hutang berhasil direstore");
