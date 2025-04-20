<?php
require_once __DIR__ . '/../config/database.php';
class ManageBooking {
    private $db;
    public function __construct() {
        $this->db = Database::getConnection();
    }
    public function getAllBookings() {
        $sql = "SELECT 
                    b.id, u.name AS customer_name, b.event_date, bs.status, 
                    b.guests, et.name AS event_type 
                FROM bookings b
                JOIN users u ON b.customer_id = u.id
                JOIN booking_statuses bs ON b.status_id = bs.id
                JOIN service_types et ON b.package_id = et.id
                ORDER BY b.event_date DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    public function getAllPackageBookings()
{
    $sql = "SELECT 
                po.id, 
                u.name AS customer_name, 
                p.name AS package_name, 
                po.ordered_at AS booking_date, 
                po.status 
            FROM package_orders po
            JOIN users u ON po.user_id = u.id
            JOIN packages p ON po.package_id = p.id
            ORDER BY po.ordered_at DESC";
    $stmt = $this->db->query($sql);
    return $stmt->fetchAll();
}
public function rejectBooking($bookingId) {
    $stmt = $this->db->prepare("UPDATE package_orders SET status = 'cancelled' WHERE id = :id");
    $stmt->bindValue(':id', $bookingId, PDO::PARAM_INT);
    return $stmt->execute();
}
}
?>
