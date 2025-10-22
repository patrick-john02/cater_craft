<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/database.php';
require_once '../vendor/autoload.php'; // Dompdf autoloader

use Dompdf\Dompdf;

$bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;

if ($bookingId <= 0) {
    die('Invalid booking ID');
}

$pdo = Database::getConnection();

// Fetch booking and payment info
$stmt = $pdo->prepare("
    SELECT 
        b.*, 
        u.name AS customer_name, 
        u.email AS customer_email,
        p.amount AS payment_amount,
        p.status AS payment_status,
        pm.method AS payment_method
    FROM bookings b
    LEFT JOIN users u ON b.customer_id = u.id
    LEFT JOIN payments p ON p.booking_id = b.id
    LEFT JOIN payment_methods pm ON p.payment_method_id = pm.id
    WHERE b.id = ? AND b.customer_id = ?
");
$stmt->execute([$bookingId, $_SESSION['user']['id']]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die('Booking not found');
}

// Fetch items
$stmt = $pdo->prepare("
    SELECT mi.name AS item_name, bi.quantity, bi.subtotal
    FROM booking_items bi
    JOIN menu_items mi ON mi.id = bi.menu_item_id
    WHERE bi.booking_id = ?
");
$stmt->execute([$bookingId]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Build the HTML for PDF
ob_start();
?>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2, h4 { color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .total { text-align: right; font-size: 14px; font-weight: bold; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Catering Booking Receipt</h2>
        <p>Booking ID: #<?= htmlspecialchars($booking['id']) ?></p>
        <hr>
    </div>

    <p><strong>Customer:</strong> <?= htmlspecialchars($booking['customer_name']) ?><br>
       <strong>Email:</strong> <?= htmlspecialchars($booking['customer_email']) ?><br>
       <strong>Event Date:</strong> <?= date('F d, Y', strtotime($booking['event_date'])) ?><br>
       <strong>Venue:</strong> <?= htmlspecialchars($booking['venue']) ?><br>
    </p>

    <h4>Ordered Items</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['item_name']) ?></td>
                <td><?= (int)$item['quantity'] ?></td>
                <td>₱<?= number_format($item['subtotal'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p class="total">Total Amount: ₱<?= number_format($booking['total_amount'], 2) ?></p>

    <h4>Payment Details</h4>
    <p>
        <strong>Payment Method:</strong> <?= htmlspecialchars($booking['payment_method'] ?? 'N/A') ?><br>
        <strong>Payment Status:</strong> <?= ucfirst(htmlspecialchars($booking['payment_status'] ?? 'Pending')) ?><br>
        <strong>Amount Paid:</strong> ₱<?= number_format($booking['payment_amount'] ?? 0, 2) ?><br>
    </p>

    <hr>
    <p style="text-align:center;">Thank you for choosing our catering service!</p>
</body>
</html>
<?php
$html = ob_get_clean();

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output to browser
$dompdf->stream("booking_receipt_{$bookingId}.pdf", ["Attachment" => true]);
exit;
