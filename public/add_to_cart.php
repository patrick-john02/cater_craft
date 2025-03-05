<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/CartController.php';

$isAuthenticated = isset($_SESSION['user']);

$cartController = new CartController();

if (!isset($_SESSION['booking_id'])) {
    $_SESSION['booking_id'] = 1;
}
$booking_id = $_SESSION['booking_id'];

$cartItems = $cartController->fetchCartItems($booking_id);
$totalAmount = $cartController->fetchCartTotal($booking_id);
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cater | Cart</title>
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
                            <span>Cater Cart</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Cater Cart Section Begin -->
    <section class="shoping-cart spad">
    <div class="container">
        <div class="shoping__cart__table">
        <table>
    <thead>
        <tr>
            <th class="shoping__product">Products</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($cartItems)): ?>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><img src="./uploads/<?= htmlspecialchars($item['image']) ?>" width="50"></td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>₱<?= number_format($item['price'], 2) ?></td>
                    <td>₱<?= number_format($item['subtotal'], 2) ?></td>
                    <td>
                    <form action="../routes.php" method="POST">
    <input type="hidden" name="item_id" value="<?= htmlspecialchars($item['id']) ?>">
    <button type="submit" name="remove_from_cart">❌</button>
</form>

                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">Your cart is empty.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
        </div>

        <div class="shoping__checkout">
    <h5>Cart Total</h5>
    <ul>
        <li>Subtotal <span>₱<?= number_format($totalAmount, 2) ?></span></li>
        <li>Total <span>₱<?= number_format($totalAmount, 2) ?></span></li>
    </ul>
    <a href="checkout.php" class="primary-btn" <?= empty($cartItems) ? 'style="pointer-events: none; opacity: 0.5;"' : '' ?>>PROCEED TO CHECKOUT</a>

</div>
    </div>
</section>
    <!-- Shoping Cart Section End -->
    <?php include('includes/footer.php');?>

    <!-- Js Plugins -->
    <script src="../assets/organi/js/jquery-3.3.1.min.js"></script>
    <script src="../assets/organi/js/bootstrap.min.js"></script>
    <script src="../assets/organi/js/jquery.nice-select.min.js"></script>
    <script src="../assets/organi/js/jquery-ui.min.js"></script>
    <script src="../assets/organi/js/jquery.slicknav.js"></script>
    <script src="../assets/organi/js/mixitup.min.js"></script>
    <script src="../assets/organi/js/owl.carousel.min.js"></script>
    <script src="../assets/organi/js/main.js"></script>
</body>
</html>