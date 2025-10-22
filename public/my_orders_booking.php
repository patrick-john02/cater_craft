<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/database.php';
$pdo = Database::getConnection();

// Fetch all bookings for this user
$stmt = $pdo->prepare("
    SELECT 
        b.id,
        b.event_date,
        b.event_time,
        b.venue,
        b.guests,
        b.total_amount,
        bs.status AS booking_status,
        p.status AS payment_status,
        pm.method AS payment_method,
        b.created_at
    FROM bookings b
    LEFT JOIN booking_statuses bs ON b.status_id = bs.id
    LEFT JOIN payments p ON b.id = p.booking_id
    LEFT JOIN payment_methods pm ON p.payment_method_id = pm.id
    WHERE b.customer_id = ?
    ORDER BY b.created_at DESC
");
$stmt->execute([$_SESSION['user']['id']]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Cater</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/organi/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/style.css" type="text/css">
    <style>
        .booking-card {
            background: #fff;
            border-radius: 5px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .booking-header h5 {
            margin: 0;
            color: #252525;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-confirmed { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .status-paid { background: #c3e6cb; color: #155724; }
        .status-unpaid { background: #f5c6cb; color: #721c24; }
        .empty-message {
            text-align: center;
            padding: 60px 0;
            color: #777;
        }
        .empty-message i {
            font-size: 60px;
            color: #ccc;
        }
        .booking-actions a {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <?php include('includes/navbar.php'); ?>

    <section class="order-success-section">
        <div class="container">
            <div class="row justify-content-center mt-5 mb-4">
                <div class="col-lg-10">
                    <h2 class="text-center mb-5">My Bookings</h2>

                    <?php if (empty($bookings)): ?>
                        <div class="empty-message">
                            <i class="fa fa-calendar-times-o"></i>
                            <h4 class="mt-3">You have no bookings yet.</h4>
                            <a href="landing_page.php" class="btn btn-primary mt-3">
                                <i class="fa fa-home"></i> Go to Home
                            </a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($bookings as $booking): ?>
                            <div class="booking-card">
                                <div class="booking-header">
                                    <h5>Booking #<?= htmlspecialchars($booking['id']) ?></h5>
                                    <span class="status-badge status-<?= strtolower($booking['booking_status']) ?>">
                                        <?= ucfirst($booking['booking_status']) ?>
                                    </span>
                                </div>

                                <div class="mt-3">
                                    <p><strong>Event Date:</strong> <?= date('F d, Y', strtotime($booking['event_date'])) ?></p>
                                    <p><strong>Event Time:</strong> <?= date('h:i A', strtotime($booking['event_time'])) ?></p>
                                    <p><strong>Venue:</strong> <?= htmlspecialchars($booking['venue']) ?></p>
                                    <p><strong>Guests:</strong> <?= (int)$booking['guests'] ?></p>
                                    <p><strong>Total Amount:</strong> ₱<?= number_format($booking['total_amount'], 2) ?></p>

                                    <p>
                                        <strong>Payment:</strong> 
                                        <span class="status-badge status-<?= strtolower($booking['payment_status'] ?? 'unpaid') ?>">
                                            <?= ucfirst($booking['payment_status'] ?? 'Unpaid') ?>
                                        </span>
                                        <?= htmlspecialchars($booking['payment_method'] ?? 'N/A') ?>
                                    </p>

                                    <div class="booking-actions mt-3">
                                        <!-- <a href="view_booking.php?id=<?= urlencode($booking['id']) ?>" class="btn btn-outline-success btn-sm">
                                            <i class="fa fa-eye"></i> View Details
                                        </a> -->
                                        <!-- <?php if (strtolower($booking['booking_status']) === 'pending'): ?>
                                            <a href="cancel_booking.php?id=<?= urlencode($booking['id']) ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Cancel this booking?');">
                                                <i class="fa fa-times"></i> Cancel
                                            </a>
                                        <?php endif; ?> -->
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php include('includes/footer.php'); ?>

    <script src="../assets/organi/js/jquery-3.3.1.min.js"></script>
    <script src="../assets/organi/js/bootstrap.min.js"></script>
    <script src="../assets/organi/js/main.js"></script>
</body>
</html>
