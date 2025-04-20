<?php
require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json');

try {
    // Decode JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate input
    if (!isset($data['payment_id']) || !isset($data['status'])) {
        throw new Exception("Invalid input data.");
    }

    $payment_id = intval($data['payment_id']);
    $status = $data['status'];

    // Validate status
    if (!in_array($status, ['confirmed', 'rejected'])) {
        throw new Exception("Invalid payment status.");
    }

    $pdo = Database::getConnection();

    // Update payment status in the database
    $stmt = $pdo->prepare("UPDATE payments SET status = :status WHERE id = :payment_id");
    $stmt->execute(['status' => $status, 'payment_id' => $payment_id]);

    // Also update the booking status if payment is confirmed
    if ($status === 'confirmed') {
        $stmt = $pdo->prepare("UPDATE bookings SET status_id = 2 WHERE id = (SELECT booking_id FROM payments WHERE id = :payment_id)");
        $stmt->execute(['payment_id' => $payment_id]);
    }

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
