<?php
class DashboardModel {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function getTotalCustomers() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS total FROM users WHERE user_type_id = 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public function getTotalBookings() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS total FROM bookings");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getPendingBookings() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS total FROM bookings WHERE status_id = 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public function getCompletedBookings() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS total FROM bookings WHERE status_id = 3");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public function getBookingsPerDay() {
        $sql = "SELECT 
                    DAYOFWEEK(event_date) AS day_index,
                    DAYNAME(event_date) AS day,
                    COUNT(id) AS total 
                FROM bookings 
                WHERE event_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                GROUP BY day, day_index
                ORDER BY day_index";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $weekDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        $dataMap = [];

        foreach ($results as $row) {
            $dataMap[$row['day']] = $row['total'];
        }

        $finalData = [];
        foreach ($weekDays as $day) {
            $finalData[] = ["day" => $day, "total" => $dataMap[$day] ?? 0];
        }

        return $finalData;
    }
    public function getSalesSummary() {
        $sql = [
            "today" => "SELECT COALESCE(SUM(amount), 0) AS total FROM payments WHERE DATE(created_at) = CURDATE()",
            "week" => "SELECT COALESCE(SUM(amount), 0) AS total FROM payments WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)",
            "month" => "SELECT COALESCE(SUM(amount), 0) AS total FROM payments WHERE YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE())",
            "year" => "SELECT COALESCE(SUM(amount), 0) AS total FROM payments WHERE YEAR(created_at) = YEAR(CURDATE())"

        ];
    
        $totals = [];
        foreach ($sql as $key => $query) {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $totals[$key] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        }
        return $totals;
    }
}
?>
