<?php
require_once __DIR__ . '/../config/Database.php';

class CheckoutModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function saveCheckout($data) {
        if (empty($data['booking_id'])) {
            throw new Exception("Booking ID is missing.");
        }
    
        if (empty($data['user_id'])) {
            throw new Exception("User ID is required.");
        }
    
        $sql = "INSERT INTO payments 
        (booking_id, user_id, amount, payment_method_id, gcash_reference, gcash_receipt, created_at) 
        VALUES 
        (:booking_id, :user_id, :amount, :payment_method, :gcash_reference, :gcash_receipt, NOW())";

        $stmt = $this->conn->prepare($sql);
    
        $stmt->bindParam(':booking_id', $data['booking_id']);
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':amount', $data['total_amount']);
        $stmt->bindParam(':payment_method', $data['payment_method'], PDO::PARAM_INT);
        $stmt->bindParam(':gcash_reference', $data['gcash_reference']); // Add this
        $stmt->bindParam(':gcash_receipt', $data['gcash_receipt']);
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
}
?>
