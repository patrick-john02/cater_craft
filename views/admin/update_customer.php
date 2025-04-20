<?php
require '../../config/database.php';
header('Content-Type: application/json');
try {
    $data = json_decode(file_get_contents('php://input'), true);
    error_log(print_r($data, true)); 
    if (empty($data['id']) || empty($data['name']) || empty($data['email']) || empty($data['phone']) || empty($data['address'])) {
        echo json_encode(['error' => 'All fields are required.']);
        exit;
    }
    $pdo = Database::getConnection();
    $query = "UPDATE users SET name = :name, email = :email, phone = :phone, address = :address WHERE id = :id AND user_type_id = 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
    $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
    $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
    $stmt->bindParam(':phone', $data['phone'], PDO::PARAM_STR);
    $stmt->bindParam(':address', $data['address'], PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'No changes were made or invalid customer ID.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
