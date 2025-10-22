<?php
session_start();
unset($_SESSION['cart'], $_SESSION['booking_id'], $_SESSION['user']);
session_destroy();
header("Location: landing_page.php");
exit();
?>
