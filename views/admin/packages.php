<?php include 'get-packages.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Cater Menu</title>
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/css/style.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/css/components.css">
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-94034622-3');
  </script>
</head>
<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <?php include 'includes/navbar.php'; ?>
      <?php include 'includes/sidebar.php'; ?>
       <!-- Main Content -->
       <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Manage Packages</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="admin_dashboard.php">Dashboard</a></div>
              <div class="breadcrumb-item">Packages</div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addPackageModal">
                <i class="fas fa-plus"></i> Add Package
              </button>
            </div>
            <?php foreach ($packages as $package): ?>
              <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <article class="article">
                  <div class="article-header">
                    <div class="article-image" data-background="../../public/uploads/<?php echo $package['image'] ?? 'default.jpg'; ?>"></div>
                    <div class="article-title">
                      <h2><a href="#"><?php echo htmlspecialchars($package['name']); ?></a></h2>
                    </div>
                  </div>
                  <div class="article-details">
                    <p><?php echo htmlspecialchars($package['description']); ?></p>
                    <p><strong>Price: ₱<?php echo number_format($package['price'], 2); ?></strong></p>
                    <div class="article-cta">
                      <button
                        class="btn btn-warning btn-sm edit-btn"
                        data-toggle="modal"
                        data-target="#editPackageModal"
                        data-id="<?= $package['id'] ?>"
                        data-name="<?= htmlspecialchars($package['name']) ?>"
                        data-description="<?= htmlspecialchars($package['description']) ?>"
                        data-price="<?= $package['price'] ?>"
                      >
                        <i class="fas fa-edit"></i> Edit
                      </button>
                      <form method="POST" action="delete-package.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this package?');">
                        <input type="hidden" name="package_id" value="<?php echo $package['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">
                          <i class="fas fa-trash"></i> Delete
                        </button>
                      </form>
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
          <!-- Add Package Modal -->
          <div class="modal fade" id="addPackageModal" tabindex="-1" role="dialog" aria-labelledby="addPackageLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="addPackageLabel">Add New Package</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form method="POST" action="add-package.php" enctype="multipart/form-data">
                    <div class="form-group">
                      <label for="name">Package Name</label>
                      <input type="text" class="form-control" name="name" id="name" placeholder="Enter package name" required>
                    </div>
                    <div class="form-group">
                      <label for="description">Description</label>
                      <textarea class="form-control" name="description" id="description" rows="3" placeholder="Enter package description" required></textarea>
                    </div>
                    <div class="form-group">
                      <label for="price">Price (₱)</label>
                      <input type="number" class="form-control" name="price" id="price" placeholder="Enter package price" required>
                    </div>
                    <div class="form-group">
                      <label for="image">Upload Image</label>
                      <input type="file" class="form-control-file" name="image" id="image">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Package</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
 <!-- Edit Package Modal -->
 <div class="modal fade" id="editPackageModal" tabindex="-1" role="dialog" aria-labelledby="editPackageLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <form method="POST" action="edit-package.php" enctype="multipart/form-data" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPackageLabel">Edit Package</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit-id">
          <div class="form-group">
            <label for="edit-name">Package Name</label>
            <input type="text" class="form-control" name="name" id="edit-name">
          </div>
          <div class="form-group">
            <label for="edit-description">Description</label>
            <textarea class="form-control" name="description" id="edit-description" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label for="edit-price">Price (₱)</label>
            <input type="number" class="form-control" name="price" id="edit-price">
          </div>
          <div class="form-group">
            <label for="edit-image">Change Image</label>
            <input type="file" class="form-control-file" name="image" id="edit-image">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update Package</button>
        </div>
      </form>
    </div>
  </div>
  <script src="../../assets/admin/cater-admin/assets/modules/jquery.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/popper.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/tooltip.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/moment.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/stisla.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/scripts.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/custom.js"></script>
  <script>
    $(document).ready(function () {
      $('.edit-btn').on('click', function () {
        const button = $(this);
        $('#edit-id').val(button.data('id'));
        $('#edit-name').val(button.data('name'));
        $('#edit-description').val(button.data('description'));
        $('#edit-price').val(button.data('price'));
      });
    });
  </script>
</body>
</html>