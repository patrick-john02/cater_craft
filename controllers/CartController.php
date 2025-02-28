<?php
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../config/database.php';

class CartController {
    private $cartModel;
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
        $this->cartModel = new Cart($this->pdo);
    }
    public function fetchCartItems($booking_id) {
        return $this->cartModel->getItems($booking_id);
    }
    public function fetchCartTotal($booking_id) {
        return $this->cartModel->getTotal($booking_id);
    }
    public function addToCart($booking_id, $menu_item_id, $price, $quantity) {
        if (!$menu_item_id || !$price || !$quantity) {
            return ["status" => "error", "message" => "Invalid input."];
        }
    
        $stmt = $this->pdo->prepare("SELECT id FROM bookings WHERE id = ?");
        $stmt->execute([$booking_id]);
        $bookingExists = $stmt->fetch();
    
        if (!$bookingExists) {
            return ["status" => "error", "message" => "Invalid Booking ID."];
        }
    
        $this->cartModel->addItem($booking_id, $menu_item_id, $price, $quantity);
        return ["status" => "success", "message" => "Item added to cart!"];
    }
    public function showCart($booking_id) {
        $cartItems = $this->cartModel->getItems($booking_id);
        $totalAmount = $this->cartModel->getTotal($booking_id);
        require_once __DIR__ . '/../public/add_to_cart.php';
    }
    public function removeFromCart($item_id) {
        $this->cartModel->removeItem($item_id);
    }
    public function getCartCount($booking_id) {
        return count($this->cartModel->getItems($booking_id));
    }
}
?>