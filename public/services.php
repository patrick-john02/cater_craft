<?php
require_once '../config/database.php'; 
$pdo = Database::getConnection();

// Fetch packages
$packages = [];
try {
    $stmt = $pdo->query("SELECT * FROM packages ORDER BY created_at DESC");
    $packages = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Failed to fetch packages: " . $e->getMessage());
}

// Check admin status
$adminInactive = false;
try {
    $stmt = $pdo->prepare("SELECT isActive FROM users WHERE user_type_id = 2 LIMIT 1"); 
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Only mark admin as inactive if isActive = 1
    if ($admin && $admin['isActive'] == 1) {
        $adminInactive = true;
    }
} catch (PDOException $e) {
    die("Failed to check admin status: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Catering Menu">
    <meta name="keywords" content="Catering, Food, Menu">
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

    <style>
        /* Image box styling */
        .uniform-image-box {
            width: 100%;
            height: 250px;
            overflow: hidden;
            border-radius: 8px;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative; /* needed for ribbon positioning */
        }

        .uniform-image-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .uniform-image-box img:hover {
            transform: scale(1.05);
        }

        /* Ribbon styling */
        .ribbon {
            width: 150px;
            background: #e74c3c;
            color: #fff;
            text-align: center;
            line-height: 30px;
            font-size: 14px;
            font-weight: bold;
            position: absolute;
            top: 15px;
            right: -40px;
            transform: rotate(45deg);
            z-index: 10;
            box-shadow: 0 3px 10px rgba(0,0,0,0.3);
        }

        .disabled-btn {
            cursor: not-allowed;
        }
    </style>
</head>

<body>
<?php include('includes/navbar.php');?>

<!-- Blog Details Hero Begin -->
<section class="blog-details-hero set-bg" data-setbg="../assets/organi/img/blog/details/1.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="blog__details__hero__text">
                    <h2> Ingga's Catering </h2>
                    <ul>
                        <li>Locations: San Jose Village Extension Atulayan Sur, Tuguegarao City, Philippines</li>
                        <li>Time Availability: 24/7</li>
                        <li>Contact: 0965 310 1013</li>
                        <li>Email: joymaangundan@gmail.com</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Blog Details Hero End -->

<!-- Related Blog Section Begin -->
<section class="related-blog spad">
    <div class="container">
        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="section-title related-blog-title">
                    <h2>List of Menu</h2>
                </div>
            </div>
            <div class="col-lg-6 text-end">
                <a href="my_orders.php" class="btn btn-success mt-2">
                    <i class="fa fa-list"></i> My Orders
                </a>
            </div>
        </div>
        <div class="row">
            <?php foreach ($packages as $package): ?>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="blog__item">
                    <div class="blog__item__pic uniform-image-box">
                        <img src="./uploads/<?= htmlspecialchars($package['image']) ?>" alt="<?= htmlspecialchars($package['name']) ?>">
                        <?php if ($adminInactive): ?>
                            <div class="ribbon">Not Available</div>
                        <?php endif; ?>
                    </div>
                    <div class="blog__item__text">
                        <ul>
                            <li><i class="fa fa-calendar-o"></i> <?= date('F j, Y', strtotime($package['created_at'])) ?></li>
                        </ul>
                        <h5><a href="#"><?= htmlspecialchars($package['name']) ?></a></h5>
                        <p><?= nl2br(htmlspecialchars($package['description'])) ?></p>
                        <p><strong>Price: ₱<?= number_format($package['price'], 2) ?></strong></p>

                        <?php if ($adminInactive): ?>
                            <button class="btn btn-sm btn-secondary mt-2 disabled-btn" disabled>
                                Order Now
                            </button>
                        <?php else: ?>
                            <a href="order.php?package_id=<?= $package['id'] ?>" class="btn btn-sm btn-primary mt-2">Order Now</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- Related Blog Section End -->

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
