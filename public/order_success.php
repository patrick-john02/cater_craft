<?php
$orderId = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;
if ($orderId <= 0) {
    die("Invalid Order ID.");
}
require_once '../config/database.php';
$pdo = Database::getConnection();
$stmt = $pdo->prepare("SELECT o.*, p.name FROM package_orders o JOIN packages p ON o.package_id = p.id WHERE o.id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) {
    die("Order not found.");
}
$packageName = htmlspecialchars($order['name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Placed</title>
    <link rel="stylesheet" href="../assets/organi/css/bootstrap.min.css">
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
    <h2>🎉 Order Successfully Placed!</h2>
    <p>Your order has been successfully recorded. We'll get in touch with you soon to confirm the details of your chosen package.</p>
    <p>Thank you for choosing <?= $packageName ?>!</p>
    <a href="landing_page.php" class="btn btn-primary">Go back to Home</a>
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