<?php
require_once '../../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['user_type_id'] != 2) {
    die("Access denied. Admins only.");
}

try {
    $pdo = Database::getConnection();

    // Fetch only admin users (user_type_id = 2)
    $stmt = $pdo->prepare("SELECT id, name, email, user_type_id, isActive, created_at 
                           FROM users 
                           WHERE user_type_id = 2 
                           ORDER BY created_at DESC");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Cater | Admin Settings</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/fontawesome/css/all.min.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/css/style.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/css/components.css">
</head>
<body>
<div id="app">
<div class="main-wrapper main-wrapper-1">

<?php include 'includes/navbar.php'?>
<?php include 'includes/sidebar.php'?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Admin Settings</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="admin_dashboard.php">Dashboard</a></div>
              <div class="breadcrumb-item">Settings</div>
            </div>
          </div>

          <div class="section-body">

            <!-- USERS MANAGEMENT -->
            <h2 class="section-title">Manage Admin Users</h2>
            <p class="section-lead">Activate or set admin accounts to not available.</p>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Admins</h4>
                  </div>
                  <div class="card-body">
                    <?php if (empty($users)): ?>
                      <p class="text-muted">No admins found.</p>
                    <?php else: ?>
                      <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                          <thead class="thead-dark">
                            <tr>
                              <th>#</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>User Type</th>
                              <th>Status</th>
                              <th>Created At</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($users as $user): ?>
                              <tr>
                                <td><?= htmlspecialchars($user['id']) ?></td>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['user_type_id']) ?></td>
                                <td>
                                  <?php if ($user['isActive']): ?>
                                    <span class="badge badge-success">Active</span>
                                  <?php else: ?>
                                    <span class="badge badge-danger">Not Available</span>
                                  <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($user['created_at']) ?></td>
                                <td>
                                  <?php if ($user['isActive']): ?>
                                    <button class="btn btn-warning btn-sm toggle-status" data-id="<?= $user['id'] ?>" data-status="0">
                                      <i class="fas fa-ban"></i> Set Not Available
                                    </button>
                                  <?php else: ?>
                                    <button class="btn btn-success btn-sm toggle-status" data-id="<?= $user['id'] ?>" data-status="1">
                                      <i class="fas fa-check"></i> Activate
                                    </button>
                                  <?php endif; ?>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </section>
      </div>


</div>
</div>

  <!-- General JS Scripts -->
  <script src="../../assets/admin/cater-admin/assets/modules/jquery.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/popper.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/tooltip.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/moment.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/stisla.js"></script>

  <!-- Template JS File -->
  <script src="../../assets/admin/cater-admin/assets/js/scripts.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/custom.js"></script>
  
<script>
$(document).ready(function(){
  $('.toggle-status').click(function(){
    var userId = $(this).data('id');
    var newStatus = $(this).data('status');

    $.post("toggle_user_status.php", { id: userId, status: newStatus }, function(response){
      if(response.trim() === "success"){
        location.reload();
      } else {
        alert("Error: " + response);
      }
    });
  });
});
</script>

</body>
</html>
