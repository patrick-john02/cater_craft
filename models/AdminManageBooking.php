<?php
require_once __DIR__ . '/../config/database.php';

class ManageBooking {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    // 1. Get all bookings (including incomplete relationships)
    public function getAllBookings() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    b.id,
                    b.created_at,
                    b.event_date,
                    b.event_time,
                    b.guests,
                    b.venue,
                    b.special_requests,
                    b.total_amount,
                    u.name AS customer_name,
                    u.email AS customer_email,
                    u.phone AS customer_phone,
                    bs.status
                FROM bookings b
                LEFT JOIN users u ON b.customer_id = u.id
                LEFT JOIN booking_statuses bs ON b.status_id = bs.id
                ORDER BY b.event_date DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching all bookings: " . $e->getMessage());
            return [];
        }
    }

    // 2. Get bookings based on filters
    public function getFilteredBookings($statusFilter = '', $dateFrom = '', $dateTo = '', $searchTerm = '') {
        try {
            $sql = "
                SELECT 
                    b.id,
                    b.created_at,
                    b.event_date,
                    b.event_time,
                    b.guests,
                    b.venue,
                    b.special_requests,
                    b.total_amount,
                    u.name AS customer_name,
                    u.email AS customer_email,
                    u.phone AS customer_phone,
                    bs.status
                FROM bookings b
                LEFT JOIN users u ON b.customer_id = u.id
                LEFT JOIN booking_statuses bs ON b.status_id = bs.id
                WHERE 1=1
            ";

            $params = [];

            if (!empty($statusFilter)) {
                $sql .= " AND b.status_id = ?";
                $params[] = $statusFilter;
            }

            if (!empty($dateFrom)) {
                $sql .= " AND b.event_date >= ?";
                $params[] = $dateFrom;
            }

            if (!empty($dateTo)) {
                $sql .= " AND b.event_date <= ?";
                $params[] = $dateTo;
            }

            if (!empty($searchTerm)) {
                $sql .= " AND (
                    u.name LIKE ? OR 
                    b.venue LIKE ? OR 
                    u.email LIKE ? OR 
                    u.phone LIKE ?
                )";
                $searchParam = '%' . $searchTerm . '%';
                $params = array_merge($params, array_fill(0, 4, $searchParam));
            }

            $sql .= " ORDER BY b.event_date DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching filtered bookings: " . $e->getMessage());
            return [];
        }
    }

    // 3. Get list of all booking statuses
    public function getAllStatuses() {
        try {
            $stmt = $this->pdo->query("SELECT id, status FROM booking_statuses ORDER BY id");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching statuses: " . $e->getMessage());
            return [];
        }
    }

    // 4. Single booking with details
    public function getBookingById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    b.*, 
                    u.name AS customer_name,
                    u.email AS customer_email,
                    u.phone AS customer_phone,
                    u.address AS customer_address,
                    bs.status
                FROM bookings b
                LEFT JOIN users u ON b.customer_id = u.id
                LEFT JOIN booking_statuses bs ON b.status_id = bs.id
                WHERE b.id = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching booking by ID: " . $e->getMessage());
            return null;
        }
    }

    // 5. Booking items (for menu)
    public function getBookingItems($bookingId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    bi.*, 
                    mi.name, 
                    mi.price, 
                    mi.description, 
                    mi.image_url
                FROM booking_items bi
                LEFT JOIN menu_items mi ON bi.menu_item_id = mi.id
                WHERE bi.booking_id = ?
            ");
            $stmt->execute([$bookingId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching booking items: " . $e->getMessage());
            return [];
        }
    }

    // 6. Update status
    public function updateBookingStatus($bookingId, $statusId) {
        try {
            $stmt = $this->pdo->prepare("UPDATE bookings SET status_id = ? WHERE id = ?");
            return $stmt->execute([$statusId, $bookingId]);
        } catch (PDOException $e) {
            error_log("Error updating booking status: " . $e->getMessage());
            return false;
        }
    }

    // 7. Booking statistics
    public function getBookingStats() {
        try {
            $stats = [];

            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM bookings");
            $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            $stmt = $this->pdo->query("
                SELECT bs.status, COUNT(*) as count 
                FROM bookings b 
                LEFT JOIN booking_statuses bs ON b.status_id = bs.id 
                GROUP BY bs.status
            ");
            $stats['by_status'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $this->pdo->query("
                SELECT COUNT(*) as count 
                FROM bookings 
                WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
                AND YEAR(created_at) = YEAR(CURRENT_DATE())
            ");
            $stats['this_month'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

            $stmt = $this->pdo->query("SELECT SUM(total_amount) as total_revenue FROM bookings");
            $stats['total_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_revenue'] ?? 0;

            return $stats;
        } catch (PDOException $e) {
            error_log("Error fetching booking stats: " . $e->getMessage());
            return [];
        }
    }

    // 8. Package Bookings (optional)
    public function getAllPackageBookings() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    po.id, 
                    u.name AS customer_name, 
                    p.name AS package_name, 
                    po.ordered_at AS booking_date, 
                    po.status 
                FROM package_orders po
                JOIN users u ON po.user_id = u.id
                JOIN packages p ON po.package_id = p.id
                ORDER BY po.ordered_at DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching package bookings: " . $e->getMessage());
            return [];
        }
    }

    public function rejectBooking($bookingId) {
        try {
            $stmt = $this->pdo->prepare("UPDATE package_orders SET status = 'cancelled' WHERE id = ?");
            return $stmt->execute([$bookingId]);
        } catch (PDOException $e) {
            error_log("Error rejecting booking: " . $e->getMessage());
            return false;
        }
    }
}
