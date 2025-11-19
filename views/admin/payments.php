<?php
require_once __DIR__ . '/../../config/database.php';
try {
    $pdo = Database::getConnection();
    $booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;
    if ($booking_id <= 0) {
        throw new Exception("Invalid booking ID.");
    }
    $sql = "SELECT 
    p.id AS payment_id, b.id AS booking_id, b.status_id, u.name AS customer_name, 
    u.address, u.email, b.event_date, b.event_time, b.guests, 
    b.venue, b.special_requests, p.amount, p.created_at AS payment_date, 
    pm.method AS payment_method, p.gcash_receipt, b.total_amount

FROM payments p
JOIN bookings b ON p.booking_id = b.id
JOIN users u ON b.customer_id = u.id
LEFT JOIN payment_methods pm ON p.payment_method_id = pm.id
WHERE p.booking_id = :booking_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['booking_id' => $booking_id]);
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$payment) {
        throw new Exception("No payment found for this booking.");
    }
    $items_sql = "SELECT mi.name AS item_name, bi.quantity, mi.price, (bi.quantity * mi.price) AS subtotal
                  FROM booking_items bi
                  JOIN menu_items mi ON bi.menu_item_id = mi.id
                  WHERE bi.booking_id = :booking_id";
    $items_stmt = $pdo->prepare($items_sql);
    $items_stmt->execute(['booking_id' => $booking_id]);
    $items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Cater Payments</title>
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/css/style.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/css/components.css">
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-94034622-3');
  </script>
  <style>
    @media print {
      .no-print {
        display: none !important;
      }
      /* Hide navbar and sidebar */
      .navbar,
      .main-sidebar,
      .sidebar-wrapper,
      aside {
        display: none !important;
      }
      body {
        margin: 0;
        padding: 20px;
      }
      .main-wrapper, .main-content {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
      }
      .main-wrapper-1 {
        padding-left: 0 !important;
      }
      .invoice {
        box-shadow: none !important;
        border: none !important;
      }
      .section {
        padding: 0 !important;
      }
    }
  </style>
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?php include 'includes/navbar.php'; ?>
            <?php include 'includes/sidebar.php'; ?>

            <div class="main-content">
                <section class="section">
                    <div class="section-header no-print">
                        <h1>Invoice</h1>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                            <div class="breadcrumb-item">Payments</div>
                        </div>
                    </div>
                     <div class="section-body">
                        <div class="invoice" id="invoice">
                            <div class="invoice-print">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="invoice-title">
                                            <h2>Invoice</h2>
                                            <div class="invoice-number">Order #<?= htmlspecialchars($payment['booking_id']); ?></div>
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
                                                    <a href="<?= htmlspecialchars('../../uploads/receipts/' . basename($payment['gcash_receipt'])); ?>" target="_blank">
                                                        <img src="<?= htmlspecialchars('../../uploads/receipts/' . basename($payment['gcash_receipt'])); ?>" 
                                                             alt="GCash Receipt" 
                                                             style="max-width: 300px; border: 1px solid #ccc;">
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <h5 class="section-title">Order Summary</h5>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-md">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Item</th>
                                                    <th class="text-center">Price</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-right">Subtotal</th>
                                                </tr>
                                                <?php
                                                $count = 1;
                                                $total = 0;
                                                foreach ($items as $item) {
                                                    $total += $item['subtotal'];
                                                ?>
                                                    <tr>
                                                        <td><?= $count++; ?></td>
                                                        <td><?= htmlspecialchars($item['item_name']); ?></td>
                                                        <td class="text-center">₱<?= number_format($item['price'], 2); ?></td>
                                                        <td class="text-center"><?= $item['quantity']; ?></td>
                                                        <td class="text-right">₱<?= number_format($item['subtotal'], 2); ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5><strong>Booking Information</strong></h5>
                                        <p><strong>Booking ID:</strong> <?= htmlspecialchars($payment['booking_id']); ?></p>
                                        <p><strong>Event Date:</strong> <?= htmlspecialchars($payment['event_date']); ?></p>
                                        <p><strong>Number of Guests:</strong> <?= htmlspecialchars($payment['guests']); ?></p>
                                        <p><strong>Venue:</strong> <?= htmlspecialchars($payment['venue'] ?? 'Not specified'); ?></p>
                                    </div>
                                    <div class="col-md-6 text-md-right">
                                        <h5><strong>Payment Information</strong></h5>
                                        <p><strong>Amount Paid:</strong> ₱<?= number_format($payment['amount'], 2); ?></p>
                                        <p><strong>Payment Date:</strong> <?= htmlspecialchars($payment['payment_date']); ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="text-md-right no-print">
                                    <?php
                                    // Get current booking status
                                    $isPending = ($payment['status_id'] == 1); // 1=Pending
                                    $isConfirmed = ($payment['status_id'] == 2); // 2=Confirmed
                                    $isCancelled = ($payment['status_id'] == 3); // 3=Cancelled
                                    $isFinalized = ($isConfirmed || $isCancelled);
                                    ?>
                                    
                                    <?php if ($isPending): ?>
                                        <button class="btn btn-success" onclick="updatePaymentStatus(<?= $payment['payment_id']; ?>, 'confirmed')">
                                            <i class="fas fa-check"></i> Confirm Payment
                                        </button>
                                        <button class="btn btn-danger" onclick="updatePaymentStatus(<?= $payment['payment_id']; ?>, 'rejected')">
                                            <i class="fas fa-times"></i> Reject Payment
                                        </button>
                                    <?php else: ?>
                                        <div class="alert <?= $isConfirmed ? 'alert-success' : 'alert-danger' ?> d-inline-block mb-3">
                                            <i class="fas fa-<?= $isConfirmed ? 'check-circle' : 'times-circle' ?>"></i> 
                                            Payment has been <?= $isConfirmed ? 'confirmed' : 'cancelled' ?>. No further actions available.
                                        </div>
                                    <?php endif; ?>
                                    
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
                </section>
            </div>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }

        function printInvoice() {
            window.print();
        }

        function updatePaymentStatus(paymentId, status) {
            if (!confirm(`Are you sure you want to ${status} this payment?`)) {
                return;
            }
            fetch('update_payment_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    payment_id: paymentId,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Payment status updated successfully!');
                    location.reload();
                } else {
                    alert('Error updating payment status: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating payment status.');
            });
        }
    </script>

    <script src="../../assets/admin/cater-admin/assets/modules/jquery.min.js"></script>
    <script src="../../assets/admin/cater-admin/assets/modules/popper.js"></script>
    <script src="../../assets/admin/cater-admin/assets/modules/tooltip.js"></script>
    <script src="../../assets/admin/cater-admin/assets/modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../assets/admin/cater-admin/assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
    <script src="../../assets/admin/cater-admin/assets/modules/moment.min.js"></script>
    <script src="../../assets/admin/cater-admin/assets/js/stisla.js"></script>
    <script src="../../assets/admin/cater-admin/assets/js/scripts.js"></script>
    <script src="../../assets/admin/cater-admin/assets/js/custom.js"></script>
</body>
</html>