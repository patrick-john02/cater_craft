<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/CartController.php';

$isAuthenticated = isset($_SESSION['user']);
$cartController = new CartController();

// Ensure we have a consistent booking ID
if (!isset($_SESSION['booking_id']) || empty($_SESSION['booking_id'])) {
    $_SESSION['booking_id'] = uniqid('BKG_');
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
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/organi/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/style.css" type="text/css">
<style>
.quantity {
  display: flex;
  justify-content: center;
  align-items: center;
}

.pro-qty {
  display: inline-flex;
  align-items: center;
  border: 1px solid #ddd;
  border-radius: 50px;
  background: #fff;
  overflow: hidden;
  width: 130px;
  height: 42px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.pro-qty .qtybtn {
  width: 42px;
  height: 100%;
  border: none;
  background: #f5f5f5;
  font-size: 20px;
  font-weight: 600;
  color: #333;
  cursor: pointer;
  transition: all 0.25s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.pro-qty .qtybtn:hover {
  background: #7fad39;
  color: #fff;
}

.pro-qty .qty-input {
  width: 45px;
  border: none;
  text-align: center;
  font-size: 16px;
  font-weight: 500;
  color: #222;
  background: transparent;
  outline: none;
  user-select: none;
}

.shoping__cart__quantity {
  text-align: center;
}

</style>

</head>
<body>
<?php include('includes/navbar.php');?>
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
    
    <section class="shoping-cart spad">
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
              <td class="shoping__cart__item">
                  <img src="./uploads/<?= htmlspecialchars($item['image']) ?>" alt="" width="80">
                  <h5><?= htmlspecialchars($item['name']) ?></h5>
              </td>
              <td class="shoping__cart__price">
                  ₱<?= number_format($item['price'], 2) ?>
              </td>
              <td class="shoping__cart__quantity">
                  <div class="quantity">
                     <div class="pro-qty" data-item-id="<?= $item['id'] ?>">
    <input 
        type="text" 
        value="<?= $item['quantity'] ?>" 
        id="qty_<?= $item['id'] ?>" 
        class="qty-input" 
        readonly
    >
</div>

                  </div>
              </td>
              <td class="shoping__cart__total">
                  ₱<?= number_format($item['subtotal'], 2) ?>
              </td>
              <td>
                  <button class="btn btn-sm btn-danger" onclick="removeFromCart(<?= $item['id'] ?>)">Remove</button>
              </td>
          </tr>
      <?php endforeach; ?>
  <?php else: ?>
      <tr><td colspan="5" class="text-center">Your cart is empty.</td></tr>
  <?php endif; ?>
</tbody>

                </table>
            </div>
            
            <?php if (!empty($cartItems)): ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="shoping__cart__btns">
                            <a href="menu.php" class="primary-btn cart-btn">CONTINUE SHOPPING</a>
                            <a href="#" class="primary-btn cart-btn cart-btn-right" onclick="updateCart()">
                                <span class="icon_loading"></span> UPDATE CART
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <!-- Coupon section if needed -->
                    </div>
                    <div class="col-lg-6">
                        <div class="shoping__checkout">
                            <h5>Cart Total</h5>
                            <ul>
                                <li>Subtotal <span>₱<?= number_format($totalAmount, 2) ?></span></li>
                                <li>Total <span>₱<?= number_format($totalAmount, 2) ?></span></li>
                            </ul>
                            <a href="checkout.php" class="primary-btn">PROCEED TO CHECKOUT</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <?php include('includes/footer.php');?>
    
    <script src="../assets/organi/js/jquery-3.3.1.min.js"></script>
    <script src="../assets/organi/js/bootstrap.min.js"></script>
    <script src="../assets/organi/js/jquery.nice-select.min.js"></script>
    <script src="../assets/organi/js/jquery-ui.min.js"></script>
    <script src="../assets/organi/js/jquery.slicknav.js"></script>
    <script src="../assets/organi/js/mixitup.min.js"></script>
    <script src="../assets/organi/js/owl.carousel.min.js"></script>
    <script src="../assets/organi/js/main.js"></script>
    
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Quantity button click handler
    $(document).on('click', '.qtybtn', function(e) {
    e.preventDefault();

    const itemId = $(this).closest('.pro-qty').data('item-id');
    const qtyInput = $("#qty_" + itemId);
    let currentQty = parseInt(qtyInput.val()) || 1;

    let newQty = currentQty + ($(this).hasClass('inc') ? 1 : -1);

    if (newQty < 1) {
        if (confirm('Remove this item from the cart?')) {
            removeFromCart(itemId);
        }
        return;
    }

    qtyInput.val(newQty);
    updateQuantityInDB(itemId, newQty);
});
});

function updateQuantityInDB(itemId, quantity) {
    $.ajax({
        url: '../routes.php',
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'update_cart_quantity',
            item_id: itemId,
            booking_id: '<?= $booking_id ?>',
            quantity: quantity
        },
        success: function(response) {
            if (response.status === 'success') {
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert('Failed to update cart.');
        }
    });
}

function removeFromCart(itemId) {
    $.ajax({
        url: '../routes.php',
        type: 'POST',
        data: {
            remove_from_cart: true,
            item_id: itemId,
            booking_id: '<?= $booking_id ?>'
        },
        success: function() {
            location.reload();
        },
        error: function() {
            alert('Error removing item.');
        }
    });
}
</script>

</body>
</html>