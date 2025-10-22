<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/UserController.php';
require_once __DIR__ . '/../../controllers/CartController.php';

$pdo = Database::getConnection();
$userController = new UserController($pdo);
$adminEmail = $userController->getAdminEmail();

$isAuthenticated = isset($_SESSION['user']);
$userName = $isAuthenticated ? $_SESSION['user']['name'] : null;

$cartCount = 0;

if ($isAuthenticated && !empty($_SESSION['booking_id'])) {
    $cartController = new CartController();
    $booking_id = $_SESSION['booking_id'];
    $cartCount = (int) $cartController->getCartCount($booking_id);
} else {
    unset($_SESSION['booking_id'], $_SESSION['cart']);
}
?>

<head>
    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            background: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            list-style: none;
            padding: 10px;
            min-width: 120px;
            right: 0;
            top: 25px;
        }

        .dropdown-menu li {
            padding: 5px 10px;
        }

        .dropdown-menu li a {
            text-decoration: none;
            color: black;
            display: block;
        }
        .dropdown-menu.active {
    display: block;
    }
    </style>
</head>

  <!-- Page Preloder -->
  <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Humberger Begin -->
    <div class="humberger__menu__overlay"></div>
    <div class="humberger__menu__wrapper">
        <div class="humberger__menu__logo">
            <a href="landing_page.php"><img src="../assets/organi/img/logo1.png" alt=""></a>
        </div>
        <div class="humberger__menu__cart">
            <ul>
                <li><a href="#"><i class="fa fa-shopping-bag"></i> <span>3</span></a></li>
            </ul>
            <div class="header__cart__price">item: <span>150.00</span></div>
        </div>
        <div class="humberger__menu__widget">
            
            <div class="header__top__right__auth">
                <a href="#"><i class="fa fa-user"></i> Login</a>
            </div>
            
        </div>
        <nav class="humberger__menu__nav mobile-menu">
            <ul>
                <li class="active"><a href="./index.html">Home</a></li>
                <li><a href="./shop-grid.html">Shop</a></li>
                <li><a href="./shop-grid.html">Services</a></li>
               
               
                <li><a href="./contact.html">Report</a></li>
            </ul>
        </nav>
        <div id="mobile-menu-wrap"></div>
        <div class="header__top__right__social">
            <a href="https://www.facebook.com/profile.php?id=100087983386234"><i class="fa fa-facebook"></i></a>
            <!-- <a href="#"><i class="fa fa-instagram"></i></a> -->
        </div>
        <div class="humberger__menu__contact">
            <ul>
            <li><i class="fa fa-envelope"></i> <?= htmlspecialchars($adminEmail) ?></li>
            </ul>
        </div>
    </div>
    <!-- Humberger End -->

    <!-- Header Section Begin -->
    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__left">
                            <ul>
                            <li><i class="fa fa-envelope"></i> <?= htmlspecialchars($adminEmail) ?></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__right">
                            <div class="header__top__right__social">
                                <a href="https://www.facebook.com/profile.php?id=100087983386234"><i class="fa fa-facebook"></i></a>
                                <!-- <a href="#"><i class="fa fa-instagram"></i></a> -->
                            </div>
                                    <?php if ($isAuthenticated) : ?>
                                    <div class="header__top__right__auth">
                                        <div id="user-menu" class="dropdown">
                                                <a href="#" class="dropdown-toggle"><i class="fa fa-user"></i> <?= htmlspecialchars($userName) ?></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php else : ?>
                                <div class="header__top__right__auth">
                                    <a href="login.php"><i class="fa fa-user"></i> Login |</a> 
                                </div>
                             <div class="header__top__right__auth">
                                 <a href="register.php"><i class="fa fa-user-plus"></i> Signup</a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="header__logo">
                        <a href="./index.html"><img src="../assets/organi/img/logo1.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <nav class="header__menu">
                        <ul>
                            <li class="active"><a href="./landing_page.php">Home</a></li>
                            
                            <li><a href="./services.php">Package</a></li>
                            
                            
                            <!-- <li><a href="./report.php">Report</a></li>
                            <li><a href="./aboutus.php">about us</a></li>
                            <li><a href="./order_history.php"> orders</a></li> -->

                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__cart">
                        <ul>
                            <li><a href="#"><i class="fa fa-comments"></i></a></li> <!-- Chat Icon -->
                            <li>
  <a href="add_to_cart.php">
    <i class="fa fa-shopping-bag"></i>
    <span id="cart-count"><?= htmlspecialchars($cartCount) ?></span>
  </a>
</li>
                            <li><a href="my_orders_booking.php"><i class="fa fa-list-alt"></i></a></li>
                        </ul>

                        <!-- <div class="header__cart__price">Total: <span id="cart-total">₱<?= number_format($totalAmount, 2) ?></span></div> -->
                </div>
            </div>
        </div>
            <div class="humberger__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
    <!-- Header Section End -->

<!-- Load jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    let dropdownToggle = document.querySelector(".dropdown-toggle");
    let dropdownMenu = document.querySelector(".dropdown-menu");
    
    if (dropdownToggle) {
        dropdownToggle.addEventListener("click", function (event) {
            event.preventDefault(); 
            dropdownMenu.classList.toggle("active"); 
        });

        document.addEventListener("click", function (event) {
            if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove("active");
            }
        });
    }
});
</script>

<script>
function updateCartCount() {
  $.ajax({
    url: "/cater-craft/routes.php?route=cart_count",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response && typeof response.cartCount !== "undefined") {
        $("#cart-count").text(response.cartCount);
      } else {
        $("#cart-count").text(0);
      }
    },
    error: function () {
      console.error("Failed to fetch cart count.");
      $("#cart-count").text(0);
    }
  });
}

// Update every 3s only if logged in
<?php if ($isAuthenticated): ?>
setInterval(updateCartCount, 3000);
<?php endif; ?>

$(document).on("click", ".add-to-cart", function (event) {
  event.preventDefault();

  const menuItemId = $(this).data("id");
  const price = $(this).data("price");

  $.ajax({
    url: "routes.php?route=add_to_cart",
    type: "POST",
    data: { menu_item_id: menuItemId, price: price, quantity: 1 },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        updateCartCount();
      } else {
        alert(response.message);
      }
    },
    error: function () {
      console.error("Failed to add item to cart.");
    }
  });
});
</script>