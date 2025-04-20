<?php
require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['payment_id'], $input['status'])) {
        throw new Exception('Missing payment ID or status.');
    }

    $paymentId = intval($input['payment_id']);
    $status = trim($input['status']);

    $allowedStatuses = ['confirmed', 'rejected'];
    if (!in_array($status, $allowedStatuses)) {
        throw new Exception('Invalid status value.');
    }

    $pdo = Database::getConnection();

    $pdo->beginTransaction();

    $updateSql = "UPDATE package_payments SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($updateSql);
    $stmt->execute([
        'status' => $status,
        'id' => $paymentId
    ]);

    if ($stmt->rowCount() === 0) {
        throw new Exception('Payment ID not found or already updated.');
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Payment status updated successfully.'
    ]);
} catch (Exception $e) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
