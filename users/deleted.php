<?php
require_once '../config/db.php';
include '../template/header.php';

// Ambil parameter dari URL
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'deleted_at';
$order = $_GET['order'] ?? 'desc';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Validasi kolom yang bisa disortir
$sortable = ['name', 'email', 'deleted_at'];
if (!in_array($sort, $sortable)) $sort = 'deleted_at';
$order = ($order === 'asc') ? 'asc' : 'desc';

// Hitung total user yang dihapus (soft delete)
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE deleted_at IS NOT NULL AND (name LIKE :search OR email LIKE :search)");
$countStmt->execute(['search' => "%$search%"]);
$total = $countStmt->fetchColumn();
$totalPages = ceil($total / $perPage);

// Ambil data user yang dihapus sesuai pencarian, sort dan pagination
$stmt = $pdo->prepare("
  SELECT * FROM users 
  WHERE deleted_at IS NOT NULL 
    AND (name LIKE :search OR email LIKE :search)
  ORDER BY $sort $order 
  LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$deletedUsers = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="mb-0 fw-semibold"><i class="bi bi-archive me-2"></i>Data User Terhapus</h5>
  <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<form class="mb-3" method="GET">
  <div class="input-group input-group-sm">
    <input type="text" name="search" class="form-control" placeholder="Cari user yang dihapus..." value="<?= htmlspecialchars($search) ?>">
    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
  </div>
</form>

<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show my-4" role="alert">
    <strong>Success!</strong> <?= htmlspecialchars($_GET['success']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
  <div class="alert alert-danger alert-dismissible fade show my-4" role="alert">
    <strong>Error!</strong> <?= htmlspecialchars($_GET['error']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<div class="mb-3 d-flex justify-content-end align-items-center">
  <form action="destroy_all.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus permanen semua user yang telah dihapus?')">
    <button type="submit" class="btn btn-sm btn-danger">
      <i class="bi bi-x-octagon"></i> Hapus Semua Permanen
    </button>
  </form>
</div>

<div class="table-responsive bg-white rounded shadow-sm p-3">
  <?php if (count($deletedUsers) > 0): ?>
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
          <th><?= sort_link('Dihapus Pada', 'deleted_at', $sort, $order, $search) ?></th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($deletedUsers as $index => $user): ?>
          <tr>
            <td><?= $offset + $index + 1 ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= date('d M Y', strtotime($user['deleted_at'])) ?></td>
            <td class="text-end">
              <a href="restore.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-light border" title="Restore"><i class="bi bi-arrow-return-left text-success"></i></a>
              <a href="destroy.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-light border" title="Hapus Permanen" onclick="return confirm('Hapus permanen user ini?')">
                <i class="bi bi-x-octagon text-danger"></i>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-secondary text-center mb-0">
      <i class="bi bi-info-circle"></i> Tidak ada data user yang dihapus.
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

<?php include '../template/footer.php'; ?>
