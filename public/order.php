<?php
require_once '../config/database.php';
session_start();

$pdo = Database::getConnection();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];

if (!isset($_GET['package_id'])) {
    die("Invalid request");
}
$package_id = (int)$_GET['package_id'];

// Fetch package info
$stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->execute([$package_id]);
$package = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$package) die("Package not found");

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = (int)($_POST['quantity'] ?? 1);
    $payment_method = $_POST['payment_method'];
    $event_datetime = $_POST['event_datetime'] ?? null;

    if (empty($event_datetime)) {
        die("Please select the date and time for your event.");
    }

    // STEP 1: Create order first (with event_datetime)
    $stmt = $pdo->prepare("INSERT INTO package_orders (user_id, package_id, quantity, event_datetime, unit_price, status)
                           VALUES (?, ?, ?, ?, ?, 'pending_payment')");
    $stmt->execute([$user_id, $package_id, $quantity, $event_datetime, $package['price']]);
    $order_id = $pdo->lastInsertId();

    // STEP 2: Determine payment method
    if ($payment_method === 'gcash') {
        $payment_method_id = 1;
        $order_status = 'pending_payment';

        if (!empty($_FILES['gcash_proof']['name'])) {
            $uploadDir = "../uploads/gcash/";
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

            $fileName = time() . "_" . basename($_FILES["gcash_proof"]["name"]);
            $targetPath = $uploadDir . $fileName;
            move_uploaded_file($_FILES["gcash_proof"]["tmp_name"], $targetPath);

            // Save GCash proof
            $stmt = $pdo->prepare("INSERT INTO gcash_proof (order_id, proof_image) VALUES (?, ?)");
            $stmt->execute([$order_id, $fileName]);
        }

    } else {
        $payment_method_id = 2;
        $order_status = 'processing';
    }

    // STEP 3: Update order status
    $stmt = $pdo->prepare("UPDATE package_orders SET status = ? WHERE id = ?");
    $stmt->execute([$order_status, $order_id]);

    // STEP 4: Insert payment record (into package_payments)
    $stmt = $pdo->prepare("INSERT INTO package_payments (package_order_id, user_id, amount, payment_method_id, status)
                           VALUES (?, ?, ?, ?, 'pending')");
    $stmt->execute([$order_id, $user_id, $package['price'] * $quantity, $payment_method_id]);

    // STEP 5: Redirect to orders page
    header("Location: my_orders.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Cater Craft Package Order">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cater | Craft - Order</title>

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
        .gcash-upload { display: none; }
    </style>
</head>
<body>
<?php include('includes/navbar.php');?>

<div class="container mt-5">
    <h2 class="mb-4">Order Package: <?= htmlspecialchars($package['name']) ?></h2>

    <div class="row mb-4">
        <div class="col-md-5 text-center">
            <img src="./uploads/<?= htmlspecialchars($package['image']) ?>" 
                alt="<?= htmlspecialchars($package['name']) ?>" 
                class="img-fluid rounded shadow">
        </div>
        <div class="col-md-7">
            <h4>Description</h4>
            <p><?= nl2br(htmlspecialchars($package['description'])) ?></p>
            <p><strong>Price per set:</strong> ₱<?= number_format($package['price'], 2) ?></p>
        </div>
    </div>

    <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        <div class="form-group mb-3">
            <label for="quantity"><strong>Quantity:</strong></label>
            <input type="number" min="1" name="quantity" id="quantity" class="form-control" required value="1">
        </div>

        <div class="form-group mb-3">
            <label for="event_datetime"><strong>Event Date & Time:</strong></label>
            <input type="datetime-local" name="event_datetime" id="event_datetime" class="form-control" required>
            <small class="text-muted">Please select when your event will be held.</small>
        </div>

        <div class="form-group mb-3">
            <label><strong>Payment Method:</strong></label><br>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="gcash" value="gcash" required>
                <label class="form-check-label" for="gcash">GCash</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash" required>
                <label class="form-check-label" for="cash">Cash on Delivery</label>
            </div>
        </div>

        <div class="form-group gcash-upload mb-3">
            <label><strong>Upload Proof of GCash Payment:</strong></label>
            <input type="file" name="gcash_proof" class="form-control" accept="image/*">
            <small class="text-muted">Please upload a screenshot or receipt of your GCash payment.</small>
        </div>

        <button type="submit" class="btn btn-success btn-lg mt-3">Confirm Order</button>
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

<script>
    // Toggle upload section when GCash selected
    $('input[name="payment_method"]').on('change', function() {
        if ($(this).val() === 'gcash') {
            $('.gcash-upload').slideDown();
        } else {
            $('.gcash-upload').slideUp();
        }
    });
</script>
</body>
</html>
