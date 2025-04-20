<?php
require_once '../config/database.php'; 
$pdo = Database::getConnection();

$packages = [];
try {
    $stmt = $pdo->query("SELECT * FROM packages ORDER BY created_at DESC");
    $packages = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Failed to fetch packages: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Catering Company">
    <meta name="keywords" content="catering, event, menu, order">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>About Us | Catering Company</title>

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

<!-- About Us Hero Section Begin -->
<section class="about-us-hero set-bg" data-setbg="../assets/organi/img/about-us-hero.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="about-us-hero-text">
                    <h2>Welcome to Our Catering Service</h2>
                    <ul>
                        <li>Location: San Jose Village Extension Atulayan Sur 3500 Tuguegarao City, Philippines</li>
                        <li>Open Hours: 24/7</li>
                        <li>Contact Us: 0965 310 1013</li>
                        <li>Email Us: joymaangundan@gmail.com3</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- About Us Hero Section End -->

<!-- About Us Section Begin -->
<section class="about-us spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>About Our Company</h2>
                    <br>
                    <p>We are a premier catering company specializing in delivering delicious food for all kinds of events. Our mission is to provide high-quality food with excellent service, making your special occasions memorable.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- About Us Section End -->


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
