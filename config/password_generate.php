<?php
$password = '123'; // User's password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

echo $hashedPassword;
?>