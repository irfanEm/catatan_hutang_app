<?php
require_once '../config/db.php';

if (!isset($_GET['id'])) {
  header('Location: deleted.php');
  exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("UPDATE agents SET deleted_at = NULL, updated_at = NOW() WHERE id = ?");
$stmt->execute([$id]);

header('Location: deleted.php');
exit;
