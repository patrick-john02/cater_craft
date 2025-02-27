<?php
require_once __DIR__ . '/../models/User.php';

class UserController {
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
}

?>