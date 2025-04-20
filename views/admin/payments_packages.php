<?php
require_once __DIR__ . '/../../config/database.php';
try {
    $pdo = Database::getConnection();
    $booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;
    if ($booking_id <= 0) throw new Exception("Invalid booking ID.");

    $sql = "SELECT 
                pb.id AS booking_id, u.name AS customer_name, 
                u.address, u.email, pb.booking_date, pb.status,
                p.amount, p.created_at AS payment_date,
                pm.method AS payment_method, p.gcash_receipt,
                pk.name AS package_name, pk.description, pk.price AS package_price
            FROM bookings_packages pb
            JOIN users u ON pb.customer_id = u.id
            JOIN packages pk ON pb.package_id = pk.id
            LEFT JOIN payments p ON p.booking_id = pb.id AND p.type = 'package'
            LEFT JOIN payment_methods pm ON p.payment_method_id = pm.id
            WHERE pb.id = :booking_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['booking_id' => $booking_id]);
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$payment) throw new Exception("No payment found for this package booking.");
} catch (Exception $e) {
    die("<p style='color:red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>");
}
?>
<div id="app">
  <div class="main-wrapper main-wrapper-1">
    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
      <section class="section">
        <div class="section-header">
          <h1>Package Invoice</h1>
          <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item">Package Payments</div>
          </div>
        </div>
        <div class="section-body">
          <div class="invoice" id="invoice">
            <div class="invoice-print">
              <div class="row">
                <div class="col-lg-12">
                  <div class="invoice-title">
                    <h2>Invoice</h2>
                    <div class="invoice-number">Booking #<?= htmlspecialchars($payment['booking_id']); ?></div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-md-6">
                      <strong>Billed To:</strong><br>
                      <?= htmlspecialchars($payment['customer_name']); ?><br>
                      <?= htmlspecialchars($payment['address']); ?><br>
                      <?= htmlspecialchars($payment['email']); ?>
                    </div>
                    <div class="col-md-6 text-md-right">
                      <strong>Payment Method:</strong><br>
                      <?= htmlspecialchars($payment['payment_method'] ?? 'Unknown'); ?><br>
                      <?php if (!empty($payment['gcash_receipt'])): ?>
                        <strong>GCash Receipt:</strong><br>
                        <a href="<?= '../../uploads/receipts/' . htmlspecialchars(basename($payment['gcash_receipt'])); ?>" target="_blank">
                          <img src="<?= '../../uploads/receipts/' . htmlspecialchars(basename($payment['gcash_receipt'])); ?>" 
                               style="max-width: 250px; border: 1px solid #ccc;">
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="row mt-4">
                    <div class="col-md-12">
                      <h5 class="section-title">Package Summary</h5>
                      <table class="table">
                        <tr>
                          <th>Package Name</th>
                          <th>Description</th>
                          <th class="text-right">Price</th>
                        </tr>
                        <tr>
                          <td><?= htmlspecialchars($payment['package_name']); ?></td>
                          <td><?= htmlspecialchars($payment['description']); ?></td>
                          <td class="text-right">₱<?= number_format($payment['package_price'], 2); ?></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <h5>Booking Info</h5>
                      <p><strong>Date:</strong> <?= htmlspecialchars($payment['booking_date']); ?></p>
                      <p><strong>Status:</strong> <?= htmlspecialchars($payment['status']); ?></p>
                    </div>
                    <div class="col-md-6 text-md-right">
                      <h5>Payment Info</h5>
                      <p><strong>Paid:</strong> ₱<?= number_format($payment['amount'], 2); ?></p>
                      <p><strong>Date:</strong> <?= htmlspecialchars($payment['payment_date']); ?></p>
                    </div>
                  </div>
                  <hr>
                  <div class="text-md-right">
                    <button class="btn btn-success" onclick="updatePaymentStatus(<?= $payment['booking_id']; ?>, 'confirmed')">Confirm</button>
                    <button class="btn btn-danger" onclick="updatePaymentStatus(<?= $payment['booking_id']; ?>, 'rejected')">Reject</button>
                    <button class="btn btn-warning btn-icon icon-left" onclick="printInvoice()">
                      <i class="fas fa-print"></i> Print
                    </button>
                    <button class="btn btn-secondary btn-icon icon-left" onclick="goBack()">
                      <i class="fas fa-arrow-left"></i> Back
                    </button>
                  </div>
                </div>
              </div>
            </div>
        </div>
      </section>
    </div>
  </div>
</div>
<script>
function goBack() {
  window.history.back();
}
function printInvoice() {
  let invoiceContent = document.getElementById('invoice').innerHTML;
  let originalContent = document.body.innerHTML;
  document.body.innerHTML = invoiceContent;
  window.print();
  document.body.innerHTML = originalContent;
  location.reload();
}
function updatePaymentStatus(bookingId, status) {
  if (!confirm(`Are you sure you want to ${status} this payment?`)) return;
  fetch('update_payment_status_package.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ booking_id: bookingId, status: status })
  }).then(res => res.json())
    .then(data => alert(data.message))
    .catch(err => alert('Error: ' + err));
}
</script>
