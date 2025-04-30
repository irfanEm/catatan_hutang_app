<?php
require_once '../config/db.php';
include '../template/header.php';

// Ambil parameter dari URL
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'h.deleted_at';
$order = $_GET['order'] ?? 'desc';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Validasi kolom yang bisa disortir
$sortable = ['debt_id', 'nama_agen', 'nama_payment_method', 'tanggal_hutang', 'tanggal_jatuh_tempo', 'sisa_hutang', 'h.deleted_at'];
if (!in_array($sort, $sortable)) $sort = 'h.deleted_at';
$order = ($order === 'asc') ? 'asc' : 'desc';

// Hitung total hutang yang dihapus
$countStmt = $pdo->prepare("
  SELECT COUNT(*) FROM hutang h 
  JOIN agents a ON h.agen_id = a.id 
  JOIN payment_methods p ON h.payment_method_id = p.id 
  WHERE h.deleted_at IS NOT NULL 
  AND (h.debt_id LIKE :search OR a.nama_agen LIKE :search OR p.nama_payment_method LIKE :search)
");
$countStmt->execute(['search' => "%$search%"]);
$total = $countStmt->fetchColumn();
$totalPages = ceil($total / $perPage);

// Ambil data hutang yang dihapus sesuai pencarian, sort dan pagination
$stmt = $pdo->prepare("
  SELECT h.*, a.nama_agen, p.nama_payment_method 
  FROM hutang h 
  JOIN agents a ON h.agen_id = a.id 
  JOIN payment_methods p ON h.payment_method_id = p.id 
  WHERE h.deleted_at IS NOT NULL 
  AND (h.debt_id LIKE :search OR a.nama_agen LIKE :search OR p.nama_payment_method LIKE :search)
  ORDER BY $sort $order 
  LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$hutang = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="mb-0 fw-semibold"><i class="bi bi-archive me-2"></i>Data Hutang Terhapus</h5>
  <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<form class="mb-3" method="GET">
  <div class="input-group input-group-sm">
    <input type="text" name="search" class="form-control" placeholder="Cari hutang yang terhapus..." value="<?= htmlspecialchars($search) ?>">
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
  <form action="destroy_all.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus permanen semua hutang yang telah dihapus?')">
    <button type="submit" class="btn btn-sm btn-danger">
      <i class="bi bi-x-octagon"></i> Hapus Semua Permanen
    </button>
  </form>
</div>

<div class="table-responsive bg-white rounded shadow-sm p-3">
  <?php if (count($hutang) > 0): ?>
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
          <th><?= sort_link('Kode Hutang', 'debt_id', $sort, $order, $search) ?></th>
          <th><?= sort_link('Agen', 'nama_agen', $sort, $order, $search) ?></th>
          <th><?= sort_link('Metode Bayar', 'nama_payment_method', $sort, $order, $search) ?></th>
          <th><?= sort_link('Tgl Hutang', 'tanggal_hutang', $sort, $order, $search) ?></th>
          <th><?= sort_link('Tgl Jatuh Tempo', 'tanggal_jatuh_tempo', $sort, $order, $search) ?></th>
          <th><?= sort_link('Sisa Hutang', 'sisa_hutang', $sort, $order, $search) ?></th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($hutang as $index => $h): ?>
          <tr>
            <td><?= $offset + $index + 1 ?></td>
            <td><?= htmlspecialchars($h['debt_id']) ?></td>
            <td><?= htmlspecialchars($h['nama_agen']) ?></td>
            <td><?= htmlspecialchars($h['nama_payment_method']) ?></td>
            <td><?= htmlspecialchars($h['tanggal_hutang']) ?></td>
            <td><?= htmlspecialchars($h['tanggal_jatuh_tempo']) ?></td>
            <td>Rp <?= number_format($h['sisa_hutang'], 0, ',', '.') ?></td>
            <td class="text-end">
              <a href="restore.php?id=<?= $h['id'] ?>" class="btn btn-sm btn-light border me-1" title="Kembalikan">
                <i class="bi bi-arrow-clockwise text-success"></i>
              </a>
              <a href="destroy.php?id=<?= $h['id'] ?>" class="btn btn-sm btn-light border" title="Hapus Permanen" onclick="return confirm('Yakin ingin menghapus permanen hutang ini?')">
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
