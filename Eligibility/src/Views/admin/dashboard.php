<?php include __DIR__ . '/../layouts/admin-header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6"><h1 class="m-0">Admin Dashboard</h1></div>
    </div>
  </div>
</div>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Info boxes -->
    <div class="row">
      <div class="col-lg-3 col-6">
        <div class="small-box bg-info"><div class="inner"><h3><?php echo $stats['total_students']; ?></h3><p>Total Students</p></div><div class="icon"><i class="fas fa-user-graduate"></i></div></div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-success"><div class="inner"><h3><?php echo $stats['total_staff']; ?></h3><p>Total Staff</p></div><div class="icon"><i class="fas fa-user-tie"></i></div></div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-warning"><div class="inner"><h3><?php echo $stats['total_courses']; ?></h3><p>Total Courses</p></div><div class="icon"><i class="fas fa-book-open"></i></div></div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-danger"><div class="inner"><h3><?php echo $stats['total_attempts']; ?></h3><p>Total Test Attempts</p></div><div class="icon"><i class="fas fa-file-signature"></i></div></div>
      </div>
    </div>

    <!-- Chart Section -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header"><h3 class="card-title">Test Attempts (Last 7 Days)</h3></div>
          <div class="card-body">
            <div class="chart"><canvas id="attemptsChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>
<!-- ADD ChartJS SCRIPT -->
<script src="assets/plugins/chart.js/Chart.min.js"></script>
<script>
$(function () {
  var chartData = <?php echo json_encode($chart_data); ?>;
  var labels = [];
  var dataPoints = [];

  // Create a map of the last 7 days to ensure all days are shown, even with 0 attempts
  var dateMap = new Map();
  for (var i = 6; i >= 0; i--) {
    var d = new Date();
    d.setDate(d.getDate() - i);
    var dateString = d.toISOString().split('T')[0]; // Format as YYYY-MM-DD
    dateMap.set(dateString, 0);
  }
  
  // Populate the map with actual data from the server
  chartData.forEach(function(item) {
    dateMap.set(item.attempt_date, item.attempt_count);
  });
  
  // Convert the map to arrays for the chart
  dateMap.forEach(function(value, key) {
    labels.push(new Date(key).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
    dataPoints.push(value);
  });

  var attemptsChartCanvas = $('#attemptsChart').get(0).getContext('2d');
  var attemptsChartData = {
    labels: labels,
    datasets: [
      {
        label: 'Test Attempts',
        backgroundColor: 'rgba(60,141,188,0.9)',
        borderColor: 'rgba(60,141,188,0.8)',
        data: dataPoints
      }
    ]
  };
  var attemptsChart = new Chart(attemptsChartCanvas, {
  type: 'line',
  data: attemptsChartData,
  options: { 
    maintainAspectRatio: false, 
    responsive: true,
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          stepSize: 1   // ensures whole numbers for attempts
        }
      }
    },
    plugins: {
      legend: {
        display: true,
        position: 'top'
      }
    },
    elements: {
      line: {
        tension: 0.3,         // smooth curve (0 = straight lines)
        borderWidth: 3
      },
      point: {
        radius: 5,
        backgroundColor: 'rgba(60,141,188,1)',
        borderWidth: 2
      }
    }
  }
});
});
</script>