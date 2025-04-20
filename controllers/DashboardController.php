<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/DashboardModel.php';
class DashboardController {
    private $dashboardModel;
    public function __construct($pdo) {
        $this->dashboardModel = new DashboardModel($pdo);
    }
    public function getDashboardStats() {
        return [
            'total_customers' => $this->dashboardModel->getTotalCustomers(),
            'total_bookings' => $this->dashboardModel->getTotalBookings(),
            'pending_bookings' => $this->dashboardModel->getPendingBookings(),
            'completed_bookings' => $this->dashboardModel->getCompletedBookings()
        ];
    }
    public function getBookingChartData() {
        $chartData = $this->dashboardModel->getBookingsPerDay();
        header('Content-Type: application/json');
        echo json_encode($chartData);
        exit;
    }
    public function getSalesData() {
        $salesData = $this->dashboardModel->getSalesSummary();
        header('Content-Type: application/json');
        echo json_encode($salesData);
        exit;
    }
}
if (isset($_GET['salesData'])) {
    $db = Database::getConnection();
    $controller = new DashboardController($db);
    $controller->getSalesData();
}
