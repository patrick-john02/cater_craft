<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/AdminManageBooking.php';

$bookingModel = new ManageBooking();

// Get filter parameters
$statusFilter = $_GET['status'] ?? '';
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';
$searchTerm = $_GET['search'] ?? '';

// Get filtered bookings
$bookings = $bookingModel->getFilteredBookings($statusFilter, $dateFrom, $dateTo, $searchTerm);
if (!is_array($bookings)) {
    $bookings = [];
}

// Get all statuses for filter dropdown
$statuses = $bookingModel->getAllStatuses();
if (!is_array($statuses)) {
    $statuses = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Cater Bookings</title>
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/datatables/datatables.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/css/style.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/css/components.css">
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-94034622-3');
</script>
</head>
<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
    <?php include 'includes/navbar.php'?>
    <?php include 'includes/sidebar.php'?>
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Booking Management</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="admin_dashboard.php">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Bookings</a></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Filter Bookings</h4>
                  </div>
                  <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" class="row mb-4">
                      <div class="col-md-3">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                          <option value="">All Statuses</option>
                          <?php foreach ($statuses as $status): ?>
                            <option value="<?= htmlspecialchars($status['id']); ?>" 
                                    <?= ($statusFilter == $status['id']) ? 'selected' : ''; ?>>
                              <?= htmlspecialchars($status['status']); ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label for="date_from">Date From</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" 
                               value="<?= htmlspecialchars($dateFrom); ?>">
                      </div>
                      <div class="col-md-2">
                        <label for="date_to">Date To</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" 
                               value="<?= htmlspecialchars($dateTo); ?>">
                      </div>
                      <div class="col-md-3">
                        <label for="search">Search</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Customer name, venue..." value="<?= htmlspecialchars($searchTerm); ?>">
                      </div>
                      <div class="col-md-2">
                        <label>&nbsp;</label>
                        <div class="d-flex">
                          <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Filter
                          </button>
                          <a href="?" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear
                          </a>
                        </div>
                      </div>
                    </form>

                    <!-- Results Summary -->
                    <div class="alert alert-info">
                      <i class="fas fa-info-circle"></i> 
                      Showing <?= count($bookings); ?> booking(s)
                      <?php if ($statusFilter || $dateFrom || $dateTo || $searchTerm): ?>
                        with applied filters
                      <?php endif; ?>
                    </div>

                    <!-- Bookings Table -->
                    <div class="table-responsive">
                      <table class="table table-striped" id="bookingsTable">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Customer Name</th>
                            <th>Booking Date</th>
                            <th>Event Date</th>
                            <th>Venue</th>
                            <th>Guests</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (count($bookings) > 0): ?>
                            <?php foreach ($bookings as $index => $booking): ?>
                              <tr>
                                <td><?= $index + 1; ?></td>
                                <td><?= htmlspecialchars($booking['customer_name']); ?></td>
                                <td><?= htmlspecialchars(date('M d, Y', strtotime($booking['created_at']))); ?></td>
                                <td><?= htmlspecialchars(date('M d, Y', strtotime($booking['event_date']))); ?></td>
                                <td><?= htmlspecialchars($booking['venue'] ?? ''); ?></td>

                                <td><?= htmlspecialchars($booking['guests']); ?></td>
                                <td>₱<?= number_format($booking['total_amount'], 2); ?></td>
                                <td>
                                  <?php
                                  $statusClass = 'badge-secondary';
                                  switch(strtolower($booking['status'])) {
                                    case 'pending':
                                      $statusClass = 'badge-warning';
                                      break;
                                    case 'confirmed':
                                      $statusClass = 'badge-success';
                                      break;
                                    case 'cancelled':
                                      $statusClass = 'badge-danger';
                                      break;
                                    case 'completed':
                                      $statusClass = 'badge-info';
                                      break;
                                  }
                                  ?>
                                  <div class="badge <?= $statusClass; ?>">
                                    <?= htmlspecialchars($booking['status']); ?>
                                  </div>
                                </td>
                                <td>
                                  <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle btn-sm" type="button" 
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Actions
                                    </button>
                                    <div class="dropdown-menu">
                                      <!-- <a class="dropdown-item" href="booking_details.php?id=<?= $booking['id']; ?>">
                                        <i class="fas fa-eye"></i> View Details
                                      </a> -->
                                      <a class="dropdown-item" href="payments.php?booking_id=<?= $booking['id']; ?>">
                                        <i class="fas fa-credit-card"></i> View Payment
                                      </a>
                                      <?php if (strtolower($booking['status']) === 'pending'): ?>
                                        <a class="dropdown-item text-success" href="confirm_booking.php?id=<?= $booking['id']; ?>" 
                                          onclick="return confirm('Are you sure you want to confirm this booking?');">
                                          <i class="fas fa-check"></i> Confirm Booking
                                        </a>
                                        <a class="dropdown-item text-danger" href="reject_booking.php?id=<?= $booking['id']; ?>" 
                                          onclick="return confirm('Are you sure you want to reject this booking?');">
                                          <i class="fas fa-times"></i> Reject Booking
                                        </a>
                                      <?php endif; ?>
                                      <div class="dropdown-divider"></div>
                                      <!-- <a class="dropdown-item" href="update_status.php?id=<?= $booking['id']; ?>">
                                        <i class="fas fa-edit"></i> Update Status
                                      </a> -->
                                    </div>
                                  </div>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                          <?php else: ?>
                            <tr>
                              <td colspan="9" class="text-center">
                                <div class="empty-state">
                                  <div class="empty-state-icon">
                                    <i class="fas fa-calendar-times"></i>
                                  </div>
                                  <h2>No bookings found</h2>
                                  <p class="lead">
                                    <?php if ($statusFilter || $dateFrom || $dateTo || $searchTerm): ?>
                                      Try adjusting your filters to see more results.
                                    <?php else: ?>
                                      No bookings have been made yet.
                                    <?php endif; ?>
                                  </p>
                                  <?php if ($statusFilter || $dateFrom || $dateTo || $searchTerm): ?>
                                    <a href="?" class="btn btn-primary mt-4">
                                      <i class="fas fa-times"></i> Clear Filters
                                    </a>
                                  <?php endif; ?>
                                </div>
                              </td>
                            </tr>
                          <?php endif; ?>
                        </tbody>
                      </table>
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

  <script src="../../assets/admin/cater-admin/assets/modules/jquery.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/popper.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/tooltip.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/moment.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/stisla.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/datatables/datatables.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/jquery-ui/jquery-ui.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/page/modules-datatables.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/scripts.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/custom.js"></script>

  <script>
    $(document).ready(function() {
      // Initialize DataTable with additional features
      $('#bookingsTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": false, // Disable default search since we have custom filters
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "pageLength": 25,
        "order": [[ 2, "desc" ]], // Sort by booking date descending
        "columnDefs": [
          { "orderable": false, "targets": 8 } // Disable sorting on Actions column
        ]
      });

      // Auto-submit form when status changes
      $('#status').change(function() {
        $(this).closest('form').submit();
      });

      // Clear individual filters
      $('.btn-clear-filter').click(function(e) {
        e.preventDefault();
        const input = $(this).siblings('input, select');
        input.val('');
        $(this).closest('form').submit();
      });
    });
  </script>

  <style>
    .empty-state {
      padding: 40px 20px;
    }
    .empty-state-icon {
      font-size: 64px;
      color: #6c757d;
      margin-bottom: 20px;
    }
    .filter-section {
      background-color: #f8f9fa;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 20px;
    }
  </style>
</body>
</html>