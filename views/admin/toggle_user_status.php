<?php
require_once '../../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type_id'] != 2) {
    http_response_code(403);
    echo "Access denied";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['id']);
    $status = intval($_POST['status']);

    try {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE users SET isActive = ? WHERE id = ?");
        $stmt->execute([$status, $userId]);

        echo "success";
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Database error: " . $e->getMessage();
    }
}

?>
