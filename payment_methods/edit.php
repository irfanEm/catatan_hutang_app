<?php
require_once '../config/db.php';
include '../template/header.php';

if (!isset($_GET['id'])) {
  header('Location: index.php');
  exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM payment_methods WHERE id = ?");
$stmt->execute([$id]);
$method = $stmt->fetch();

if (!$method) {
  echo "<div class='alert alert-danger'>Metode pembayaran tidak ditemukan.</div>";
  include '../template/footer.php';
  exit;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="mb-0 fw-semibold"><i class="bi bi-pencil-square me-2"></i>Edit Metode</h5>
  <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="bg-white p-4 rounded shadow-sm">
  <form action="update.php" method="POST">
    <input type="hidden" name="id" value="<?= $method['id'] ?>">
    <div class="mb-3">
      <label for="kode_payment_method" class="form-label small text-muted">Kode Metode</label>
      <input type="text" name="kode_payment_method" id="kode_payment_method" class="form-control form-control-sm" required value="<?= htmlspecialchars($method['kode_payment_method']) ?>">
    </div>
    <div class="mb-3">
      <label for="nama_payment_method" class="form-label small text-muted">Nama Metode</label>
      <input type="text" name="nama_payment_method" id="nama_payment_method" class="form-control form-control-sm" required value="<?= htmlspecialchars($method['nama_payment_method']) ?>">
    </div>
    <div class="d-flex justify-content-end mt-4">
      <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-save me-1"></i> Update</button>
    </div>
  </form>
</div>

<?php include '../template/footer.php'; ?>
