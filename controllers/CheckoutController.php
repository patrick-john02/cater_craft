<?php
require_once '../config/database.php';
require_once '../controllers/CartController.php';

class CheckoutController {
    private $pdo;
    private $cartController;

    public function __construct() {
        $this->pdo = Database::getConnection();
        $this->cartController = new CartController();
    }

    public function processCheckout() {
        session_start();

        // Validate user authentication
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
            $_SESSION['error'] = "You must be logged in to proceed.";
            header("Location: ../public/login.php");
            exit;
        }

        // Validate POST data exists
        if (empty($_POST)) {
            $_SESSION['error'] = "Invalid request. Please try again.";
            header("Location: ../public/checkout.php");
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        
        // Sanitize and validate input data
        $data = $this->validateAndSanitizeInput($_POST);
        
        if (!$data) {
            return; // Error already set in validation method
        }

        // Handle file upload for GCash receipt
        $gcashReceiptPath = $this->handleFileUpload();
        if ($gcashReceiptPath === false && $this->isGcashPayment($data['payment_method'])) {
            return; // Error already set in file upload method
        }
        
        $data['gcash_receipt'] = $gcashReceiptPath;

        // Validate cart items exist using booking_id
        if (!$this->validateCart($data['booking_id'])) {
            $_SESSION['error'] = "Your cart is empty or invalid.";
            header("Location: ../public/add_to_cart.php");
            exit;
        }

        // Process the order
        $this->createOrder($data);
    }

    private function validateAndSanitizeInput($postData) {
        // Required fields (removed 'guests')
        $required = ['booking_id', 'venue', 'event_date', 'event_time', 'total_amount', 'payment_method'];
        
        foreach ($required as $field) {
            if (empty($postData[$field])) {
                $_SESSION['error'] = "Please fill in all required fields. Missing: " . ucfirst(str_replace('_', ' ', $field));
                header("Location: ../public/checkout.php");
                exit;
                return false;
            }
        }

        // Validate and sanitize data (removed guests field)
        $data = [
            'booking_id' => trim($postData['booking_id']),
            'customer_id' => $_SESSION['user']['id'],
            'venue' => trim($postData['venue']),
            'event_date' => $postData['event_date'],
            'event_time' => $postData['event_time'],
            'special_requests' => trim($postData['special_requests'] ?? ''),
            'total_amount' => (float)$postData['total_amount'],
            'payment_method' => (int)$postData['payment_method'],
            'gcash_reference' => trim($postData['gcash_reference'] ?? ''),
        ];

        if ($data['total_amount'] <= 0) {
            $_SESSION['error'] = "Invalid total amount.";
            header("Location: ../public/checkout.php");
            exit;
            return false;
        }

        // Validate event date is in the future
        $eventDateTime = $data['event_date'] . ' ' . $data['event_time'];
        if (strtotime($eventDateTime) <= time()) {
            $_SESSION['error'] = "Event date and time must be in the future.";
            header("Location: ../public/checkout.php");
            exit;
            return false;
        }

        // Validate payment method exists
        if (!$this->validatePaymentMethod($data['payment_method'])) {
            $_SESSION['error'] = "Invalid payment method selected.";
            header("Location: ../public/checkout.php");
            exit;
            return false;
        }

        // Validate GCash fields if GCash is selected
        if ($this->isGcashPayment($data['payment_method']) && empty($data['gcash_reference'])) {
            $_SESSION['error'] = "GCash reference number is required for GCash payments.";
            header("Location: ../public/checkout.php");
            exit;
            return false;
        }

        return $data;
    }

