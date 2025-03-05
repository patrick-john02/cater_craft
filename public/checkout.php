<?php
session_start();
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../config/database.php';

$cartController = new CartController();

// Ensure booking ID is set in the session
if (!isset($_SESSION['booking_id']) || empty($_SESSION['booking_id'])) {
    $_SESSION['booking_id'] = uniqid('BKG_'); // Generate a unique booking ID
}

$booking_id = $_SESSION['booking_id']; // Assign session booking ID to a variable

$cartItems = $cartController->fetchCartItems($booking_id);
$totalAmount = $cartController->fetchCartTotal($booking_id);

$pdo = Database::getConnection();
$stmt = $pdo->query("SELECT * FROM payment_methods");
$payment_methods = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cater | Checkout</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
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
<?php include('includes/navbar.php');?>
 <!-- Breadcrumb Section Begin -->
 <section class="breadcrumb-section set-bg" data-setbg="../assets/organi/img/blog/details/1.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Cater Cart</h2>
                        <div class="breadcrumb__option">
                            <a href="landing_page.php">Home</a>
                            <a href="add_to_cart.php">Cart</a>
                            <span>Check Out</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="row">
            </div>
            <div class="checkout__form">
                <h4>Billing Details</h4>
                <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>


                <form action="../controllers/CheckoutController.php?action=processCheckout" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="booking_id" value="<?= htmlspecialchars($booking_id) ?>">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="checkout__input">
                            <p>First Name<span>*</span></p>
                            <input type="text" name="first_name" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="checkout__input">
                            <p>Last Name<span>*</span></p>
                            <input type="text" name="last_name" required>
                        </div>
                    </div>
                </div>
                <div class="checkout__input">
                    <p>Email<span>*</span></p>
                    <input type="email" name="email" required>
                </div>
                <div class="checkout__input">
                    <p>Phone<span>*</span></p>
                    <input type="text" name="phone" required>
                </div>
                <div class="checkout__input">
                    <p>Address<span>*</span></p>
                    <input type="text" name="address" required>
                </div>
                <div class="checkout__input">
    <p>Venue<span>*</span></p>
    <input type="text" name="venue" required>
</div>

                <div class="checkout__input">
                    <p>Event Date<span>*</span></p>
                    <input type="date" name="event_date" required>
                </div>
                <div class="checkout__input">
                    <p>Event Time<span>*</span></p>
                    <input type="time" name="event_time" required>
                </div>
                <div class="checkout__input">
                    <p>Number of Guests<span>*</span></p>
                    <input type="number" name="guests" required>
                </div>
                <div class="checkout__input">
                    <p>Special Requests</p>
                    <textarea name="special_requests"></textarea>
                </div>

                <!-- Order Summary -->
                <div class="checkout__order">
                    <h4>Your Order</h4>
                    <ul>
                        <?php if (!empty($cartItems)): ?>
                            <?php foreach ($cartItems as $item): ?>
                                <li><?= htmlspecialchars($item['name']) ?> (x<?= $item['quantity'] ?>) 
                                    <span>₱<?= number_format($item['subtotal'], 2) ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>No items in cart</li>
                        <?php endif; ?>
                    </ul>
                    <div class="checkout__order__total">Total <span>₱<?= number_format($totalAmount, 2) ?></span></div>
                    <input type="hidden" name="total_amount" value="<?= $totalAmount ?>">

                    <!-- Payment Methods -->
                    <div class="checkout__input__checkbox">
        <label>
            <input type="radio" name="payment_method" value="Cash" required onclick="toggleGcashUpload(false)"> Cash Payment
        </label>
    </div>
    <div class="checkout__input">
    <p>Choose Payment Method<span>*</span></p>
    <?php foreach ($payment_methods as $method): ?>
        <div class="checkout__input__checkbox">
            <label>
                <input type="radio" name="payment_method" value="<?= $method['id'] ?>" required 
                    onclick="toggleGcashUpload(<?= ($method['method'] == 'gcash') ? 'true' : 'false' ?>)">
                <?= ucfirst($method['method']) ?>
            </label>
        </div>
    <?php endforeach; ?>
</div>

<!-- GCash Fields (Reference Number & Receipt Upload) -->
<div id="gcash_fields" style="display: none;">
    <div class="checkout__input">
        <label>GCash Reference Number<span>*</span></label>
        <input type="text" name="gcash_reference" id="gcash_reference">
    </div>
    <div class="checkout__input">
        <label>Upload GCash Receipt<span>*</span></label>
        <input type="file" name="gcash_receipt" id="gcash_receipt" accept="image/*">
    </div>
</div>

    <button type="submit" class="site-btn">PLACE ORDER</button>
                </div>
            </form>

            </div>
        </div>
    </section>
    <!-- Checkout Section End -->

    <!-- Js Plugins -->
    <script src="../assets/organi/js/jquery-3.3.1.min.js"></script>
    <script src="../assets/organi/js/bootstrap.min.js"></script>
    <script src="../assets/organi/js/jquery.nice-select.min.js"></script>
    <script src="../assets/organi/js/jquery-ui.min.js"></script>
    <script src="../assets/organi/js/jquery.slicknav.js"></script>
    <script src="../assets/organi/js/mixitup.min.js"></script>
    <script src="../assets/organi/js/owl.carousel.min.js"></script>
    <script src="../assets/organi/js/main.js"></script>
    <script>
function toggleGcashUpload(show) {
    let gcashFields = document.getElementById('gcash_fields');
    let gcashRef = document.getElementById('gcash_reference');
    let gcashReceipt = document.getElementById('gcash_receipt');

    if (show) {
        gcashFields.style.display = 'block';
        gcashRef.setAttribute('required', 'required');
        gcashReceipt.setAttribute('required', 'required');
    } else {
        gcashFields.style.display = 'none';
        gcashRef.removeAttribute('required');
        gcashReceipt.removeAttribute('required');
    }
}
</script>
</body>
</html>