<?php
require_once '../config/db.php';
include '../template/header.php';

$stmtAgents = $pdo->query("SELECT * FROM agents WHERE deleted_at IS NULL");
$agents = $stmtAgents->fetchAll();

$stmtPaymentMethods = $pdo->query("SELECT * FROM payment_methods WHERE deleted_at IS NULL");
$paymentMethods = $stmtPaymentMethods->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="mb-0 fw-semibold"><i class="bi bi-plus-circle me-2"></i>Tambah Hutang</h5>
  <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="bg-white p-4 rounded shadow-sm">
  <form action="store.php" method="POST">
    <div class="mb-3">
      <label for="debt_id" class="form-label small text-muted">Kode Hutang</label>
      <input type="text" name="debt_id" id="debt_id" class="form-control form-control-sm" required>
    </div>
    <div class="mb-3">
      <label for="agen_id" class="form-label small text-muted">Pilih Agen</label>
      <select name="agen_id" id="agen_id" class="form-select form-select-sm" required>
        <option value="">Pilih Agen</option>
        <?php foreach ($agents as $agent): ?>
          <option value="<?= $agent['id'] ?>"><?= htmlspecialchars($agent['nama_agen']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label for="payment_method_id" class="form-label small text-muted">Pilih Metode Pembayaran</label>
      <select name="payment_method_id" id="payment_method_id" class="form-select form-select-sm" required>
        <option value="">Pilih Metode Pembayaran</option>
        <?php foreach ($paymentMethods as $paymentMethod): ?>
          <option value="<?= $paymentMethod['id'] ?>"><?= htmlspecialchars($paymentMethod['nama_payment_method']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label for="tanggal_hutang" class="form-label small text-muted">Tanggal Hutang</label>
      <input type="date" name="tanggal_hutang" id="tanggal_hutang" class="form-control form-control-sm" required>
    </div>
    <div class="mb-3">
      <label for="tanggal_jatuh_tempo" class="form-label small text-muted">Tanggal Jatuh Tempo</label>
      <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" class="form-control form-control-sm" required>
    </div>
    <div class="mb-3">
      <label for="sisa_hutang" class="form-label small text-muted">Sisa Hutang</label>
      <input type="number" name="sisa_hutang" id="sisa_hutang" class="form-control form-control-sm" required>
    </div>
    <div class="d-flex justify-content-end mt-4">
      <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-save me-1"></i> Simpan</button>
    </div>
  </form>
</div>

<?php include '../template/footer.php'; ?>
