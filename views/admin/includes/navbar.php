<?php
// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../controllers/UserController.php';

$pdo = Database::getConnection();
$controller = new UserController($pdo);

$adminName = "Cater Admin"; // Default name

// Debugging: Log session data
error_log("Session Data: " . print_r($_SESSION, true));

// Ensure user is logged in and is an admin
if (isset($_SESSION['user']) && $_SESSION['user']['user_type_id'] == 2) {
    $adminName = htmlspecialchars($_SESSION['user']['name']);
} else {
    error_log("Admin session data not found!");
}
?>

<div class="main-wrapper main-wrapper-1">
  <div class="navbar-bg"></div>
  <nav class="navbar navbar-expand-lg main-navbar">
    <ul class="navbar-nav ml-auto"> 
      <li class="dropdown">
        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
          <div class="d-sm-none d-lg-inline-block">Hi, <?= $adminName; ?></div>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <!-- <a href="../../views/admin/profile.php" class="dropdown-item has-icon">
            <i class="far fa-user"></i> Profile
          </a> -->
          <div class="dropdown-divider"></div>
          <a href="./logout.php" class="dropdown-item has-icon text-danger">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </div>
      </li>
    </ul>
  </nav>
</div>