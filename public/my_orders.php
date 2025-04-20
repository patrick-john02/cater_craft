<?php
require_once __DIR__ . '/../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isAuthenticated = isset($_SESSION['user']);
$userName = $isAuthenticated ? $_SESSION['user']['name'] : null;
$userId = $isAuthenticated ? $_SESSION['user']['id'] : null;

if (!$isAuthenticated || !$userId) {
    die("You must be logged in to view your orders.");
}

try {
    $pdo = Database::getConnection();

    $stmt = $pdo->prepare("
        SELECT 
            po.id AS order_id,
            po.quantity,
            po.unit_price,
            po.total_price,
            po.status,
            po.ordered_at,
            p.name AS package_name,
            p.image AS package_image
        FROM 
            package_orders po
        JOIN 
            packages p ON po.package_id = p.id
        WHERE 
            po.user_id = :user_id
        ORDER BY po.ordered_at DESC
    ");

    $stmt->execute(['user_id' => $userId]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cater | Craft</title>

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
<?php include('includes/navbar.php'); ?>

<!-- Order Section -->
<section class="order-section spad">
    <div class="container">
        <div class="section-title">
            <h2>My Package Orders</h2>
        </div>

        <?php if (count($orders) === 0): ?>
            <p class="text-center">You haven't ordered any packages yet.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($orders as $order): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm border-0 h-100">
                            <img class="card-img-top" src="./uploads/<?= htmlspecialchars($order['package_image']) ?>" alt="<?= htmlspecialchars($order['package_name']) ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($order['package_name']) ?></h5>
                                <p class="card-text mb-1"><strong>Quantity:</strong> <?= $order['quantity'] ?></p>
                                <p class="card-text mb-1"><strong>Unit Price:</strong> ₱<?= number_format($order['unit_price'], 2) ?></p>
                                <p class="card-text mb-1"><strong>Total:</strong> ₱<?= number_format($order['total_price'], 2) ?></p>
                                <p class="card-text mb-1"><strong>Status:</strong> <span class="badge badge-info"><?= ucfirst($order['status']) ?></span></p>
                                <p class="card-text"><small class="text-muted">Ordered At: <?= date("F j, Y, g:i a", strtotime($order['ordered_at'])) ?></small></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

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