    private function handleFileUpload() {
        if (!isset($_FILES['gcash_receipt']) || $_FILES['gcash_receipt']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($_FILES['gcash_receipt']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = "File upload failed. Please try again.";
            header("Location: ../public/checkout.php");
            exit;
            return false;
        }

        $file = $_FILES['gcash_receipt'];
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            $_SESSION['error'] = "Invalid file type. Please upload JPG, PNG, GIF, or PDF files only.";
            header("Location: ../public/checkout.php");
            exit;
            return false;
        }

        // Validate file size (5MB max)
        if ($file['size'] > 5 * 1024 * 1024) {
            $_SESSION['error'] = "File size too large. Maximum 5MB allowed.";
            header("Location: ../public/checkout.php");
            exit;
            return false;
        }

        // Create upload directory if it doesn't exist
        $uploadDir = '../uploads/receipts/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                $_SESSION['error'] = "Failed to create upload directory.";
                header("Location: ../public/checkout.php");
                exit;
                return false;
            }
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('receipt_', true) . '.' . $extension;
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            $_SESSION['error'] = "Failed to upload receipt. Please try again.";
            header("Location: ../public/checkout.php");
            exit;
            return false;
        }

        return 'uploads/receipts/' . $fileName;
    }

    private function validatePaymentMethod($paymentMethodId) {
        $stmt = $this->pdo->prepare("SELECT id FROM payment_methods WHERE id = ?");
        $stmt->execute([$paymentMethodId]);
        return $stmt->fetch() !== false;
    }

    private function isGcashPayment($paymentMethodId) {
        $stmt = $this->pdo->prepare("SELECT method FROM payment_methods WHERE id = ?");
        $stmt->execute([$paymentMethodId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && strtolower($result['method']) === 'gcash';
    }

    private function validateCart($booking_id) {
        // Check if cart has items using the CartController
        $cartItems = $this->cartController->fetchCartItems($booking_id);
        return !empty($cartItems);
    }

    private function createOrder($data) {
        try {
            $this->pdo->beginTransaction();

            // Get cart items before processing
            $cartItems = $this->cartController->fetchCartItems($data['booking_id']);
            
            if (empty($cartItems)) {
                throw new Exception("Cart is empty");
            }

            // Debug: Log cart items structure
            error_log("Cart Items: " . print_r($cartItems, true));

            // Insert booking (removed guests field)
            $stmt = $this->pdo->prepare("
                INSERT INTO bookings (
                    customer_id, event_date, event_time, venue, 
                    special_requests, total_amount, status_id, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, 1, NOW())
            ");
            
            $success = $stmt->execute([
                $data['customer_id'],
                $data['event_date'],
                $data['event_time'],
                $data['venue'],
                $data['special_requests'],
                $data['total_amount']
            ]);

            if (!$success) {
                throw new Exception("Failed to create booking: " . implode(", ", $stmt->errorInfo()));
            }

            $final_booking_id = $this->pdo->lastInsertId();

            if (!$final_booking_id) {
                throw new Exception("Failed to get booking ID");
            }

            // Insert booking items from cart
            $this->insertBookingItems($final_booking_id, $cartItems);

            // Insert payment record
            $stmt = $this->pdo->prepare("
                INSERT INTO payments (
                    booking_id, user_id, amount, payment_method_id, 
                    gcash_reference, gcash_receipt, payment_status_id, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, 1, 'pending', NOW())
            ");
            
            $success = $stmt->execute([
                $final_booking_id,
                $data['customer_id'],
                $data['total_amount'],
                $data['payment_method'],
                $data['gcash_reference'] ?: null,
                $data['gcash_receipt'] ?: null
            ]);

            if (!$success) {
                throw new Exception("Failed to create payment record: " . implode(", ", $stmt->errorInfo()));
            }

            // Clear cart from database using the original booking_id
            $this->clearCart($data['booking_id']);

            $this->pdo->commit();

            // Clear session booking_id to generate new one for next order
            unset($_SESSION['booking_id']);
            
            // Store booking ID for success page
            $_SESSION['last_booking_id'] = $final_booking_id;
            
            $_SESSION['success'] = "Your order has been successfully placed! Booking ID: " . $final_booking_id;
            header("Location: ../public/order_success_booking.php");
            exit;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            
            // Log the detailed error
            error_log("Checkout Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Show more specific error in development (remove in production)
            $_SESSION['error'] = "An error occurred while processing your order: " . $e->getMessage();
            header("Location: ../public/checkout.php");
            exit;
        }
    }

    private function insertBookingItems($booking_id, $cartItems) {
        if (empty($cartItems)) {
            throw new Exception("Cart is empty");
        }

        // Prepare statements
        $insertStmt = $this->pdo->prepare("
            INSERT INTO booking_items (booking_id, menu_item_id, quantity, subtotal)
            VALUES (?, ?, ?, ?)
        ");
        
        $validateStmt = $this->pdo->prepare("
            SELECT id, name, price FROM menu_items WHERE id = ? AND availability = 1
        ");

        foreach ($cartItems as $item) {
            // Debug: Log each item
            error_log("Processing cart item: " . print_r($item, true));

            // Flexible field name handling
            $menuItemId = $item['menu_item_id'] ?? $item['item_id'] ?? $item['id'] ?? null;
            $quantity = $item['quantity'] ?? 0;
            $price = $item['price'] ?? $item['unit_price'] ?? 0;
            $subtotal = $item['subtotal'] ?? $item['total'] ?? ($price * $quantity);

            // Validate required item fields
            if (!$menuItemId) {
                throw new Exception("Invalid cart item: missing menu_item_id. Available fields: " . implode(", ", array_keys($item)));
            }

            if (!$quantity || $quantity <= 0) {
                throw new Exception("Invalid cart item: invalid quantity");
            }

            if ($subtotal <= 0) {
                throw new Exception("Invalid cart item: invalid subtotal");
            }

            // Validate that menu_item_id exists in menu_items table
            $validateStmt->execute([$menuItemId]);
            $menuItem = $validateStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$menuItem) {
                throw new Exception("Menu item with ID {$menuItemId} does not exist or is not available. Please refresh your cart.");
            }

            // Recalculate subtotal based on current menu item price
            $currentPrice = $menuItem['price'];
            $calculatedSubtotal = $currentPrice * $quantity;
            
            // Log if there's a price discrepancy
            if (abs($calculatedSubtotal - $subtotal) > 0.01) {
                error_log("Price mismatch for item {$menuItemId}: Cart subtotal={$subtotal}, Calculated={$calculatedSubtotal}");
            }

            $success = $insertStmt->execute([
                $booking_id,
                $menuItemId,
                (int)$quantity,
                (float)$calculatedSubtotal
            ]);

            if (!$success) {
                throw new Exception("Failed to insert booking item: " . implode(", ", $insertStmt->errorInfo()));
            }
        }
    }

    private function clearCart($booking_id) {
        try {
            // Clear cart items from booking_items table
            $stmt = $this->pdo->prepare("DELETE FROM booking_items WHERE booking_id = ?");
            $stmt->execute([$booking_id]);
            
            // Also clear from session if stored there
            if (isset($_SESSION['cart'])) {
                unset($_SESSION['cart']);
            }
            
            error_log("Cart cleared successfully for booking_id: " . $booking_id);
        } catch (Exception $e) {
            // Log but don't fail the transaction for cart clearing
            error_log("Warning: Failed to clear cart: " . $e->getMessage());
        }
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'processCheckout') {
    $checkoutController = new CheckoutController();
    $checkoutController->processCheckout();
} else {
    // Invalid request
    session_start();
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../public/checkout.php");
    exit;
}
?>