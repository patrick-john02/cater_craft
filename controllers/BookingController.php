<?php
require_once __DIR__ . '/../models/Booking.php';

class BookingController {
    public function manageBookings() {
        $bookingModel = new Booking();
        $bookings = $bookingModel->getAllBookings();
        include __DIR__ . '/../views/admin/bookings.php';
    }
}
?>
