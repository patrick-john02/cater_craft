<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/AdminManageBooking.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $bookingId = intval($_POST['booking_id']);
    $bookingModel = new ManageBooking();

    if ($bookingModel->rejectBooking($bookingId)) {
        header("Location: booking_packages.php?message=Booking+rejected+successfully");
    } else {
        header("Location: booking_packages.php?error=Failed+to+reject+booking");
    }
    exit;
}
?>
