<?php
session_start();
session_destroy();
header("Location: ../../public/landing_page.php");
exit();
?>
