<?php
session_start();
require_once '../config/database.php';
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    die("You must be logged in to place an order.");
}
$user_id = $_SESSION['user']['id'];
$pdo = Database::getConnection();
if (!isset($_GET['package_id'])) {
    die("Package ID not specified.");
}
$package_id = (int) $_GET['package_id'];
$stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->execute([$package_id]);
$package = $stmt->fetch();
if (!$package) {
    die("Package not found.");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = max(1, (int) $_POST['quantity']); // Minimum 1
    $unit_price = $package['price'];
    $stmt = $pdo->prepare("
        INSERT INTO package_orders (user_id, package_id, quantity, unit_price)
        VALUES (?, ?, ?, ?)
    ");
    try {
        $stmt->execute([$user_id, $package_id, $quantity, $unit_price]);
        $orderId = $pdo->lastInsertId();
        header("Location: order_success.php?order_id=$orderId");
        exit;
    } catch (PDOException $e) {
        die("Order failed: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cater | Craft</title>
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
<?php include('includes/navbar.php');?>
<div class="container mt-5">
    <h2>Order Package: <?= htmlspecialchars($package['name']) ?></h2>
    <p><?= htmlspecialchars($package['description']) ?></p>
    <img src="./uploads/<?= htmlspecialchars($package['image']) ?>" 
         alt="<?= htmlspecialchars($package['name']) ?>" 
         class="img-fluid mb-3" 
         style="width: 300px; height: auto; cursor: pointer;" 
         data-toggle="modal" data-target="#imageModal">
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel"><?= htmlspecialchars($package['name']) ?> - Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Larger Image -->
                    <img src="./uploads/<?= htmlspecialchars($package['image']) ?>" 
                         alt="<?= htmlspecialchars($package['name']) ?>" 
                         class="img-fluid">
                </div>
            </div>
        </div>
    </div>
    <form method="POST">
        <div class="row align-items-center mb-3">
            <div class="col-md-4 text-center">
                <img src="./uploads/<?= htmlspecialchars($package['image']) ?>" alt="<?= htmlspecialchars($package['name']) ?>" class="img-fluid" style="width: 100px; height: auto;">
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <label>Unit Price: ₱<?= number_format($package['price'], 2) ?></label>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" min="1" name="quantity" id="quantity" class="form-control" required value="1">
                </div>
                <button type="submit" class="btn btn-success mt-3">Confirm Order</button>
            </div>
        </div>
    </form>
</div>
<?php include('includes/footer.php');?>
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