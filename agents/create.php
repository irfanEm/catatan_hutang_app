<?php include '../template/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="mb-0 fw-semibold"><i class="bi bi-person-plus me-2"></i>Tambah Agen</h5>
  <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="bg-white p-4 rounded shadow-sm">
  <form action="store.php" method="POST">
    <div class="mb-3">
      <label for="kode_agen" class="form-label small text-muted">Kode Agen</label>
      <input type="text" name="kode_agen" id="kode_agen" class="form-control form-control-sm" required>
    </div>
    <div class="mb-3">
      <label for="nama_agen" class="form-label small text-muted">Nama Agen</label>
      <input type="text" name="nama_agen" id="nama_agen" class="form-control form-control-sm" required>
    </div>
    <div class="d-flex justify-content-end mt-4">
      <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-save me-1"></i> Simpan</button>
    </div>
  </form>
</div>
<?php include '../template/footer.php'; ?>

