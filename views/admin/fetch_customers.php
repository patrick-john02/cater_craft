<?php
require '../../config/database.php';
try {
    $pdo = Database::getConnection();
    $query = "SELECT u.id, u.name, u.email, u.phone, u.address
              FROM users u
              WHERE u.user_type_id = 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($customers);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>