<?php
session_start();

require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../config/database.php';

// Check if user is authenticated
if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "You must be logged in to checkout.";
    header("Location: login.php");
    exit;
}

$pdo = Database::getConnection();
$userId = $_SESSION['user']['id'];

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    $_SESSION['error'] = "User data not found.";
    header("Location: login.php");
    exit;
}

// Parse user data safely
$fullName = $userData['name'] ?? '';
$nameParts = explode(' ', $fullName, 2);
$firstName = $nameParts[0] ?? '';
$lastName = $nameParts[1] ?? '';
$email = $userData['email'] ?? '';
$phone = $userData['phone'] ?? '';
$address = $userData['address'] ?? '';

// Initialize cart controller
$cartController = new CartController();

// Generate booking ID if not exists
if (!isset($_SESSION['booking_id']) || empty($_SESSION['booking_id'])) {
    $_SESSION['booking_id'] = uniqid('BKG_');
}
$booking_id = $_SESSION['booking_id'];

// Get cart items
$cartItems = $cartController->fetchCartItems($booking_id);
$totalAmount = $cartController->fetchCartTotal($booking_id);

// Validate cart is not empty
if (empty($cartItems) || $totalAmount <= 0) {
    $_SESSION['error'] = "Your cart is empty. Please add items before checkout.";
    header("Location: add_to_cart.php");
    exit;
}

// Get payment methods
$stmt = $pdo->query("SELECT * FROM payment_methods ORDER BY method");
$payment_methods = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Cater Checkout">
    <meta name="keywords" content="catering, checkout, order">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cater | Checkout</title>
    
    <!-- CSS Files -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/organi/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/style.css" type="text/css">
</head>
<body>
    <?php include('includes/navbar.php'); ?>
    
    <!-- Breadcrumb Section -->
    <section class="breadcrumb-section set-bg" data-setbg="../assets/organi/img/blog/details/1.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Checkout</h2>
                        <div class="breadcrumb__option">
                            <a href="landing_page.php">Home</a>
                            <a href="add_to_cart.php">Cart</a>
                            <span>Checkout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Checkout Section -->
    <section class="checkout spad">
        <div class="container">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <form action="../controllers/CheckoutController.php?action=processCheckout" method="POST" enctype="multipart/form-data" id="checkout-form">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="checkout__form">
                            <h4>Billing Details</h4>
                            
                            <!-- Hidden user information -->
                            <input type="hidden" name="booking_id" value="<?= htmlspecialchars($booking_id) ?>">
                            <input type="hidden" name="first_name" value="<?= htmlspecialchars($firstName) ?>">
                            <input type="hidden" name="last_name" value="<?= htmlspecialchars($lastName) ?>">
                            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                            <input type="hidden" name="phone" value="<?= htmlspecialchars($phone) ?>">
                            <input type="hidden" name="address" value="<?= htmlspecialchars($address) ?>">
                            
                            <!-- Display user info (read-only) -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Customer Name</p>
                                        <input type="text" value="<?= htmlspecialchars($fullName) ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Email Address</p>
                                        <input type="text" value="<?= htmlspecialchars($email) ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Phone Number</p>
                                        <input type="text" value="<?= htmlspecialchars($phone) ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Address</p>
                                        <input type="text" value="<?= htmlspecialchars($address) ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            <h5>Event Details</h5>
                            
                            <!-- Event Details -->
                            <div class="checkout__input">
                                <p>Event Venue<span>*</span></p>
                                <input type="text" name="venue" required placeholder="Enter venue location">
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Event Date<span>*</span></p>
                                        <input type="date" name="event_date" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Event Time<span>*</span></p>
                                        <input type="time" name="event_time" required>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="checkout__input">
                                <p>Special Requests</p>
                                <textarea name="special_requests" rows="4" placeholder="Any special dietary requirements or additional requests..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="checkout__order">
                            <h4>Your Order</h4>
                            <div class="checkout__order__products">Products <span>Total</span></div>
                            <ul>
    <?php foreach ($cartItems as $item): ?>
        <li>
            <?= htmlspecialchars($item['name']) ?> 
            <span class="quantity">(x<?= (int)$item['quantity'] ?>)</span>
            <span>₱<?= number_format($item['subtotal'], 2) ?></span>
            <input type="hidden" name="cart_items[<?= $item['menu_item_id'] ?>]" value="<?= $item['quantity'] ?>">
        </li>
    <?php endforeach; ?>
