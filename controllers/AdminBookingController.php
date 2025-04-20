<?php
require_once __DIR__ . '/../views/admin/bookings.php';


class AdminBookingController {
    public function index() {
        $bookingModel = new ManageBooking();
        $bookings = $bookingModel->getAllBookings();
    
        if (!is_array($bookings)) {
            $bookings = [];
        }
    
        require_once __DIR__ . '/../views/admin/bookings.php';
    }
    
}
?>
