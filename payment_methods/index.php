<?php
require_once '../config/db.php';
include '../template/header.php';

// Ambil parameter
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'created_at';
$order = $_GET['order'] ?? 'desc';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Kolom yang bisa disortir
$sortable = ['kode_payment_method', 'nama_payment_method', 'created_at'];
if (!in_array($sort, $sortable)) $sort = 'created_at';
$order = ($order === 'asc') ? 'asc' : 'desc';

// Hitung total data
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM payment_methods WHERE deleted_at IS NULL AND (kode_payment_method LIKE :search OR nama_payment_method LIKE :search)");
$countStmt->execute(['search' => "%$search%"]);
$total = $countStmt->fetchColumn();
$totalPages = ceil($total / $perPage);

// Ambil data dengan limit dan offset
$stmt = $pdo->prepare("
  SELECT * FROM payment_methods 
  WHERE deleted_at IS NULL 
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
  <h5 class="mb-0 fw-semibold"><i class="bi bi-credit-card-2-front me-2"></i>Metode Pembayaran</h5>
  <a href="create.php" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i> Tambah</a>
</div>

<form class="mb-3" method="GET">
  <div class="input-group input-group-sm">
    <input type="text" name="search" class="form-control" placeholder="Cari metode..." value="<?= htmlspecialchars($search) ?>">
    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
  </div>
</form>

<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show my-4" role="alert" style="font-size: 0.875rem; border-radius: 0.5rem;">
    <strong>Success!</strong> <?= htmlspecialchars($_GET['success']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

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
          <th><?= sort_link('Dibuat', 'created_at', $sort, $order, $search) ?></th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($methods as $index => $method): ?>
          <tr>
            <td><?= $offset + $index + 1 ?></td>
            <td><?= htmlspecialchars($method['kode_payment_method']) ?></td>
            <td><?= htmlspecialchars($method['nama_payment_method']) ?></td>
            <td><?= date('d M Y', strtotime($method['created_at'])) ?></td>
            <td class="text-end">
              <a href="edit.php?id=<?= $method['id'] ?>" class="btn btn-sm btn-light border me-1" title="Edit"><i class="bi bi-pencil text-warning"></i></a>
              <a href="delete.php?id=<?= $method['id'] ?>" class="btn btn-sm btn-light border" title="Hapus" onclick="return confirm('Hapus metode ini?')"><i class="bi bi-trash text-danger"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-secondary text-center mb-0">
      <i class="bi bi-info-circle"></i> Tidak ada metode pembayaran.
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
  <a href="deleted.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-archive"></i> Data Terhapus</a>
</div>

<?php include '../template/footer.php'; ?>
