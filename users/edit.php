<?php
require_once '../config/db.php';
include '../template/header.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<div class='alert alert-danger'>User tidak ditemukan.</div>";
    include '../template/footer.php';
    exit;
}

$name = $user['name'];
$email = $user['email'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);

    if (empty($name)) $errors[] = 'Nama wajib diisi.';
    if (empty($email)) $errors[] = 'Email wajib diisi.';

    // Cek jika email sudah digunakan user lain
    $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND id != ?");
    $check->execute([$email, $id]);
    if ($check->fetchColumn() > 0) {
        $errors[] = 'Email sudah digunakan oleh user lain.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$name, $email, $id]);
        header('Location: index.php?success=User berhasil diperbarui');
        exit;
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="mb-0 fw-semibold"><i class="bi bi-pencil-square me-2"></i>Edit User</h5>
  <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger small">
    <ul class="mb-0 ps-3">
      <?php foreach ($errors as $error): ?>
        <li><?= htmlspecialchars($error) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="bg-white p-4 rounded shadow-sm">
  <form method="POST">
    <div class="mb-3">
      <label for="name" class="form-label small text-muted">Nama</label>
      <input type="text" name="name" id="name" class="form-control form-control-sm" value="<?= htmlspecialchars($name) ?>" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label small text-muted">Email</label>
      <input type="email" name="email" id="email" class="form-control form-control-sm" value="<?= htmlspecialchars($email) ?>" required>
    </div>
    <div class="d-flex justify-content-end mt-4">
      <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-save me-1"></i> Update</button>
    </div>
  </form>
</div>

<?php include '../template/footer.php'; ?>
