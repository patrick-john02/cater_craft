<?php
require_once __DIR__ . '/../config/database.php';

class Booking {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAllBookings() {
        $sql = "SELECT b.*, u.name AS customer_name, bs.status AS booking_status 
                FROM bookings b
                JOIN users u ON b.customer_id = u.id
                JOIN booking_statuses bs ON b.status_id = bs.id
                ORDER BY b.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
