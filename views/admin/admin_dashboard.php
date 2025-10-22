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
$stats = $dashboardController->getDashboardStats();
$dishOverview = $dashboardController->getDishOverview();
$mostOrdered = $dishOverview['most_ordered'];
$recommended = $dishOverview['recommended'];


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
      max-height: 300px;
      width: 100% !important;
  }
  
  /* Improved analytics cards styling */
  .analytics-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 20px;
    color: white;
    margin-bottom: 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
  }
  
  .analytics-card:hover {
    transform: translateY(-5px);
  }
  
  .analytics-card h4 {
    color: white;
    margin-bottom: 15px;
    font-weight: 600;
  }
  
  .dish-item {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 10px;
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s ease;
  }
  
  .dish-item:hover {
    background: rgba(255,255,255,0.2);
    transform: scale(1.02);
  }
  
  .dish-name {
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 5px;
  }
  
  .dish-orders {
    opacity: 0.9;
    font-size: 14px;
  }
  
  .orders-badge {
    background: rgba(255,255,255,0.3);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    float: right;
    margin-top: -5px;
  }
  
  /* Revenue analytics card */
  .revenue-card {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  }
  
  /* Popular times card */
  .times-card {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
  }
  
  /* Customer insights card */
  .customer-card {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
  }
  
  .stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 5px;
  }
  
  .stat-label {
    opacity: 0.9;
    font-size: 14px;
  }
  
  .mini-stat {
    text-align: center;
    margin-bottom: 15px;
  }
  
  .time-slot {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid rgba(255,255,255,0.1);
  }
  
  .time-slot:last-child {
    border-bottom: none;
  }
  
  .progress-bar-custom {
    height: 6px;
    background: rgba(255,255,255,0.3);
    border-radius: 3px;
    overflow: hidden;
    margin-top: 5px;
  }
  
  .progress-fill {
    height: 100%;
    background: white;
    border-radius: 3px;
    transition: width 0.3s ease;
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
                <div class="card-body"><?= htmlspecialchars($stats['total_customers']); ?></div>
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
                <div class="card-body"><?= htmlspecialchars($stats['total_bookings']); ?></div>
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
                <div class="card-body"><?= htmlspecialchars($stats['pending_bookings']); ?></div>
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
                <div class="card-body"><?= htmlspecialchars($stats['completed_bookings']); ?></div>
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

                 <div class="row mt-4">
                <div class="col-lg-6 col-md-12 col-12">
                  <div class="analytics-card">
                    <h4><i class="fas fa-trophy"></i> Most Ordered Dishes</h4>
                    <div class="dishes-container">
                      <?php if (!empty($dishOverview['most_ordered'])): ?>
                        <?php foreach ($dishOverview['most_ordered'] as $index => $dish): ?>
                          <div class="dish-item">
                            <div class="dish-name">
                              <i class="fas fa-medal" style="color: <?= $index == 0 ? '#FFD700' : ($index == 1 ? '#C0C0C0' : '#CD7F32') ?>"></i>
                              <?= htmlspecialchars($dish['name']) ?>
                              <span class="orders-badge"><?= htmlspecialchars($dish['total_ordered']) ?> orders</span>
                            </div>
                            <div class="progress-bar-custom">
                              <div class="progress-fill" style="width: <?= ($dish['total_ordered'] / $dishOverview['most_ordered'][0]['total_ordered']) * 100 ?>%"></div>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <div class="dish-item">
                          <div class="dish-name">No orders found yet</div>
                          <div class="dish-orders">Start taking orders to see analytics!</div>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>

                <div class="col-lg-6 col-md-12 col-12">
                  <div class="analytics-card revenue-card">
                    <h4><i class="fas fa-chart-line"></i> Dish Revenue Analytics</h4>
                    <div class="mini-stat">
                      <div class="stat-number" id="topDishRevenue">₱0</div>
                      <div class="stat-label">Top Dish Revenue</div>
                    </div>
                    <div class="dish-revenue-list" id="dishRevenueList">
                      <!-- This will be populated by JavaScript -->
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-lg-4 col-md-12 col-12">
                  <div class="analytics-card times-card">
                    <h4><i class="fas fa-clock"></i> Popular Booking Times</h4>
                    <div id="popularTimes">
                      <!-- Populated by JavaScript -->
                    </div>
                  </div>
                </div>

                <div class="col-lg-4 col-md-12 col-12">
                  <div class="analytics-card customer-card">
                    <h4><i class="fas fa-users"></i> Customer Insights</h4>
                    <div class="mini-stat">
                      <div class="stat-number" id="avgOrderValue">₱0</div>
                      <div class="stat-label">Avg Order Value</div>
                    </div>
                    <div class="mini-stat">
                      <div class="stat-number" id="repeatCustomers">0%</div>
                      <div class="stat-label">Repeat Customers</div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-4 col-md-12 col-12">
                  <div class="analytics-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <h4><i class="fas fa-calendar-alt"></i> Booking Trends</h4>
                    <div class="mini-stat">
                      <div class="stat-number" id="avgGuestsPerBooking">0</div>
                      <div class="stat-label">Avg Guests per Booking</div>
                    </div>
                    <div class="mini-stat">
                      <div class="stat-number" id="peakBookingDay">-</div>
                      <div class="stat-label">Peak Booking Day</div>
                    </div>
                  </div>
                </div>
              </div>

      <script>

      document.addEventListener("DOMContentLoaded", function () {
          fetchAnalyticsData();
      });

      function fetchAnalyticsData() {

          fetch('../../controllers/DashboardController.php?dishAnalytics=true')
              .then(response => response.json())
              .then(data => {
                  if (data.topDishRevenue) {
                      document.getElementById("topDishRevenue").innerText = "₱" + data.topDishRevenue;
                  }
                  

                  if (data.dishRevenue && data.dishRevenue.length > 0) {
                      let revenueHtml = '';
                      data.dishRevenue.slice(0, 3).forEach(dish => {
                          revenueHtml += `
                              <div class="time-slot">
                                  <span>${dish.name}</span>
                                  <span>₱${dish.revenue}</span>
                              </div>
                          `;
                      });
                      document.getElementById("dishRevenueList").innerHTML = revenueHtml;
                  }
              })
              .catch(error => console.error("Error loading dish analytics:", error));


          fetch('../../controllers/DashboardController.php?bookingAnalytics=true')
              .then(response => response.json())
              .then(data => {
                  // Update popular times
                  if (data.popularTimes && data.popularTimes.length > 0) {
                      let timesHtml = '';
                      data.popularTimes.forEach(time => {
                          timesHtml += `
                              <div class="time-slot">
                                  <span>${time.time_slot}</span>
                                  <span>${time.booking_count} bookings</span>
                              </div>
                          `;
                      });
                      document.getElementById("popularTimes").innerHTML = timesHtml;
                  }
                  

                  if (data.avgOrderValue) {
                      document.getElementById("avgOrderValue").innerText = "₱" + data.avgOrderValue;
                  }
                  if (data.repeatCustomers) {
                      document.getElementById("repeatCustomers").innerText = data.repeatCustomers + "%";
                  }
                  

                  if (data.avgGuestsPerBooking) {
                      document.getElementById("avgGuestsPerBooking").innerText = data.avgGuestsPerBooking;
                  }
                  if (data.peakBookingDay) {
                      document.getElementById("peakBookingDay").innerText = data.peakBookingDay;
                  }
              })
              .catch(error => console.error("Error loading booking analytics:", error));
      }
      </script>
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

                document.getElementById("todaySales").innerText = "₱" + data.today;
                document.getElementById("weekSales").innerText = "₱" + data.week;
                document.getElementById("monthSales").innerText = "₱" + data.month;
                document.getElementById("yearSales").innerText = "₱" + data.year;

                const ctx = document.getElementById("salesChart").getContext("2d");

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
    fetchSalesData();
});

</script>
</body>
</html>