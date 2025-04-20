<?php
require_once '../config/database.php'; 
$pdo = Database::getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    try {
        $stmt = $pdo->prepare("INSERT INTO reports (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$name, $email, $message]);
        $successMessage = "Your report has been submitted successfully!";
    } catch (PDOException $e) {
        $errorMessage = "Failed to submit the report: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Catering Company">
    <meta name="keywords" content="catering, report, customer feedback">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report Issue | Catering Company</title>

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
        /* Add custom styles for centering the form */
        .report-form {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
        }

        .form-container {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px; /* Limit the width of the form */
        }

        .form-group label {
            font-weight: 600;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
<?php include('includes/navbar.php');?>

<!-- Report Hero Section Begin -->
<section class="report-hero set-bg" data-setbg="../assets/organi/img/report-hero.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="report-hero-text">
                    <h2>Report an Issue or Feedback</h2>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Report Hero Section End -->

<!-- Report Form Section Begin -->
<section class="report-form spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>We'd Love to Hear Your Feedback</h2>
                    <br>
                    <p>If you encountered any issue with our service, or you just want to give us some feedback, feel free to report it here.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="form-container">
                    <?php if (isset($successMessage)): ?>
                        <div class="alert alert-success"><?= $successMessage ?></div>
                    <?php elseif (isset($errorMessage)): ?>
                        <div class="alert alert-danger"><?= $errorMessage ?></div>
                    <?php endif; ?>

                    <form action="report.php" method="post">
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Your Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Your Message</label>
                            <textarea id="message" name="message" class="form-control" rows="6" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Report</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Report Form Section End -->

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
