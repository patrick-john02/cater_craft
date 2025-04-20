<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include required files
require_once '../config/database.php';
require_once '../controllers/UserController.php';

// Redirect if user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Get the user ID from session
$pdo = Database::getConnection();
$userId = $_SESSION['user']['id'] ?? 0;

// Fetch bookings for the logged-in user
$stmt = $pdo->prepare("SELECT * FROM bookings WHERE customer_id = ? ORDER BY event_date DESC");
$stmt->execute([$userId]);
$bookings = $stmt->fetchAll();

// Helper function to get booking status
function getStatusText($statusId) {
    return match ($statusId) {
        1 => 'Pending',
        2 => 'Confirmed',
        3 => 'Cancelled',
        default => 'Unknown',
    };
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders - Cater | Craft</title>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cater | Craft</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="../assets/organi/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/style.css" type="text/css">
</head>
<body>

<?php include('includes/navbar.php'); ?>

<section class="spad">
    <div class="container">
        <h2 class="mb-4">My Orders</h2>

        <?php if (count($bookings) > 0): ?>
            <?php foreach ($bookings as $booking): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 hidden class="card-title">Booking ID: <?= htmlspecialchars($booking['id']) ?></h5>
                        <p><strong>Event Date:</strong> <?= date("F j, Y", strtotime($booking['event_date'])) ?>
                        | <strong>Time:</strong> <?= date("g:i A", strtotime($booking['event_time'])) ?></p>

                        <p><strong>Guests:</strong> <?= htmlspecialchars($booking['guests']) ?></p>
                        <p><strong>Venue:</strong> <?= htmlspecialchars($booking['venue']) ?></p>
                        <p><strong>Status:</strong> <?= getStatusText($booking['status_id']) ?></p>
                        <p><strong>Total Amount:</strong> ₱<?= number_format($booking['total_amount'], 2) ?></p>

                        <!-- Booking Items -->
                        <h6 class="mt-3">Menu Items:</h6>
                        <ul>
                            <?php
                            $itemStmt = $pdo->prepare("
                                SELECT bi.quantity, bi.subtotal, mi.name 
                                FROM booking_items bi 
                                JOIN menu_items mi ON mi.id = bi.menu_item_id 
                                WHERE bi.booking_id = ?
                            ");
                            $itemStmt->execute([$booking['id']]);
                            $items = $itemStmt->fetchAll();

                            foreach ($items as $item): ?>
                                <li><?= htmlspecialchars($item['name']) ?> - <?= $item['quantity'] ?> pcs - ₱<?= number_format($item['subtotal'], 2) ?></li>
                            <?php endforeach; ?>
                        </ul>

                        <?php if (!empty($booking['cancellation_reason'])): ?>
                            <div class="alert alert-danger mt-2">
                                <strong>Cancelled:</strong> <?= htmlspecialchars($booking['cancellation_reason']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You have no orders yet. <a href="index.php">Browse packages</a> to place an order.</p>
        <?php endif; ?>
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
