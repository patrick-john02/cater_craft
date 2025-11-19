<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/AdminManageBooking.php';

if (isset($_GET['id'])) {
    $bookingModel = new ManageBooking();
    $result = $bookingModel->updateBookingStatus($_GET['id'], 'confirmed');
    
    if ($result) {
        header('Location: bookings.php?success=Booking confirmed successfully');
    } else {
        header('Location: bookings.php?error=Failed to confirm booking');
    }
} else {
    header('Location: bookings.php');
}
exit;
?>