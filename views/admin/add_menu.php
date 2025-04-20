<?php
require_once '../../config/database.php';
header('Content-Type: application/json');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo = Database::getConnection();
    $name = trim($_POST['menu_name'] ?? '');
    $price = trim($_POST['menu_price'] ?? '');
    $description = trim($_POST['menu_description'] ?? '');
    $category_id = trim($_POST['menu_category'] ?? '');
    $availability = 1;
    if (empty($name) || empty($price) || !is_numeric($price) || $price <= 0 || empty($category_id)) {
        echo json_encode(["status" => "error", "message" => "Invalid input values."]);
        exit();
    }
    $targetDir = "../../public/uploads/";
    $imageName = basename($_FILES["menu_image"]["name"]);
    $imagePath = $targetDir . $imageName;
    $imageFileType = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedTypes)) {
        echo json_encode(["status" => "error", "message" => "Invalid image format. Only JPG, JPEG, PNG & GIF allowed."]);
        exit();
    }
    if ($_FILES["menu_image"]["size"] > 5000000) {
        echo json_encode(["status" => "error", "message" => "Image size should not exceed 5MB."]);
        exit();
    }
    if (!move_uploaded_file($_FILES["menu_image"]["tmp_name"], $imagePath)) {
        echo json_encode(["status" => "error", "message" => "Failed to upload image."]);
        exit();
    }
    try {
        $sql = "INSERT INTO menu_items (name, description, price, image, category_id, availability) 
                VALUES (:name, :description, :price, :image, :category_id, :availability)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':image' => $imageName,
            ':category_id' => $category_id,
            ':availability' => $availability
        ]);
        echo json_encode(["status" => "success", "message" => "Menu item added successfully!"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>