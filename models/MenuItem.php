<?php
require_once __DIR__ . '/../config/database.php';

class MenuItem {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getCategories() {
        $stmt = $this->pdo->prepare("SELECT * FROM menu_categories ORDER BY category ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByCategory($category_id) {
        $stmt = $this->pdo->prepare("
            SELECT mi.*, mc.category, 
                (mi.price - (mi.price * 0.20)) AS discounted_price,
                20 AS discount
            FROM menu_items mi
            JOIN menu_categories mc ON mi.category_id = mc.id
            WHERE mi.category_id = ?
        ");
        $stmt->execute([$category_id]);
        return $stmt->fetchAll();
    }
    public function getCategoryName($category_id) {
        $stmt = $this->pdo->prepare("SELECT category FROM menu_categories WHERE id = ?");
        $stmt->execute([$category_id]);
        return $stmt->fetchColumn(); 
    }
}
?>
