<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Get booking ID from session (set in CheckoutController)
$bookingId = isset($_SESSION['last_booking_id']) ? (int)$_SESSION['last_booking_id'] : 0;

if ($bookingId <= 0) {
    $_SESSION['error'] = "Invalid booking ID.";
    header("Location: landing_page.php");
    exit;
}

require_once '../config/database.php';
$pdo = Database::getConnection();

// Fetch booking details
$stmt = $pdo->prepare("
    SELECT 
        b.*,
        bs.status as booking_status,
        pm.method as payment_method,
        p.amount as payment_amount,
        p.status as payment_status,
        u.name as customer_name,
        u.email as customer_email
    FROM bookings b
    LEFT JOIN booking_statuses bs ON b.status_id = bs.id
    LEFT JOIN payments p ON b.id = p.booking_id
    LEFT JOIN payment_methods pm ON p.payment_method_id = pm.id
    LEFT JOIN users u ON b.customer_id = u.id
    WHERE b.id = ? AND b.customer_id = ?
");
$stmt->execute([$bookingId, $_SESSION['user']['id']]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    $_SESSION['error'] = "Booking not found.";
    header("Location: landing_page.php");
    exit;
}

// Fetch booking items
$stmt = $pdo->prepare("
    SELECT 
        bi.*,
        mi.name as item_name,
        mi.description as item_description
    FROM booking_items bi
    JOIN menu_items mi ON bi.menu_item_id = mi.id
    WHERE bi.booking_id = ?
");
$stmt->execute([$bookingId]);
$bookingItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Clear the session variable
unset($_SESSION['last_booking_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - Cater</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/organi/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/style.css" type="text/css">
    <style>
        .success-icon {
            font-size: 80px;
            color: #7fad39;
            margin-bottom: 20px;
        }
        .order-success-section {
            padding: 80px 0;
        }
        .order-details-box {
            background: #f5f5f5;
            padding: 30px;
            border-radius: 5px;
            margin-top: 30px;
        }
        .order-summary {
            background: white;
            padding: 25px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #252525;
        }
        .item-list {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        .item-list li {
            padding: 10px;
            background: #f9f9f9;
            margin-bottom: 10px;
            border-radius: 3px;
            display: flex;
            justify-content: space-between;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <?php include('includes/navbar.php'); ?>
    
    <section class="order-success-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="success-icon">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <h2>Order Successfully Placed!</h2>
                    <p class="lead">Thank you for your booking. Your order has been received and is being processed.</p>
                    <p><strong>Booking ID:</strong> #<?= htmlspecialchars($bookingId) ?></p>
                    
                    <div class="order-details-box mt-4">
                        <div class="order-summary">
                            <h4 class="mb-4">Booking Details</h4>
                            
                            <div class="info-row">
                                <span class="info-label">Customer Name:</span>
                                <span><?= htmlspecialchars($booking['customer_name']) ?></span>
                            </div>
                            
                            <div class="info-row">
                                <span class="info-label">Email:</span>
                                <span><?= htmlspecialchars($booking['customer_email']) ?></span>
                            </div>
                            
                            <div class="info-row">
                                <span class="info-label">Event Date:</span>
                                <span><?= date('F d, Y', strtotime($booking['event_date'])) ?></span>
                            </div>
                            
                            <div class="info-row">
                                <span class="info-label">Event Time:</span>
                                <span><?= date('h:i A', strtotime($booking['event_time'])) ?></span>
                            </div>
                            
                            <div class="info-row">
                                <span class="info-label">Venue:</span>
                                <span><?= htmlspecialchars($booking['venue']) ?></span>
                            </div>
                            
                            <div class="info-row">
                                <span class="info-label">Number of Guests:</span>
                                <span><?= (int)$booking['guests'] ?></span>
                            </div>
                            
                            <?php if (!empty($booking['special_requests'])): ?>
                            <div class="info-row">
                                <span class="info-label">Special Requests:</span>
                                <span><?= htmlspecialchars($booking['special_requests']) ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="info-row">
                                <span class="info-label">Payment Method:</span>
                                <span><?= ucfirst(htmlspecialchars($booking['payment_method'] ?? 'N/A')) ?></span>
                            </div>
                            
                            <div class="info-row">
                                <span class="info-label">Payment Status:</span>
                                <span class="status-badge status-<?= strtolower($booking['payment_status']) ?>">
                                    <?= ucfirst(htmlspecialchars($booking['payment_status'])) ?>
                                </span>
                            </div>
                            
                            <hr class="my-4">
                            
                            <h5 class="mb-3">Ordered Items</h5>
                            <ul class="item-list">
                                <?php foreach ($bookingItems as $item): ?>
                                <li>
                                    <span>
                                        <strong><?= htmlspecialchars($item['item_name']) ?></strong>
                                        <small class="d-block text-muted">Qty: <?= (int)$item['quantity'] ?></small>
                                    </span>
                                    <span class="text-right">
                                        <strong>₱<?= number_format($item['subtotal'], 2) ?></strong>
                                    </span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            
                            <div class="info-row mt-3" style="font-size: 18px; border-top: 2px solid #7fad39; padding-top: 15px;">
                                <span class="info-label">Total Amount:</span>
                                <span><strong>₱<?= number_format($booking['total_amount'], 2) ?></strong></span>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-4">
                            <i class="fa fa-info-circle"></i>
                            <strong>What's Next?</strong><br>
                            We will review your booking and contact you within 24 hours to confirm the details. 
                            <?php if ($booking['payment_method'] === 'gcash'): ?>
                            We will verify your GCash payment receipt shortly.
                            <?php endif; ?>
                        </div>
                        
                        <div class="mt-4">
                            <a href="landing_page.php" class="btn btn-primary mr-2">
                                <i class="fa fa-home"></i> Back to Home
                            </a>
                            <a href="my_orders_booking.php" class="btn btn-outline-primary">
                                <i class="fa fa-list"></i> View My Bookings
                            </a>
                            <a href="download_receipt.php?booking_id=<?= $bookingId ?>" class="btn btn-success" target="_blank">
                                <i class="fa fa-download"></i> Download Receipt
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php include('includes/footer.php'); ?>
    
    <script src="../assets/organi/js/jquery-3.3.1.min.js"></script>
    <script src="../assets/organi/js/bootstrap.min.js"></script>
    <script src="../assets/organi/js/jquery.nice-select.min.js"></script>
    <script src="../assets/organi/js/jquery-ui.min.js"></script>
    <script src="../assets/organi/js/jquery.slicknav.js"></script>
    <script src="../assets/organi/js/mixitup.min.js"></script>
    <script src="../assets/organi/js/owl.carousel.min.js"></script>
    <script src="../assets/organi/js/main.js"></script>
</body>
</html>