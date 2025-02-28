<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }
       

    public function fetchAdminEmail() {
        $stmt = $this->pdo->prepare("
            SELECT email FROM users 
            WHERE user_type_id = (SELECT id FROM user_types WHERE type = 'admin') 
            LIMIT 1
        ");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function fetchAdminPhoneNumber(){
        $stmt = $this->pdo->prepare("
            SELECT phone FROM users 
            WHERE user_type_id = (SELECT id FROM user_types WHERE type = 'admin') 
            LIMIT 1
            ");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function login($email, $password) {
        error_log("Checking user in database: " . $email);
    
        $stmt = $this->pdo->prepare("
            SELECT id, name, email, password, user_type_id, account_status_id 
            FROM users 
            WHERE email = :email
        ");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();
    
        if (!$user) {
            error_log("No user found for email: " . $email);
            return false;
        }
    
        error_log("User found. Checking password...");
        error_log("Stored hashed password: " . $user['password']);
        error_log("Entered password: " . $password);
        error_log("password_verify result: " . (password_verify($password, $user['password']) ? 'TRUE' : 'FALSE'));
    
        if (!password_verify($password, $user['password'])) {
            error_log("Password does not match for email: " . $email);
            return false;
        }
    
        if ($user['account_status_id'] != 1) {
            error_log("Account is inactive for email: " . $email);
            return "Account is inactive. Contact support.";
        }
    
        error_log("User authenticated successfully!");
        return $user;
    }
    
    
    
    public function register($name, $email, $password, $phone, $address) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, phone, address, user_type_id) VALUES (?, ?, ?, ?, ?, 2)");
        return $stmt->execute([$name, $email, $hashedPassword, $phone, $address]);
    
    }
}
?>