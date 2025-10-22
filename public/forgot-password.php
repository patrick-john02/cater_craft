<?php
session_start();
require_once __DIR__ . '/../config/database.php';
// require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/../../PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../../PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../../PHPMailer-master/src/SMTP.php';




use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$pdo = Database::getConnection();

$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);

// Handle POST submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];


    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['error'] = "Email not found.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Generate token and save it
    $token = bin2hex(random_bytes(32));
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
    $stmt->execute([$email, $hashedToken]);

    // Email reset link
    $resetLink = "http://localhost/cater-craft/public/reset_password.php?token=$token";
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ityourboiaki@gmail.com';
        $mail->Password = 'wsmx fsgs fwpi jgnx';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender/Receiver
        $mail->setFrom('ityourboiaki@gmail.com', 'Cater-Craft');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Cater-Craft Password Reset';
        $mail->Body    = "Hello,<br><br>Click the link below to reset your Cater-Craft password:<br><br>
                          <a href='$resetLink'>$resetLink</a><br><br>
                          If you didn't request this, please ignore this email.";

        $mail->send();
        $_SESSION['error'] = "Reset link has been sent to your email.";
    } catch (Exception $e) {
        $_SESSION['error'] = "Mailer Error: " . $mail->ErrorInfo;
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cater-Craft</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            background-color: #ee4d2d;
            padding: 20px;
        }

        .navbar-brand {
            color: white;
            font-size: 20px;
            font-weight: bold;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
        }

        .login-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 450px; 
            min-height: 400px;
            text-align: center;
        }

        .form-control {
            border-radius: 5px;
            padding: 12px;
        }

        .btn-primary {
            background: #ee4d2d;
            border: none;
            font-size: 16px;
            font-weight: bold;
            padding: 12px;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background: #d43f1f;
        }

        .login-footer {
            margin-top: 15px;
            font-size: 14px;
        }

        .login-footer a {
            color: #ee4d2d;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .footer {
            background: #f5f5f5;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            margin-top: auto;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <a class="navbar-brand" href="login.php">Cater-Craft</a>
    </nav>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-box">
            <img src="../assets/organi/img/logo1.png" alt="Cater-Craft Logo" class="mb-3" width="120">
            <h4 class="text-center mb-3">FORGOT PASSWORD </h4>
            <span>Enter your email here</span>

            <?php if ($error) : ?>
                <div class="alert alert-danger mt-3"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3 mt-4">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Submit</button>
            </form>

            <div class="login-footer">
                <a href="#">Forgot Password?</a>
                <span>New to Cater-Craft <a href="register.php">Sign Up</a></span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; 2025 Cater-Craft. All rights reserved.
    </div>

</body>
</html>
