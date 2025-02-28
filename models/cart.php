<?php
class Cart {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addItem($booking_id, $menu_item_id, $price, $quantity) {
        $subtotal = $price * $quantity;

        $stmt = $this->pdo->prepare("SELECT id FROM bookings WHERE id = ?");
        $stmt->execute([$booking_id]);
        $bookingExists = $stmt->fetch();

        if (!$bookingExists) {
            throw new Exception("Error: Booking ID does not exist.");
        }

        $stmt = $this->pdo->prepare("SELECT id, quantity FROM booking_items WHERE booking_id = ? AND menu_item_id = ?");
        $stmt->execute([$booking_id, $menu_item_id]);
        $existingItem = $stmt->fetch();

        if ($existingItem) {
            $newQuantity = $existingItem['quantity'] + $quantity;
            $newSubtotal = $newQuantity * $price;
            $stmt = $this->pdo->prepare("UPDATE booking_items SET quantity = ?, subtotal = ? WHERE id = ?");
            $stmt->execute([$newQuantity, $newSubtotal, $existingItem['id']]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO booking_items (booking_id, menu_item_id, quantity, subtotal) VALUES (?, ?, ?, ?)");
            $stmt->execute([$booking_id, $menu_item_id, $quantity, $subtotal]);
        }
    }

    public function getItems($booking_id) {
        $stmt = $this->pdo->prepare("
            SELECT bi.id, mi.name, mi.price, bi.quantity, bi.subtotal, mi.image 
            FROM booking_items bi
            JOIN menu_items mi ON bi.menu_item_id = mi.id
            WHERE bi.booking_id = ?
        ");
        $stmt->execute([$booking_id]);
        return $stmt->fetchAll();
    }

    public function getTotal($booking_id) {
        $stmt = $this->pdo->prepare("SELECT SUM(subtotal) AS total FROM booking_items WHERE booking_id = ?");
        $stmt->execute([$booking_id]);
        return $stmt->fetchColumn() ?? 0;
    }
    public function removeItem($item_id) {
        $stmt = $this->pdo->prepare("DELETE FROM booking_items WHERE id = ?");
        $stmt->execute([$item_id]);
    }
    
}
?>