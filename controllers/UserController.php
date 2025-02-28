<?php
require_once __DIR__ . '/../models/User.php';

class UserController {
    private $db;

    private $userModel;


    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    public function getAdminEmail() {
        return $this->userModel->fetchAdminEmail();
    }
    public function getPhoneNumber(){
        return $this->userModel->fetchAdminPhoneNumber();
    }
    public function register() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['first_name'] . ' ' . $_POST['last_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            if ($this->userModel->register($name, $email, $password, $phone, $address)) {
                echo "Registration Successful!";
            } else {
                echo "Registration Failed!";
            }
        }
    }
}

?>