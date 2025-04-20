<?php
require_once '../../config/database.php';
$pdo = Database::getConnection();
$query = "SELECT * FROM packages";
$stmt = $pdo->query($query);
$packages = $stmt->fetchAll();
?>
