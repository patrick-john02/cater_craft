<?php
require_once '../../config/database.php';
$pdo = Database::getConnection();
$query = "SELECT id, category FROM menu_categories";
$stmt = $pdo->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll();
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_menu'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $query = "UPDATE menu_items SET name = ?, description = ?, price = ?, updated_at = NOW() WHERE id = ?";
    $stmt = $pdo->prepare($query);
    if ($stmt->execute([$name, $description, $price, $id])) {
        header("Location: menu.php?success=2");
        exit();
    } else {
        header("Location: menu.php?error=1");
        exit();
    }
}
$query = "SELECT id, name, description, price, image FROM menu_items WHERE availability = 1";
if (!empty($selectedCategory)) {
    $query .= " AND category_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$selectedCategory]);
} else {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
}
$menuItems = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Cater | Menu</title>
    <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/css/components.css">
    <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <?php include 'includes/navbar.php'?>
        <?php include 'includes/sidebar.php'?>

        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>Menu</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="menu.php">Menu</a></div>
                    </div>
                </div>

                <?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
                    <script>
                        $(document).ready(function() {
                            toastr.success('Menu item updated successfully!', 'Success', {
                                closeButton: true,
                                progressBar: true,
                                timeOut: 3000
                            });
                        });
                    </script>
                <?php endif; ?>

                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addPackageModal">
                            <i class="fas fa-plus"></i> Add Menu
                        </button>
                        <form method="GET" class="form-inline mb-3">
                        <label for="categoryFilter" class="mr-2 font-weight-bold">Filter Category:</label>
                        <select name="category" id="categoryFilter" class="form-control mr-2">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id']; ?>" <?= $selectedCategory == $category['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['category']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                    </div>

                    <?php foreach ($menuItems as $row): ?>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                            <article class="article">
                                <div class="article-header">
                                    <div class="article-image" style="background-image: url('../../public/uploads/<?php echo htmlspecialchars($row['image']); ?>');"></div>
                                    <div class="article-title">
                                        <h2><a href="#"><?php echo htmlspecialchars($row['name']); ?></a></h2>
                                    </div>
                                </div>
                                <div class="article-details">
                                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                                    <p><strong>₱<?php echo number_format($row['price'], 2); ?></strong></p>
                                    <div class="article-cta">
                                        <a href="#" class="btn btn-primary update-menu-btn" 
                                           data-toggle="modal" data-target="#updateMenuModal"
                                           data-id="<?= $row['id']; ?>" 
                                           data-name="<?= htmlspecialchars($row['name']); ?>" 
                                           data-price="<?= $row['price']; ?>" 
                                           data-description="<?= htmlspecialchars($row['description']); ?>">
                                           Update
                                        </a>
                                        <button class="btn btn-danger delete-menu-btn" 
                                                data-id="<?php echo $row['id']; ?>">
                                            Delete
                                        </button>

                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $(".delete-menu-btn").on("click", function() {
        let menuId = $(this).data("id");

        if (confirm("Are you sure you want to delete this menu item?")) {
            $.ajax({
                url: "delete_menu.php",
                type: "POST",
                data: { id: menuId },
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        toastr.success(response.message, "Success");
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        toastr.error(response.message, "Error");
                    }
                },
                error: function() {
                    toastr.error("An error occurred while deleting the menu item.", "Error");
                }
            });
        }
    });
});
</script>

  <!-- Add Menu Modal -->
<div class="modal fade" id="addPackageModal" tabindex="-1" role="dialog" aria-labelledby="addPackageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPackageModalLabel">Add New Menu Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addMenuForm" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="form-group">
                    <label for="menu_name">Menu Name</label>
                    <input type="text" class="form-control" id="menu_name" name="menu_name" required>
                </div>
                <div class="form-group">
                    <label for="menu_price">Price</label>
                    <input type="number" class="form-control" id="menu_price" name="menu_price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="menu_description">Description</label>
                    <textarea class="form-control" id="menu_description" name="menu_description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="menu_category">Category</label>
                    <select class="form-control" id="menu_category" name="menu_category" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id']; ?>"><?= htmlspecialchars($category['category']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="menu_image">Menu Image</label>
                    <input type="file" class="form-control" id="menu_image" name="menu_image" required accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Menu</button>
        </div>
        </form>

<script>
$(document).ready(function() {
    $("#addMenuForm").submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "add_menu.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    toastr.success(response.message, "Success", {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 3000
                    });
                    $("#addPackageModal").modal("hide");
                    $("#addMenuForm")[0].reset();

                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message, "Error");
                }
            },
            error: function() {
                toastr.error("An error occurred while adding the menu.", "Error");
            }
        });
    });
});
</script>
        </div>
    </div>
</div>

<!-- Update Menu Modal -->
<div class="modal fade" id="updateMenuModal" tabindex="-1" role="dialog" aria-labelledby="updateMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updateMenuModalLabel">Update Menu Item</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="updateMenuForm" method="POST" action="update_menu.php" enctype="multipart/form-data">
                <div class="modal-body p-3">
                    <input type="hidden" id="menu_id" name="id">

                    <div class="form-group">
                        <label for="update_menu_name" class="font-weight-bold">Menu Name</label>
                        <input type="text" class="form-control border rounded" id="update_menu_name" name="name" >
                    </div>

                    <!-- <div class="form-group">
    <label for="update_menu_category">Category</label>
    <select class="form-control" id="update_menu_category" name="menu_category">
        <option value="">Select Category</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['id']; ?>"><?= htmlspecialchars($category['category']); ?></option>
        <?php endforeach; ?>
    </select>
</div> -->


                    <div class="form-group">
                        <label for="update_menu_price" class="font-weight-bold">Price</label>
                        <input type="number" class="form-control border rounded" id="update_menu_price" name="price" step="0.01" >
                    </div>

                    <div class="form-group">
                        <label for="update_menu_description" class="font-weight-bold">Description</label>
                        <textarea class="form-control border rounded" id="update_menu_description" name="description" rows="3"></textarea>
                    </div>

                    <!-- <div class="form-group">
                        <label for="update_menu_image" class="font-weight-bold">Upload Image</label>
                        <input type="file" class="form-control-file" id="update_menu_image" name="image" accept="image/*">
                        <small class="text-muted">Only JPG, JPEG, PNG, GIF formats allowed.</small>
                    <div id="currentImagePreview"></div>
                    </div> -->
                </div>

                <div class="modal-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="update_menu" class="btn btn-primary">Update Menu</button>
                </div>
            </form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
    $('.update-menu-btn').on('click', function() {
        $('#menu_id').val($(this).data('id'));
        $('#update_menu_name').val($(this).data('name'));
        $('#update_menu_price').val($(this).data('price'));
        $('#update_menu_description').val($(this).data('description'));



        let image = $(this).data('image');
        if (image) {
            $('#currentImagePreview').html(`<img src="../../public/uploads/${image}" class="img-fluid mt-2" width="100">`);
        } else {
            $('#currentImagePreview').html('');
        }
    });
});
</script>
</div>
</div>
</div>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/popper.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/tooltip.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/moment.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/stisla.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/scripts.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/custom.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>
</html>