</ul>
                            <div class="checkout__order__subtotal">Subtotal <span>₱<?= number_format($totalAmount, 2) ?></span></div>
                            <div class="checkout__order__total">Total <span>₱<?= number_format($totalAmount, 2) ?></span></div>
                            
                            <input type="hidden" name="total_amount" value="<?= $totalAmount ?>">
                            
                            <!-- Payment Methods -->
<div class="checkout__input">
    <p>Payment Method<span>*</span></p>
    <select name="payment_method" id="payment_method" class="form-control" required>
        <option value="" disabled selected>Select a payment method</option>
        <?php foreach ($payment_methods as $method): ?>
            <option
                value="<?= $method['id'] ?>"
                data-method="<?= strtolower($method['method']) ?>"
                title="<?= isset($method['description']) ? htmlspecialchars($method['description']) : '' ?>"
            >
                <?= ucfirst(htmlspecialchars($method['method'])) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if (!empty($payment_methods)): ?>
        <small class="text-muted d-block mt-1">
            Choose how you would like to pay (e.g., GCash, Cash on Delivery, etc.).
        </small>
    <?php endif; ?>
                            </div>
                            
                            <!-- GCash Payment Fields -->
                            <div id="gcash_fields" style="display:none;">
                                <div class="checkout__input">
                                    <p>GCash Reference Number<span>*</span></p>
                                    <input type="text" name="gcash_reference" id="gcash_reference" placeholder="Enter GCash reference number">
                                </div>
                                <div class="checkout__input">
                                    <p>Upload GCash Receipt<span>*</span></p>
                                    <input type="file" name="gcash_receipt" id="gcash_receipt" accept="image/*,.pdf">
                                    <small class="text-muted">Accepted formats: JPG, PNG, PDF (Max 5MB)</small>
                                </div>
                            </div>
                            
                         
                            
                            <button type="submit" class="site-btn" id="submit-btn">PLACE ORDER</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    
    <!-- JavaScript Files -->
    <script src="../assets/organi/js/jquery-3.3.1.min.js"></script>
    <script src="../assets/organi/js/bootstrap.min.js"></script>
    <script src="../assets/organi/js/jquery.nice-select.min.js"></script>
    <script src="../assets/organi/js/jquery-ui.min.js"></script>
    <script src="../assets/organi/js/jquery.slicknav.js"></script>
    <script src="../assets/organi/js/mixitup.min.js"></script>
    <script src="../assets/organi/js/owl.carousel.min.js"></script>
    <script src="../assets/organi/js/main.js"></script>
    
<script>
// Handle payment method dropdown change
document.getElementById('payment_method').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const method = selectedOption.getAttribute('data-method') || '';
    togglePaymentFields(method);
});

function togglePaymentFields(method) {
    const gcashFields = document.getElementById('gcash_fields');
    const gcashRef = document.getElementById('gcash_reference');
    const gcashReceipt = document.getElementById('gcash_receipt');

    if (method === 'gcash') {
        gcashFields.style.display = 'block';
        gcashRef.setAttribute('required', 'required');
        gcashReceipt.setAttribute('required', 'required');
    } else {
        gcashFields.style.display = 'none';
        gcashRef.removeAttribute('required');
        gcashReceipt.removeAttribute('required');
        gcashRef.value = '';
        gcashReceipt.value = '';
    }
}

// Form submission validation
document.getElementById('checkout-form').addEventListener('submit', function (e) {
    const submitBtn = document.getElementById('submit-btn');
    const paymentMethod = document.getElementById('payment_method');
    const selectedPayment = paymentMethod.value;

    // Check if payment method is selected
    if (!selectedPayment || selectedPayment === '') {
        e.preventDefault();
        alert('Please select a payment method.');
        paymentMethod.focus();
        return;
    }

    // Disable submit button to prevent double submission
    submitBtn.disabled = true;
    submitBtn.textContent = 'Processing...';

    // Re-enable after 5 seconds as fallback
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'PLACE ORDER';
    }, 5000);
});

// File upload validation
document.getElementById('gcash_receipt').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            alert('File size must be less than 5MB');
            this.value = '';
        }
    }
});
</script>
</body>
</html>