<?php
require_once '../models/Checkout.php';
require_once '../config/database.php';

class CheckoutController {
    private $checkoutModel;

    public function __construct() {
        $this->checkoutModel = new CheckoutModel();
    }

    public function processCheckout() {
        session_start();

        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
            $_SESSION['error'] = "You must be logged in to proceed.";
            header("Location: ../public/login.php");
            exit;
        }

        $user_id = $_SESSION['user']['id'];

        // Capture form data
        $data = [
            'customer_id'       => $user_id,
            'event_date'        => $_POST['event_date'] ?? '',
            'event_time'        => $_POST['event_time'] ?? '',
            'guests'            => $_POST['guests'] ?? '',
            'venue'             => $_POST['venue'] ?? '',
            'special_requests'  => $_POST['special_requests'] ?? '',
            'total_amount'      => isset($_POST['total_amount']) && is_numeric($_POST['total_amount']) ? $_POST['total_amount'] : null,
            'payment_method'    => $_POST['payment_method'] ?? '',
            'gcash_reference'   => $_POST['gcash_reference'] ?? '',
            'gcash_receipt'     => null, // Default null, will update if file is uploaded
        ];

        // ✅ Handle File Upload
        if (isset($_FILES['gcash_receipt']) && $_FILES['gcash_receipt']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/receipts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Create directory if not exists
            }

            $fileTmpPath = $_FILES['gcash_receipt']['tmp_name'];
            $fileName = time() . '_' . basename($_FILES['gcash_receipt']['name']); // Unique name
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $filePath)) {
                $data['gcash_receipt'] = 'uploads/receipts/' . $fileName; // Store relative path
            } else {
                $_SESSION['error'] = "Failed to upload receipt.";
                header("Location: ../public/checkout.php");
                exit;
            }
        }

        // Validate required fields
        if (empty($data['event_date']) || empty($data['event_time']) || empty($data['guests']) || empty($data['venue'])) {
            $_SESSION['error'] = "Please fill in all required fields.";
            header("Location: ../public/checkout.php");
            exit;
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            // Insert into bookings table
            $stmt = $pdo->prepare("
                INSERT INTO bookings (customer_id, event_date, event_time, guests, venue, special_requests, total_amount, status_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 1)
            ");
            $stmt->execute([
                $data['customer_id'],
                $data['event_date'],
                $data['event_time'],
                $data['guests'],
                $data['venue'],
                $data['special_requests'],
                $data['total_amount']
            ]);

            $booking_id = $pdo->lastInsertId(); // Get newly created booking ID

            // ✅ Insert ordered items into booking_items
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    $stmt = $pdo->prepare("
                        INSERT INTO booking_items (booking_id, menu_item_id, quantity, subtotal)
                        VALUES (?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $booking_id,
                        $item['id'],
                        $item['quantity'],
                        $item['subtotal']
                    ]);
                }
            }

            // ✅ Insert payment details
            $stmt = $pdo->prepare("
                INSERT INTO payments (booking_id, user_id, amount, payment_method_id, gcash_reference, gcash_receipt)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $booking_id,
                $data['customer_id'],
                $data['total_amount'],
                $data['payment_method'],
                $data['gcash_reference'],
                $data['gcash_receipt']
            ]);

            $pdo->commit();

            // ✅ Clear only the session cart (not the booking_items)
            unset($_SESSION['cart']);

            $_SESSION['success'] = "Your order has been successfully placed!";
            header("Location: ../public/checkout.php"); // Redirect to the orders page
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['error'] = "Error processing order: " . $e->getMessage();
            header("Location: ../public/checkout.php");
            exit();
        }
    }
}

// Handle Request from Form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === "processCheckout") {
    $checkoutController = new CheckoutController();
    $checkoutController->processCheckout();
}
?>
