<?php
require_once __DIR__ . '../../config/database.php';

class MenuCategory {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCategories() {
        $stmt = $this->pdo->prepare("SELECT * FROM menu_categories ORDER BY category ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>