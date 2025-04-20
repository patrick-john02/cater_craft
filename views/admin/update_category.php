<?php
require '../../config/database.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['category_id'];
    $name = $_POST['category_name'];

    if (!empty($name)) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE menu_categories SET category = ? WHERE id = ?");
        $stmt->execute([$name, $id]);
    }
}

header("Location: categories.php");
exit;
?>
