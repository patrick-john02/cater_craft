<?php

session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type_id'] != 2) {
    header("Location: ../../public/login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/DashboardController.php';

$pdo = Database::getConnection();
$dashboardController = new DashboardController($pdo);
$stats = $dashboardController->getDashboardStats();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Cater | Craft Admin</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/jqvmap/dist/jqvmap.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/weather-icon/css/weather-icons.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/weather-icon/css/weather-icons-wind.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/summernote/summernote-bs4.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/css/style.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/css/components.css">
<!-- Start GA -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-94034622-3');
</script>
<style>
  #salesChart {
      max-height: 300px; /* 🔹 Prevent excessive stretching */
      width: 100% !important; /* Ensure full width */
  }
</style>
<!-- /END GA --></head>
<body>
  <div id="app">
  <div class="main-wrapper main-wrapper-1">
<?php include 'includes/navbar.php'?>
<?php include 'includes/sidebar.php'?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Dashboard</h1>
          </div>
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="far fa-user"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Customers</h4>
                </div>
                <div class="card-body"><?= $stats['total_customers']; ?></div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                  <i class="far fa-newspaper"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Bookings</h4>
                </div>
                <div class="card-body"><?= $stats['total_bookings']; ?></div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                  <i class="far fa-file"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Pending Bookings</h4>
                </div>
                <div class="card-body"><?= $stats['pending_bookings']; ?></div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                  <i class="fas fa-circle"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Completed Bookings</h4>
                </div>
                <div class="card-body"><?= $stats['completed_bookings']; ?></div>
                </div>
              </div>
            </div>            
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>Booking Stats</h4>
                  <div class="card-header-action">
                  
                  </div>
                </div>
                <div class="card-body">
                <canvas id="salesChart" height="182"></canvas>
                  <div class="statistic-details mt-sm-4">
    <div class="statistic-details-item">
        <span class="text-muted"><span class="text-primary"><i class="fas fa-caret-up"></i></span> 7%</span>
        <div class="detail-value" id="todaySales">₱0</div>
        <div class="detail-name">Today's Sales</div>
    </div>
    <div class="statistic-details-item">
        <span class="text-muted"><span class="text-danger"><i class="fas fa-caret-down"></i></span> 23%</span>
        <div class="detail-value" id="weekSales">₱0</div>
        <div class="detail-name">This Week's Sales</div>
    </div>
    <div class="statistic-details-item">
        <span class="text-muted"><span class="text-primary"><i class="fas fa-caret-up"></i></span>9%</span>
        <div class="detail-value" id="monthSales">₱0</div>
        <div class="detail-name">This Month's Sales</div>
    </div>
    <div class="statistic-details-item">
        <span class="text-muted"><span class="text-primary"><i class="fas fa-caret-up"></i></span> 19%</span>
        <div class="detail-value" id="yearSales">₱0</div>
        <div class="detail-name">This Year's Sales</div>
    </div>
</div>

                </div>
              </div>
            </div>
           
          </div>
          
                
              

             
            
          </div>
          
        </section>
      </div>
    </div>
  </div>
  
  <!-- General JS Scripts -->
  <script src="../../assets/admin/cater-admin/assets/modules/jquery.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/popper.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/tooltip.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/moment.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/stisla.js"></script>
  
  <!-- JS Libraies -->
  <script src="../../assets/admin/cater-admin/assets/modules/simple-weather/jquery.simpleWeather.min.js"></script>
  <!-- <script src="../../assets/admin/cater-admin/assets/modules/chart.min.js"></script> -->
  <script src="../../assets/admin/cater-admin/assets/modules/jqvmap/dist/jquery.vmap.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/jqvmap/dist/maps/jquery.vmap.world.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/summernote/summernote-bs4.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/chocolat/dist/js/jquery.chocolat.min.js"></script>

  <!-- Page Specific JS File -->
  <!-- <script src="../../assets/admin/cater-admin/assets/js/page/index-0.js"></script> -->
  
  <!-- Template JS File -->
  <script src="../../assets/admin/cater-admin/assets/js/scripts.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/custom.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let salesChart = null; // 🔹 Keep chart instance outside to persist

    function fetchSalesData() {
        fetch('../../controllers/DashboardController.php?salesData=true')
            .then(response => response.json())
            .then(data => {
                // ✅ Update sales statistics
                document.getElementById("todaySales").innerText = "₱" + data.today;
                document.getElementById("weekSales").innerText = "₱" + data.week;
                document.getElementById("monthSales").innerText = "₱" + data.month;
                document.getElementById("yearSales").innerText = "₱" + data.year;

                // ✅ Render Chart
                const ctx = document.getElementById("salesChart").getContext("2d");

                // 🔹 Destroy existing chart before creating a new one
                if (salesChart !== null) {
                    salesChart.destroy();
                }

                // 🔹 Create new chart instance
                salesChart = new Chart(ctx, {
    type: "bar",
    data: {
        labels: ["Today", "This Week", "This Month", "This Year"],
        datasets: [{
            label: "Sales Data",
            data: [data.today, data.week, data.month, data.year],
            backgroundColor: ["#007bff", "#dc3545", "#ffc107", "#28a745"],
            borderColor: ["#0056b3", "#a71d2a", "#d39e00", "#19692c"],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true, // ✅ Keeps aspect ratio
        aspectRatio: 2, // ✅ Adjusts width-to-height ratio
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

            })
            .catch(error => console.error("Error loading sales data:", error));
    }

    // ✅ Call the function once when the page loads
    fetchSalesData();
});

</script>
</body>
</html>