<?php
require_once '../../config/database.php';
$pdo = Database::getConnection();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM menu_items WHERE id = ?";
    $stmt = $pdo->prepare($query);
    if ($stmt->execute([$id])) {
        echo json_encode(["status" => "success", "message" => "Menu item deleted successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete menu item."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
