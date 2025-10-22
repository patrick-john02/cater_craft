<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$pdo = Database::getConnection();
$error = null;
$success = null;

// Token from link
$token = $_GET['token'] ?? null;

if (!$token) {
    die("Invalid reset link.");
}

// Handle password reset submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'], $_POST['confirm_password'])) {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Lookup token in DB
        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE 1");
        $stmt->execute();
        $resetRows = $stmt->fetchAll();

        $found = null;
        foreach ($resetRows as $row) {
            if (password_verify($token, $row['token'])) {
                $found = $row;
                break;
            }
        }

        if (!$found) {
            $error = "Invalid or expired reset token.";
        } else {
            $email = $found['email'];

            // Update user password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->execute([$hashedPassword, $email]);

            // Delete reset token
            $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
            $stmt->execute([$email]);

            $success = "Password successfully updated. You can now <a href='login.php'>login</a>.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cater-Craft - Reset Password</title>
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
        .reset-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
        }
        .reset-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 450px; 
            min-height: 350px;
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

    <!-- Reset Container -->
    <div class="reset-container">
        <div class="reset-box">
            <img src="../assets/organi/img/logo1.png" alt="Cater-Craft Logo" class="mb-3" width="120">
            <h4 class="text-center mb-3">Reset Password</h4>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php else: ?>
            <form method="POST">
                <div class="mb-3 mt-3">
                    <input type="password" class="form-control" name="password" placeholder="New Password" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Reset Password</button>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; 2025 Cater-Craft. All rights reserved.
    </div>

</body>
</html>
