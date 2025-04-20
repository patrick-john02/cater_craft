<?php
require_once '../../config/database.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = Database::getConnection();

    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../public/uploads/';
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $uploadPath = $uploadDir . $imageName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            $image = $imageName;
        } else {
            echo "Error uploading file.";
            exit;
        }
    }
    if ($image) {
        $sql = "UPDATE packages SET name = :name, description = :description, price = :price, image = :image WHERE id = :id";
    } else {
        $sql = "UPDATE packages SET name = :name, description = :description, price = :price WHERE id = :id";
    }
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':id', $id);
        if ($image) {
            $stmt->bindParam(':image', $image);
        }
        if ($stmt->execute()) {
            header("Location: packages.php?success=1");
            exit();
        } else {
            echo "Error updating package.";
        }
    } catch (PDOException $e) {
        echo "DB Error: " . $e->getMessage();
    }
}
?>