<?php
require_once '../../config/database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $admin_id = 1; 
    $imageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $originalName = basename($_FILES['image']['name']);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $newName = uniqid('pkg_', true) . '.' . $extension;
        $uploadPath = $uploadDir . $newName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            $imageName = $newName;
        } else {
            die("Failed to upload image.");
        }
    }
    try {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO packages (admin_id, name, description, price, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$admin_id, $name, $description, $price, $imageName]);
        header("Location:packages.php?success=1");
        exit;
    } catch (PDOException $e) {
        die("Error inserting package: " . $e->getMessage());
    }
} else {
    header("Location: packages.php");
    exit;
}
?>