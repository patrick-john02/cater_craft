<?php
require_once '../../config/database.php';
$pdo = Database::getConnection();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_menu'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['menu_category'];
    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../../public/uploads/";
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $imageName;
        $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $image = $imageName;

                // Get the old image name
                $stmt = $pdo->prepare("SELECT image FROM menu_items WHERE id = ?");
                $stmt->execute([$id]);
                $oldImage = $stmt->fetchColumn();


                if ($oldImage && file_exists($targetDir . $oldImage)) {
                    unlink($targetDir . $oldImage);
                }
            }
        }
    }
    if ($image) {
        $query = "UPDATE menu_items SET name = ?, description = ?, price = ?, image = ?, category_id = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $success = $stmt->execute([$name, $description, $price, $image, $category_id, $id]);
    } else {
        $query = "UPDATE menu_items SET name = ?, description = ?, price = ?, category_id = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $success = $stmt->execute([$name, $description, $price, $category_id, $id]);
    }

    if ($success) {
        header("Location: menu.php?success=2");
    } else {
        header("Location: menu.php?error=1");
    }
    exit();
}
?>