<?php include '../template/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="mb-0 fw-semibold"><i class="bi bi-person-plus me-2"></i>Tambah User</h5>
  <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php
require_once '../config/db.php';

$name = $email = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($name))     $errors[] = 'Nama wajib diisi.';
    if (empty($email))    $errors[] = 'Email wajib diisi.';
    if (empty($password)) $errors[] = 'Password wajib diisi.';

    $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetchColumn() > 0) {
        $errors[] = 'Email sudah terdaftar.';
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword]);
        header('Location: index.php?success=User berhasil ditambahkan');
        exit;
    }
}
?>

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
    <div class="mb-3">
      <label for="password" class="form-label small text-muted">Password</label>
      <input type="password" name="password" id="password" class="form-control form-control-sm" required>
    </div>
    <div class="d-flex justify-content-end mt-4">
      <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-save me-1"></i> Simpan</button>
    </div>
  </form>
</div>
<?php include '../template/footer.php'; ?>
