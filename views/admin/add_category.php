<?php
require '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['category_name'];

    if (!empty($name)) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO menu_categories (category) VALUES (?)");
        $stmt->execute([$name]); 
    }
}

header("Location: categories.php");
exit;
?>
