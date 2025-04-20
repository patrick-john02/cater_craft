<?php
require_once '../../config/database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['package_id'])) {
    $packageId = intval($_POST['package_id']);
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("SELECT image FROM packages WHERE id = ?");
    $stmt->execute([$packageId]);
    $package = $stmt->fetch();
    if ($package) {
        $imagePath = __DIR__ . "/../../public/uploads/" . $package['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $stmt = $pdo->prepare("DELETE FROM packages WHERE id = ?");
        $stmt->execute([$packageId]);
        header("Location: packages.php?deleted=1");
        exit();
    }
}
header("Location: packages.php?error=1");
exit();