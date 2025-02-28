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

        .btn-social {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-bottom: 10px;
            border-radius: 5px;
            padding: 10px;
        }

        .btn-social img {
            width: 20px;
            margin-right: 10px;
        }

        .separator {
            text-align: center;
            font-size: 14px;
            margin: 15px 0;
            position: relative;
        }

        .separator::before,
        .separator::after {
            content: "";
            display: block;
            width: 40%;
            height: 1px;
            background: #ddd;
            position: absolute;
            top: 50%;
        }

        .separator::before {
            left: 0;
        }

        .separator::after {
            right: 0;
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
        <img src="../assets/organi/img/logo1.png"" alt="Cater-Craft Logo" class="mb-3" width="120">
            <h4 class="text-center mb-3">Login</h4>
            <?php
                session_start();
                $error = $_SESSION['error'] ?? null;
                unset($_SESSION['error']); 
            ?>

            <?php if (isset($error)) : ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="../routes.php?route=login" method="POST">
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">LOG IN</button>
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