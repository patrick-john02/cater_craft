<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
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
}
?>