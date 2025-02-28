<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/CartController.php';
require_once __DIR__ . '/controllers/AuthController.php';

$pdo = Database::getConnection();
$cartController = new CartController($pdo);
$authController = new AuthController();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_GET['route']) && $_GET['route'] === "login") {
    $authController->login();
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove_from_cart'])) {
        $cartController->removeFromCart($_POST['item_id']);
        header("Location: http://localhost/cater-craft/public/add_to_cart.php");
        exit();
    }
}

if (!isset($_SESSION['booking_id']) || !is_numeric($_SESSION['booking_id'])) {
    $stmt = $pdo->prepare("SELECT id FROM bookings WHERE customer_id = 1 ORDER BY created_at DESC LIMIT 1");
    $stmt->execute();
    $existingBooking = $stmt->fetch();

    if ($existingBooking) {
        $_SESSION['booking_id'] = $existingBooking['id'];
    } else {
        $stmt = $pdo->prepare("INSERT INTO bookings (customer_id, event_date, event_time, guests, total_amount) VALUES (1, CURDATE(), CURTIME(), 1, 0)");
        $stmt->execute();
        $_SESSION['booking_id'] = $pdo->lastInsertId();
    }
}

$booking_id = $_SESSION['booking_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_GET['route']) && $_GET['route'] === "add_to_cart") {
    $menu_item_id = $_POST['menu_item_id'] ?? null;
    $price = $_POST['price'] ?? null;
    $quantity = $_POST['quantity'] ?? 1;

    if ($menu_item_id && $price && $quantity > 0) {
        $response = $cartController->addToCart($booking_id, $menu_item_id, $price, $quantity);
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    } else {
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "message" => "Invalid input."]);
        exit();
    }
}
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['route']) && $_GET['route'] === "cart_count") {
    $cartCount = isset($_SESSION['booking_id']) ? $cartController->getCartCount($_SESSION['booking_id']) : 0;

    header('Content-Type: application/json');
    echo json_encode(['cartCount' => $cartCount]);
    exit();
}
?>