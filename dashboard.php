<?php include 'template/header.php'; ?>

<?php
$total_hutang = $pdo->query("SELECT COUNT(*) FROM hutang WHERE deleted_at IS NULL")->fetchColumn();
$total_agen = $pdo->query("SELECT COUNT(*) FROM agents WHERE deleted_at IS NULL")->fetchColumn();
$total_payment_methods = $pdo->query("SELECT COUNT(*) FROM payment_methods WHERE deleted_at IS NULL")->fetchColumn();
$total_sisa_hutang = $pdo->query("SELECT SUM(sisa_hutang) FROM hutang WHERE deleted_at IS NULL")->fetchColumn() ?: 0;
$today = date('Y-m-d');
$jatuh_tempo_hari_ini = $pdo->query("SELECT COUNT(*) FROM hutang WHERE tanggal_jatuh_tempo = '$today' AND deleted_at IS NULL")->fetchColumn();
$hutang_terlambat = $pdo->query("SELECT COUNT(*) FROM hutang WHERE tanggal_jatuh_tempo < '$today' AND sisa_hutang > 0 AND deleted_at IS NULL")->fetchColumn();

// Ambil data hutang 6 bulan terakhir
$monthly_data = [];
$months = [];

for ($i = 5; $i >= 0; $i--) {
    $month_label = date('M Y', strtotime("-$i months"));
    $month_key = date('Y-m', strtotime("-$i months"));
    $months[] = $month_label;

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM hutang WHERE DATE_FORMAT(tanggal_hutang, '%Y-%m') = ? AND deleted_at IS NULL");
    $stmt->execute([$month_key]);
    $monthly_data[] = $stmt->fetchColumn();
}

// Data Pie Chart: Lunas vs Belum
$stmt = $pdo->query("SELECT COUNT(*) FROM hutang WHERE sisa_hutang = 0 AND deleted_at IS NULL");
$lunas = $stmt->fetchColumn();
$stmt = $pdo->query("SELECT COUNT(*) FROM hutang WHERE sisa_hutang > 0 AND deleted_at IS NULL");
$belum_lunas = $stmt->fetchColumn();

?>

<style>
  .card-dashboard {
    border: none;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    transition: 0.3s ease-in-out;
  }

  .card-dashboard:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
  }

  .icon-circle {
    width: 50px;
    height: 50px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    color: white;
    font-size: 1.4rem;
  }

  .dashboard-header {
    font-weight: 600;
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    color: #333;
  }

  .stat-value {
    font-size: 1.3rem;
    font-weight: 600;
    color: #222;
  }

  .stat-subtext {
    font-size: 0.9rem;
    color: #777;
  }

  @media (max-width: 576px) {
    .dashboard-header {
      font-size: 1.4rem;
    }
  }
</style>

<div class="container-fluid px-4 py-4">
  <div class="dashboard-header">📊 Dashboard Ringkasan</div>

  <div class="row g-4">
    <div class="col-sm-6 col-lg-3">
      <div class="card card-dashboard h-100">
        <div class="card-body d-flex align-items-center">
          <div class="icon-circle bg-primary me-3"><i class="bi bi-cash-stack"></i></div>
          <div>
            <div class="stat-value"><?= $total_hutang ?> Transaksi</div>
            <div class="stat-subtext">Total Hutang</div>
            <div class="stat-subtext">Sisa Rp <?= number_format($total_sisa_hutang, 0, ',', '.') ?></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-lg-3">
      <div class="card card-dashboard h-100">
        <div class="card-body d-flex align-items-center">
          <div class="icon-circle bg-success me-3"><i class="bi bi-people-fill"></i></div>
          <div>
            <div class="stat-value"><?= $total_agen ?> Agen</div>
            <div class="stat-subtext"><?= $total_payment_methods ?> Metode Pembayaran</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-lg-3">
      <div class="card card-dashboard h-100">
        <div class="card-body d-flex align-items-center">
          <div class="icon-circle bg-warning text-dark me-3"><i class="bi bi-calendar-event"></i></div>
          <div>
            <div class="stat-value"><?= $jatuh_tempo_hari_ini ?> Jatuh Tempo</div>
            <div class="stat-subtext">Hari ini (<?= $today ?>)</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-lg-3">
      <div class="card card-dashboard h-100">
        <div class="card-body d-flex align-items-center">
          <div class="icon-circle bg-danger me-3"><i class="bi bi-exclamation-triangle"></i></div>
          <div>
            <div class="stat-value"><?= $hutang_terlambat ?> Terlambat</div>
            <div class="stat-subtext">Lewat Jatuh Tempo</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row mt-5">
  <div class="col-md-8 mb-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title">Grafik Hutang 6 Bulan Terakhir</h5>
        <canvas id="barChart" height="100"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-4 mb-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title">Status Hutang</h5>
        <canvas id="pieChart" height="200"></canvas>
      </div>
    </div>
  </div>
</div>

<?php include 'template/footer.php'; ?>
