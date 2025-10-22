<?php
require_once __DIR__ . '/../../config/database.php';
$pdo = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $booking_id = (int)$_POST['booking_id'];

    $stmt = $pdo->prepare("UPDATE package_orders SET status = 'processing' WHERE id = ?");
    $stmt->execute([$booking_id]);

    header("Location: booking_packages.php?msg=approved");
    exit;
}
header("Location: booking_packages.php?error=invalid");
exit;
?>
