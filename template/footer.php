</div> <!-- penutup container-fluid -->
</div> <!-- penutup d-flex -->
<script src="../assets/bootstrap5.3.5/css/bootstrap.min.css"></script>
<script src="../assets/bootstrap5.3.5/js/bootstrap.min.js"></script>
<script src="../assets/DataTables/datatables.min.js"></script>

<script>
  const barCtx = document.getElementById('barChart').getContext('2d');
  const barChart = new Chart(barCtx, {
    type: 'bar',
    data: {
      labels: <?= json_encode($months) ?>,
      datasets: [{
        label: 'Jumlah Hutang',
        data: <?= json_encode($monthly_data) ?>,
        backgroundColor: 'rgba(54, 162, 235, 0.6)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1,
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1 }
        }
      }
    }
  });

  const pieCtx = document.getElementById('pieChart').getContext('2d');
  const pieChart = new Chart(pieCtx, {
    type: 'pie',
    data: {
      labels: ['Lunas', 'Belum Lunas'],
      datasets: [{
        label: 'Status Hutang',
        data: [<?= $lunas ?>, <?= $belum_lunas ?>],
        backgroundColor: ['#198754', '#dc3545'],
        borderWidth: 0
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom'
        }
      }
    }
  });
</script>

</body>
</html>
