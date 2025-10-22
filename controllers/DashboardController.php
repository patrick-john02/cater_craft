<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/DashboardModel.php';

class DashboardController {
    private $dashboardModel;
    private $pdo;
    
    public function __construct($pdo) {
        $this->dashboardModel = new DashboardModel($pdo);
        $this->pdo = $pdo;
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
    
    public function getDishOverview() {
        return [
            'most_ordered' => $this->dashboardModel->getMostOrderedDishes(),
            'recommended' => $this->dashboardModel->getRecommendedDishes()
        ];
    }
    

    public function getDishAnalytics() {
        try {

            $stmt = $this->pdo->prepare("
                SELECT 
                    mi.name,
                    SUM(bi.quantity * mi.price) as revenue,
                    SUM(bi.quantity) as total_ordered
                FROM booking_items bi
                JOIN menu_items mi ON bi.menu_item_id = mi.id
                JOIN bookings b ON bi.booking_id = b.id
                WHERE b.status_id IN (2, 3) -- confirmed and completed bookings
                GROUP BY mi.id, mi.name
                ORDER BY revenue DESC
                LIMIT 10
            ");
            $stmt->execute();
            $dishRevenue = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Format revenue values
            foreach ($dishRevenue as &$dish) {
                $dish['revenue'] = number_format($dish['revenue'], 2);
            }

            return [
                'topDishRevenue' => !empty($dishRevenue) ? $dishRevenue[0]['revenue'] : '0.00',
                'dishRevenue' => $dishRevenue
            ];
        } catch (Exception $e) {
            return [
                'topDishRevenue' => '0.00',
                'dishRevenue' => []
            ];
        }
    }

    public function getBookingAnalytics() {
        try {

            $stmt = $this->pdo->prepare("
                SELECT 
                    CASE 
                        WHEN HOUR(event_time) BETWEEN 6 AND 11 THEN 'Morning (6AM-11AM)'
                        WHEN HOUR(event_time) BETWEEN 12 AND 17 THEN 'Afternoon (12PM-5PM)'
                        WHEN HOUR(event_time) BETWEEN 18 AND 23 THEN 'Evening (6PM-11PM)'
                        ELSE 'Late Night (12AM-5AM)'
                    END as time_slot,
                    COUNT(*) as booking_count
                FROM bookings 
                WHERE status_id IN (2, 3)
                GROUP BY time_slot
                ORDER BY booking_count DESC
            ");
            $stmt->execute();
            $popularTimes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $this->pdo->prepare("
                SELECT AVG(total_amount) as avg_order_value
                FROM bookings 
                WHERE status_id IN (2, 3)
            ");
            $stmt->execute();
            $avgOrderValue = $stmt->fetchColumn();

            $stmt = $this->pdo->prepare("
                SELECT 
                    COUNT(DISTINCT customer_id) as total_customers,
                    COUNT(DISTINCT CASE WHEN booking_count > 1 THEN customer_id END) as repeat_customers
                FROM (
                    SELECT customer_id, COUNT(*) as booking_count
                    FROM bookings 
                    WHERE status_id IN (2, 3)
                    GROUP BY customer_id
                ) as customer_bookings
            ");
            $stmt->execute();
            $customerData = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $repeatCustomersPercent = 0;
            if ($customerData['total_customers'] > 0) {
                $repeatCustomersPercent = round(($customerData['repeat_customers'] / $customerData['total_customers']) * 100, 1);
            }

            $stmt = $this->pdo->prepare("
                SELECT AVG(guests) as avg_guests
                FROM bookings 
                WHERE status_id IN (2, 3)
            ");
            $stmt->execute();
            $avgGuests = $stmt->fetchColumn();


            $stmt = $this->pdo->prepare("
                SELECT 
                    DAYNAME(event_date) as day_name,
                    COUNT(*) as booking_count
                FROM bookings 
                WHERE status_id IN (2, 3)
                GROUP BY DAYOFWEEK(event_date), DAYNAME(event_date)
                ORDER BY booking_count DESC
                LIMIT 1
            ");
            $stmt->execute();
            $peakDay = $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'popularTimes' => $popularTimes,
                'avgOrderValue' => number_format($avgOrderValue ?: 0, 2),
                'repeatCustomers' => $repeatCustomersPercent,
                'avgGuestsPerBooking' => round($avgGuests ?: 0),
                'peakBookingDay' => $peakDay ? $peakDay['day_name'] : 'N/A'
            ];

        } catch (Exception $e) {
            return [
                'popularTimes' => [],
                'avgOrderValue' => '0.00',
                'repeatCustomers' => 0,
                'avgGuestsPerBooking' => 0,
                'peakBookingDay' => 'N/A'
            ];
        }
    }
}


if (isset($_GET['salesData'])) {
    $db = Database::getConnection();
    $controller = new DashboardController($db);
    $controller->getSalesData();
}

if (isset($_GET['dishAnalytics'])) {
    $db = Database::getConnection();
    $controller = new DashboardController($db);
    header('Content-Type: application/json');
    echo json_encode($controller->getDishAnalytics());
    exit;
}

if (isset($_GET['bookingAnalytics'])) {
    $db = Database::getConnection();
    $controller = new DashboardController($db);
    header('Content-Type: application/json');
    echo json_encode($controller->getBookingAnalytics());
    exit;
}
?>