<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/AdminManageBooking.php';
$bookingModel = new ManageBooking();
$packageBookings = $bookingModel->getAllPackageBookings();
if (!is_array($packageBookings)) {
    $packageBookings = [];
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
            <h1>Package Booking Management</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="admin_dashboard.php">Dashboard</a></div>
              <div class="breadcrumb-item">Package Bookings</div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header"><h4>All Package Bookings</h4></div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Customer Name</th>
                            <th>Package Name</th>
                            <th>Booking Date</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($packageBookings as $index => $booking): ?>
                            <tr>
                              <td><?= $index + 1; ?></td>
                              <td><?= htmlspecialchars($booking['customer_name']); ?></td>
                              <td><?= htmlspecialchars($booking['package_name']); ?></td>
                              <td><?= htmlspecialchars($booking['booking_date']); ?></td>
                              <td><div class="badge badge-success"><?= htmlspecialchars($booking['status']); ?></div></td>
                              <td>
  <?php if (!empty($booking['proof_image'])): ?>
    <img src="../../uploads/gcash_proofs/<?= htmlspecialchars($booking['proof_image']) ?>" alt="GCash Proof" class="img-thumbnail" style="width: 70px; cursor: pointer;" data-toggle="modal" data-target="#proofModal<?= $booking['id'] ?>">
    
    <!-- Modal -->
    <div class="modal fade" id="proofModal<?= $booking['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="proofModalLabel<?= $booking['id'] ?>" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="proofModalLabel<?= $booking['id'] ?>">Payment Proof</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">
            <img src="../../uploads/gcash_proofs/<?= htmlspecialchars($booking['proof_image']) ?>" alt="Full GCash Proof" class="img-fluid">
          </div>
        </div>
      </div>
    </div>
  <?php else: ?>
    <span class="text-muted">No proof</span>
  <?php endif; ?>
    <!-- Reject Form -->
    <form action="reject_booking.php" method="POST" style="display: inline-block; margin-left: 10px;" onsubmit="return confirm('Are you sure you want to reject this booking?');">
    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
  </form>
</td>
                            </tr>
                          <?php endforeach; ?>
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
</body>
</html>