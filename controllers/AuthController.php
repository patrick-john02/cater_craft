<?php
require_once __DIR__ . '/../models/User.php';

session_start();

class AuthController {
    public function login() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            error_log("Login form submitted");

            if (!isset($_POST['email']) || !isset($_POST['password'])) {
                error_log("Email or password not set");
                return;
            }

            $email = $_POST['email']; 
            $password = $_POST['password'];

            error_log("Email received: " . $email);

            $userModel = new User();
            $user = $userModel->login($email, $password);

            if (is_array($user)) { 
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'user_type_id' => $user['user_type_id'],
                    'user_role' => $user['user_role']
                ];
                error_log("User logged in successfully");

                // Redirect based on user type
                if ($user['user_type_id'] == 2) {
                    header("Location: ../cater-craft/views/admin/admin_dashboard.php"); // Redirect admins
                } else {
                    header("Location: ./public/landing_page.php"); // Redirect customers
                }
                exit();
            } else {
                error_log("Login failed for email: " . $email);
                $_SESSION['error'] = "Invalid email or password!";
                header("Location: ../public/login.php");
                exit();
            }
        }
    }
}
?>
