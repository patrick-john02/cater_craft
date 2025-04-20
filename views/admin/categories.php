<?php
require '../../config/database.php';

$pdo = Database::getConnection();
$stmt = $pdo->query("SELECT * FROM menu_categories ORDER BY id DESC");
$categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Cater Categories</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/bootstrap/css/bootstrap.min.css">


  <!-- CSS Libraries -->

  <!-- Template CSS -->
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/css/style.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/css/components.css">
<!-- Start GA -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-94034622-3');
</script>
<!-- /END GA --></head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
    <?php include 'includes/navbar.php'?>
    <?php include 'includes/sidebar.php'?>

      <!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Manage Categories</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="admin_dashboard.php">Dashboard</a></div>
        <div class="breadcrumb-item">Categories</div>
      </div>
    </div>

    <div class="row">
      <div class="col-12 col-md-6 col-lg-12">
        <div class="card">
          <div class="card-header">
            <h4>Categories List</h4>
            <button class="btn btn-primary ml-auto" data-toggle="modal" data-target="#addCategoryModal">
              <i class="fas fa-plus"></i> Add Category
            </button>
          </div>
          <div class="card-body">
          <table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Category Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <th scope="row"><?php echo $category['id']; ?></th>
                <td><?php echo htmlspecialchars($category['category']); ?></td>
                <td>
                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $category['id']; ?>"
                            data-name="<?php echo htmlspecialchars($category['category']); ?>">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $category['id']; ?>">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
          </div>
        </div>
      </div>
    </div>
    </section>
    </div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="update_category.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="edit_category_id" name="category_id">
                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text" id="edit_category_name" name="category_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>


    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addCategoryLabel">Add New Category</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <form action="add_category.php" method="POST">
    <div class="form-group">
        <label>Category Name</label>
        <input type="text" name="category_name" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea name="category_description" class="form-control" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Save Category</button>
</form>

          </div>
        </div>
      </div>
    </div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    // Handle edit button click
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');

            document.getElementById('edit_category_id').value = id;
            document.getElementById('edit_category_name').value = name;

            $('#editCategoryModal').modal('show');
        });
    });

    // Handle delete button click
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            if (confirm("Are you sure you want to delete this category?")) {
                const id = this.getAttribute('data-id');

                fetch('delete_category.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id=' + id
                }).then(() => location.reload());
            }
        });
    });
});

</script>
  <script src="../../assets/admin/cater-admin/assets/modules/jquery.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/popper.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/tooltip.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/moment.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/stisla.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/scripts.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/custom.js"></script>
</body>
</html>