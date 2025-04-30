<?php
require_once '../config/db.php';
include '../template/header.php';

if (!isset($_GET['id'])) {
  header('Location: index.php');
  exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM hutang WHERE id = ?");
$stmt->execute([$id]);
$hutang = $stmt->fetch();

if (!$hutang) {
  echo "<div class='alert alert-danger'>Data hutang tidak ditemukan.</div>";
  include '../template/footer.php';
  exit;
}

$stmtAgents = $pdo->query("SELECT * FROM agents WHERE deleted_at IS NULL");
$agents = $stmtAgents->fetchAll();

$stmtPaymentMethods = $pdo->query("SELECT * FROM payment_methods WHERE deleted_at IS NULL");
$paymentMethods = $stmtPaymentMethods->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="mb-0 fw-semibold"><i class="bi bi-pencil-square me-2"></i>Edit Hutang</h5>
  <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="bg-white p-4 rounded shadow-sm">
  <form action="update.php" method="POST">
    <input type="hidden" name="id" value="<?= $hutang['id'] ?>">
    <div class="mb-3">
      <label for="debt_id" class="form-label small text-muted">Kode Hutang</label>
      <input type="text" name="debt_id" id="debt_id" class="form-control form-control-sm" required value="<?= htmlspecialchars($hutang['debt_id']) ?>">
    </div>
    <div class="mb-3">
      <label for="agen_id" class="form-label small text-muted">Pilih Agen</label>
      <select name="agen_id" id="agen_id" class="form-select form-select-sm" required>
        <option value="">Pilih Agen</option>
        <?php foreach ($agents as $agent): ?>
          <option value="<?= $agent['id'] ?>" <?= $hutang['agen_id'] == $agent['id'] ? 'selected' : '' ?>><?= htmlspecialchars($agent['nama_agen']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label for="payment_method_id" class="form-label small text-muted">Pilih Metode Pembayaran</label>
      <select name="payment_method_id" id="payment_method_id" class="form-select form-select-sm" required>
        <option value="">Pilih Metode Pembayaran</option>
        <?php foreach ($paymentMethods as $paymentMethod): ?>
          <option value="<?= $paymentMethod['id'] ?>" <?= $hutang['payment_method_id'] == $paymentMethod['id'] ? 'selected' : '' ?>><?= htmlspecialchars($paymentMethod['nama_payment_method']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label for="tanggal_hutang" class="form-label small text-muted">Tanggal Hutang</label>
      <input type="date" name="tanggal_hutang" id="tanggal_hutang" class="form-control form-control-sm" required value="<?= $hutang['tanggal_hutang'] ?>">
    </div>
    <div class="mb-3">
      <label for="tanggal_jatuh_tempo" class="form-label small text-muted">Tanggal Jatuh Tempo</label>
      <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" class="form-control form-control-sm" required value="<?= $hutang['tanggal_jatuh_tempo'] ?>">
    </div>
    <div class="mb-3">
      <label for="sisa_hutang" class="form-label small text-muted">Sisa Hutang</label>
      <input type="number" name="sisa_hutang" id="sisa_hutang" class="form-control form-control-sm" required value="<?= $hutang['sisa_hutang'] ?>">
    </div>
    <div class="d-flex justify-content-end mt-4">
      <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-save me-1"></i> Update</button>
    </div>
  </form>
</div>

<?php include '../template/footer.php'; ?>
