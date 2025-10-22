<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isAuthenticated = isset($_SESSION['user']);
$userName = $isAuthenticated ? $_SESSION['user']['name'] : null;
$userId = $isAuthenticated ? $_SESSION['user']['id'] : null;

if (!$isAuthenticated || !$userId) {
    header("Location: login.php");
    exit; // stop further execution
}
try {
            $pdo = Database::getConnection();

            $stmt = $pdo->prepare("
                SELECT 
                    po.id AS order_id,
                    po.quantity,
                    po.unit_price,
                    po.total_price,
                    po.status,
                    po.ordered_at,
                    p.name AS package_name,
                    p.image AS package_image
                FROM 
                    package_orders po
                JOIN 
                    packages p ON po.package_id = p.id
                WHERE 
                    po.user_id = :user_id
                ORDER BY po.ordered_at DESC
            ");

            $stmt->execute(['user_id' => $userId]);
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cater | Craft</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="../assets/organi/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="../assets/organi/css/style.css" type="text/css">

    <style>
        /* Timeline styles */
        .timeline {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            margin-bottom: 5px;
            padding: 0;
            list-style-type: none;
            position: relative;
        }
        
        .timeline li {
            text-align: center;
            position: relative;
            flex: 1;
            z-index: 2;
        }
        
        .timeline li::before {
            content: '';
            width: 20px;
            height: 20px;
            border: 2px solid #ccc;
            border-radius: 50%;
            display: block;
            margin: 0 auto 10px;
            background-color: #fff;
            position: relative;
            z-index: 2;
        }
        
        .timeline li.active::before {
            border-color: #28a745;
            background-color: #28a745;
        }
        
        /* Connection line between timeline items */
        .timeline li:not(:last-child)::after {
            content: '';
            position: absolute;
            width: calc(100% - 20px);
            height: 2px;
            background: #ccc;
            top: 10px;
            left: calc(50% + 10px);
            z-index: 1;
        }
        
        .timeline li.active:not(:last-child)::after {
            background: #28a745;
        }
        
        .timeline li.active + li.active::after {
            background: #28a745;
        }
        
        .timeline-label {
            font-size: 12px;
            font-weight: 500;
            color: #666;
        }
        
        .timeline li.active .timeline-label {
            color: #28a745;
            font-weight: 600;
        }
        
        /* Card improvements */
        .order-card {
            transition: transform 0.2s ease-in-out;
            border: 1px solid #e0e0e0;
        }
        
        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        
        .status-pending_payment { background: #fff3cd; color: #856404; }
        .status-processing { background: #d1ecf1; color: #0c5460; }
        .status-shipped { background: #d4edda; color: #155724; }
        .status-in_transit { background: #cce5ff; color: #004085; }
        .status-delivered { background: #d1e7dd; color: #0f5132; }
        .status-completed { background: #d1e7dd; color: #0f5132; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<?php include('includes/navbar.php'); ?>

<!-- Order Section -->
<section class="order-section spad">
    <div class="container">
        <div class="section-title">
            <h2>My Package Orders</h2>
        </div>

        <?php if (count($orders) === 0): ?>
            <div class="text-center py-5">
                <i class="fa fa-shopping-bag fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No Orders Yet</h4>
                <p class="text-muted">You haven't ordered any packages yet.</p>
                <a href="packages.php" class="btn btn-primary">Browse Packages</a>
            </div>
        <?php else: ?>
            <div class="row">
            <?php 
            $statusFlow = [
                'pending_payment' => 'Pending Payment',
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'in_transit' => 'In Transit',
                'delivered' => 'Delivered',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled'
            ];

            foreach ($orders as $order): 
                // Normalize DB value (lowercase + underscores)
                $orderStatus = strtolower(trim($order['status']));
                $orderStatus = str_replace(' ', '_', $orderStatus);
                $orderStatus = str_replace('-', '_', $orderStatus);

                $statusKeys = array_keys($statusFlow);
                $currentIndex = array_search($orderStatus, $statusKeys);
                $currentIndex = ($currentIndex === false) ? -1 : $currentIndex;
                
                // Handle cancelled status differently
                $isCancelled = ($orderStatus === 'cancelled');
            ?>
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card order-card h-100">
                        <?php if (!empty($order['package_image']) && file_exists("./uploads/" . $order['package_image'])): ?>
                            <img class="card-img-top" src="./uploads/<?= htmlspecialchars($order['package_image']) ?>" 
                                alt="<?= htmlspecialchars($order['package_name']) ?>" 
                                style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" 
                                 style="height: 200px;">
                                <i class="fa fa-image fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0"><?= htmlspecialchars($order['package_name']) ?></h5>
                                <span class="status-badge status-<?= $orderStatus ?>">
                                    <?= $statusFlow[$orderStatus] ?? ucfirst(str_replace('_', ' ', $orderStatus)) ?>
                                </span>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Order ID:</small><br>
                                    <strong>#<?= str_pad($order['order_id'], 6, '0', STR_PAD_LEFT) ?></strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Quantity:</small><br>
                                    <strong><?= $order['quantity'] ?></strong>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Unit Price:</small><br>
                                    <strong>₱<?= number_format($order['unit_price'], 2) ?></strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Total Amount:</small><br>
                                    <strong class="text-success">₱<?= number_format($order['total_price'], 2) ?></strong>
                                </div>
                            </div>
                            
                            
                            <div class="mb-3">
                                <small class="text-muted">
                                    Ordered: <?= date("M j, Y \a\\t g:i A", strtotime($order['ordered_at'])) ?>
                                </small>
                            </div>

                            <?php if (!$isCancelled): ?>
                            <!-- Progress Timeline -->
                            <div class="mt-3">
                                <h6 class="mb-2">Order Progress</h6>
                                <ul class="timeline">
                                    <?php 
                                    $progressStatuses = ['pending_payment', 'processing', 'shipped', 'in_transit', 'delivered', 'completed'];
                                    foreach ($progressStatuses as $i => $status): 
                                        $isActive = ($currentIndex !== -1 && $i <= $currentIndex && in_array($orderStatus, $progressStatuses));
                                    ?>
                                        <li class="<?= $isActive ? 'active' : '' ?>">
                                            <span class="timeline-label"><?= $statusFlow[$status] ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php else: ?>
                            <div class="mt-3 text-center">
                                <i class="fa fa-times-circle text-danger fa-2x"></i>
                                <p class="text-danger mt-2 mb-0"><strong>Order Cancelled</strong></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            
            <!-- Pagination could go here if needed -->
            <div class="text-center mt-4">
                <p class="text-muted">Showing <?= count($orders) ?> order(s)</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include('includes/footer.php'); ?>

<!-- Js Plugins -->
<script src="../assets/organi/js/jquery-3.3.1.min.js"></script>
<script src="../assets/organi/js/bootstrap.min.js"></script>
<script src="../assets/organi/js/jquery.nice-select.min.js"></script>
<script src="../assets/organi/js/jquery-ui.min.js"></script>
<script src="../assets/organi/js/jquery.slicknav.js"></script>
<script src="../assets/organi/js/mixitup.min.js"></script>
<script src="../assets/organi/js/owl.carousel.min.js"></script>
<script src="../assets/organi/js/main.js"></script>

<script>
$(document).ready(function() {
    // Add smooth hover effects
    $('.order-card').hover(
        function() { $(this).addClass('shadow-lg'); },
        function() { $(this).removeClass('shadow-lg'); }
    );

});
</script>
</body>
</html>