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
    // public function fetchCartItems($booking_id) {
    //     return $this->cartModel->getItems($booking_id);
    // }
    public function fetchCartItems($booking_id) {
    $stmt = $this->pdo->prepare("
        SELECT 
            bi.id,
            bi.menu_item_id,
            bi.quantity,
            bi.subtotal,
            mi.name,
            mi.price,
            mi.image,
            mi.availability
        FROM booking_items bi
        JOIN menu_items mi ON bi.menu_item_id = mi.id
        WHERE bi.booking_id = ?
    ");
    $stmt->execute([$booking_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
   public function updateCartQuantity($item_id, $booking_id, $quantity) {
    try {
        // Get the menu_item_id and price from menu_items table
        $stmt = $this->pdo->prepare("
            SELECT mi.price 
            FROM booking_items bi
            JOIN menu_items mi ON bi.menu_item_id = mi.id
            WHERE bi.id = ? AND bi.booking_id = ?
        ");
        $stmt->execute([$item_id, $booking_id]);
        $item = $stmt->fetch();
        
        if ($item) {
            $price = $item['price'];
            $subtotal = $price * $quantity;
            
            // Update quantity and subtotal
            $stmt = $this->pdo->prepare("UPDATE booking_items SET quantity = ?, subtotal = ? WHERE id = ? AND booking_id = ?");
            $stmt->execute([$quantity, $subtotal, $item_id, $booking_id]);
            return ["status" => "success", "message" => "Cart updated successfully."];
        } else {
            return ["status" => "error", "message" => "Item not found."];
        }
    } catch (PDOException $e) {
        return ["status" => "error", "message" => "Failed to update cart: " . $e->getMessage()];
    }
}
}
?>