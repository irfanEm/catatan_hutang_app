<?php
require_once '../config/db.php';
include '../template/header.php';

// Ambil parameter dari URL
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'created_at';
$order = $_GET['order'] ?? 'desc';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Validasi kolom yang bisa disortir
$sortable = ['name', 'email', 'created_at'];
if (!in_array($sort, $sortable)) $sort = 'created_at';
$order = ($order === 'asc') ? 'asc' : 'desc';

// Hitung total user (dengan pencarian)
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE deleted_at IS NULL AND (name LIKE :search OR email LIKE :search)");
$countStmt->execute(['search' => "%$search%"]);
$total = $countStmt->fetchColumn();
$totalPages = ceil($total / $perPage);

// Ambil data user sesuai pencarian, sort dan pagination
$stmt = $pdo->prepare("
  SELECT * FROM users 
  WHERE deleted_at IS NULL 
    AND (name LIKE :search OR email LIKE :search)
  ORDER BY $sort $order 
  LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="mb-0 fw-semibold"><i class="bi bi-person-lines-fill me-2"></i>Data User</h5>
  <a href="create.php" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i> Tambah User</a>
</div>

<form class="mb-3" method="GET">
  <div class="input-group input-group-sm">
    <input type="text" name="search" class="form-control" placeholder="Cari user..." value="<?= htmlspecialchars($search) ?>">
    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
  </div>
</form>

<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show my-4" role="alert">
    <strong>Success!</strong> <?= htmlspecialchars($_GET['success']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<div class="table-responsive bg-white rounded shadow-sm p-3">
  <?php if (count($users) > 0): ?>
    <table class="table table-borderless table-hover align-middle small">
      <thead class="text-muted border-bottom">
        <tr>
          <th>#</th>
          <?php
          function sort_link($label, $column, $sort, $order, $search) {
              $newOrder = ($sort == $column && $order == 'asc') ? 'desc' : 'asc';
              $icon = '';
              if ($sort == $column) {
                  $icon = $order == 'asc' ? '▲' : '▼';
              }
              $url = "?search=$search&sort=$column&order=$newOrder";
              return "<a href=\"$url\" class=\"text-decoration-none text-dark\">$label $icon</a>";
          }
          ?>
          <th><?= sort_link('Nama User', 'name', $sort, $order, $search) ?></th>
          <th><?= sort_link('Email', 'email', $sort, $order, $search) ?></th>
          <th><?= sort_link('Dibuat Pada', 'created_at', $sort, $order, $search) ?></th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $index => $user): ?>
          <tr>
            <td><?= $offset + $index + 1 ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
            <td class="text-end">
              <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-light border me-1" title="Edit"><i class="bi bi-pencil text-warning"></i></a>
              <a href="delete.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-light border" title="Hapus" onclick="return confirm('Hapus user ini?')"><i class="bi bi-trash text-danger"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-secondary text-center mb-0">
      <i class="bi bi-info-circle"></i> Tidak ada data user saat ini.
    </div>
  <?php endif; ?>
</div>

<?php if ($totalPages > 1): ?>
<nav class="mt-3">
  <ul class="pagination pagination-sm">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
        <a class="page-link" href="?search=<?= $search ?>&sort=<?= $sort ?>&order=<?= $order ?>&page=<?= $i ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>
  </ul>
</nav>
<?php endif; ?>

<div class="mt-3">
  <a href="deleted.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-archive"></i> Data User Terhapus</a>
</div>

<?php include '../template/footer.php'; ?>
