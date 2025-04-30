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
$sortable = ['kode_payment_method', 'nama_payment_method', 'deleted_at'];
if (!in_array($sort, $sortable)) $sort = 'deleted_at';
$order = ($order === 'asc') ? 'asc' : 'desc';

// Hitung total metode pembayaran yang dihapus (soft delete)
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM payment_methods WHERE deleted_at IS NOT NULL AND (kode_payment_method LIKE :search OR nama_payment_method LIKE :search)");
$countStmt->execute(['search' => "%$search%"]);
$total = $countStmt->fetchColumn();
$totalPages = ceil($total / $perPage);

// Ambil data metode pembayaran yang dihapus sesuai pencarian, sort dan pagination
$stmt = $pdo->prepare("
  SELECT * FROM payment_methods 
  WHERE deleted_at IS NOT NULL 
    AND (kode_payment_method LIKE :search OR nama_payment_method LIKE :search)
  ORDER BY $sort $order 
  LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$methods = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="mb-0 fw-semibold"><i class="bi bi-archive me-2"></i>Metode Terhapus</h5>
  <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<form class="mb-3" method="GET">
  <div class="input-group input-group-sm">
    <input type="text" name="search" class="form-control" placeholder="Cari metode yang dihapus..." value="<?= htmlspecialchars($search) ?>">
    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
  </div>
</form>

<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show my-4" role="alert" style="font-size: 0.875rem; border-radius: 0.5rem;">
    <strong>Success!</strong> <?= htmlspecialchars($_GET['success']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<div class="mb-3 d-flex justify-content-end align-items-center">
  <form action="destroy_all.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus permanen semua metode pembayaran yang telah dihapus?')">
    <button type="submit" class="btn btn-sm btn-danger">
      <i class="bi bi-x-octagon"></i> Hapus Semua Permanen
    </button>
  </form>
</div>

<div class="table-responsive bg-white rounded shadow-sm p-3">
  <?php if (count($methods) > 0): ?>
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
          <th><?= sort_link('Kode', 'kode_payment_method', $sort, $order, $search) ?></th>
          <th><?= sort_link('Nama Metode', 'nama_payment_method', $sort, $order, $search) ?></th>
          <th><?= sort_link('Dihapus Pada', 'deleted_at', $sort, $order, $search) ?></th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($methods as $index => $method): ?>
          <tr>
            <td><?= $offset + $index + 1 ?></td>
            <td><?= htmlspecialchars($method['kode_payment_method']) ?></td>
            <td><?= htmlspecialchars($method['nama_payment_method']) ?></td>
            <td><?= date('d M Y, H:i', strtotime($method['deleted_at'])) ?></td>
            <td class="text-end">
              <a href="restore.php?id=<?= $method['id'] ?>" class="btn btn-sm btn-light border" onclick="return confirm('Pulihkan metode ini?')">
                <i class="bi bi-arrow-counterclockwise text-success"></i>
              </a>
              <a href="destroy.php?id=<?= $method['id'] ?>" class="btn btn-sm btn-light border" title="Hapus Permanen" onclick="return confirm('Hapus permanen metode ini?')">
                <i class="bi bi-x-octagon text-danger"></i>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-secondary text-center mb-0">
      <i class="bi bi-info-circle"></i> Tidak ada data terhapus.
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
