<?php 
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/MenuItemController.php';

// User authentication
$isAuthenticated = isset($_SESSION['user']);

$pdo = Database::getConnection();
$item_id = $_GET['id'] ?? $_GET['menu_item_id'] ?? null;

if (!$item_id) {
    die("Invalid Item ID");
}

$controller = new MenuItemController($pdo);
$item = $controller->showItemDetails($item_id);
?>

<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product | Details</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
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
                    <h2><?= htmlspecialchars($item['name']) ?></h2>
                    <div class="breadcrumb__option">
                        <a href="landing_page.php">Home</a>
                        <a href="categories.php?id=<?= $item['category_id'] ?>"><?= htmlspecialchars($item['category']) ?></a>
                        <span><?= htmlspecialchars($item['name']) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <!-- Breadcrumb Section End -->

    <!-- Product Details Section Begin -->
    <section class="product-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="product__details__pic">
                    <img src="./uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product__details__pic__item--large">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="product__details__text">
                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                    <div class="product__details__price">₱<?= number_format($item['price'], 2) ?></div>
                        <p><?= htmlspecialchars($item['description']) ?></p>
                            <ul>
                                <li><b>Availability:</b> <?= $item['availability'] ? 'In Stock' : 'Out of Stock' ?></li>
                                <li><b>Serving Size:</b> <?= htmlspecialchars($item['serving_size'] ?? 'plate') ?></li>
                            </ul>
                            <?php if (!$isAuthenticated): ?>
                                <a href="login.php" class="primary-btn">Login to Add to Cart</a>
                                <?php else: ?>
    <form id="addToCartForm">
        <input type="hidden" name="menu_item_id" value="<?= htmlspecialchars($item['id']) ?>">
        <input type="hidden" name="price" value="<?= htmlspecialchars($item['price']) ?>">
        <input type="hidden" name="booking_id" value="<?= $_SESSION['booking_id'] ?? 1 ?>">

        <div class="product__details__quantity">
            <div class="quantity">
                <input type="number" name="quantity" value="1" min="1" required>
            </div>
        </div>
        <button type="submit" class="primary-btn">ADD TO CART</button>
    </form>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <script src="../assets/organi/js/jquery-3.3.1.min.js"></>
    <script src="../assets/organi/js/bootstrap.min.js"></script>
    <script src="../assets/organi/js/jquery.nice-select.min.js"></script>
    <script src="../assets/organi/js/jquery-ui.min.js"></script>
    <script src="../assets/organi/js/jquery.slicknav.js"></script>
    <script src="../assets/organi/js/mixitup.min.js"></script>
    <script src="../assets/organi/js/owl.carousel.min.js"></script>
    <script src="../assets/organi/js/main.js"></script>
    <script>
    $(document).ready(function () {
    $("#addToCartForm").submit(function (event) {
        event.preventDefault(); 

        $.ajax({
            url: "../routes.php?route=add_to_cart",  
            type: "POST",
            data: $(this).serialize() + "&add_to_cart=true",
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    iziToast.success({
                        title: "Success",
                        message: response.message,
                        position: "topRight"
                    });
                } else {
                    iziToast.error({
                        title: "Error",
                        message: response.message,
                        position: "topRight"
                    });
                }
            },
            error: function () {
                iziToast.error({
                    title: "Error",
                    message: "Something went wrong!",
                    position: "topRight"
                });
            }
        });
    });
});
</script>
</body>
</html>