<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Cater Menu</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/fontawesome/css/all.min.css">

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
      <h1>Manage Packages</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item">Packages</div>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addPackageModal">
          <i class="fas fa-plus"></i> Add Package
        </button>
      </div>

      <!-- Package 1 -->
      <div class="col-12 col-sm-6 col-md-6 col-lg-3">
        <article class="article">
          <div class="article-header">
            <div class="article-image" data-background="../../public/uploads/4.jpg"></div>
            <div class="article-title">
              <h2><a href="#">Grilled Chicken Meal</a></h2>
            </div>
          </div>
          <div class="article-details">
            <p>Perfectly grilled chicken served with fresh vegetables and mashed potatoes.</p>
            <p><strong>Price: $15.99</strong></p>
            <div class="article-cta">
              <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button>
              <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
            </div>
          </div>
        </article>
      </div>

      <!-- Package 2 -->
      <div class="col-12 col-sm-6 col-md-6 col-lg-3">
        <article class="article">
          <div class="article-header">
            <div class="article-image" data-background="../../public/uploads/1.jpg"></div>
            <div class="article-title">
              <h2><a href="#">Pasta Carbonara</a></h2>
            </div>
          </div>
          <div class="article-details">
            <p>Creamy carbonara pasta with bacon bits, Parmesan cheese, and rich sauce.</p>
            <p><strong>Price: $12.50</strong></p>
            <div class="article-cta">
              <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button>
              <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
            </div>
          </div>
        </article>
      </div>

      <!-- Package 3 -->
      <div class="col-12 col-sm-6 col-md-6 col-lg-3">
        <article class="article">
          <div class="article-header">
            <div class="article-image" data-background="../../public/uploads/2.jpg"></div>
            <div class="article-title">
              <h2><a href="#">Beef Steak</a></h2>
            </div>
          </div>
          <div class="article-details">
            <p>Juicy beef steak with garlic butter sauce, served with steamed veggies.</p>
            <p><strong>Price: $20.00</strong></p>
            <div class="article-cta">
              <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button>
              <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
            </div>
          </div>
        </article>
      </div>

      <!-- Package 4 -->
      <div class="col-12 col-sm-6 col-md-6 col-lg-3">
        <article class="article">
          <div class="article-header">
            <div class="article-image" data-background="../../public/uploads/3.jpg"></div>
            <div class="article-title">
              <h2><a href="#">Mango Cheesecake</a></h2>
            </div>
          </div>
          <div class="article-details">
            <p>Delicious mango cheesecake with a buttery graham crust and creamy texture.</p>
            <p><strong>Price: $8.99</strong></p>
            <div class="article-cta">
              <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button>
              <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
            </div>
          </div>
        </article>
      </div>

    </div>

    <!-- Add Package Modal -->
    <div class="modal fade" id="addPackageModal" tabindex="-1" role="dialog" aria-labelledby="addPackageLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addPackageLabel">Add New Package</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <label>Package Name</label>
                <input type="text" class="form-control" placeholder="Enter package name">
              </div>
              <div class="form-group">
                <label>Description</label>
                <textarea class="form-control" rows="3" placeholder="Enter package description"></textarea>
              </div>
              <div class="form-group">
                <label>Price ($)</label>
                <input type="number" class="form-control" placeholder="Enter package price">
              </div>
              <div class="form-group">
                <label>Upload Image</label>
                <input type="file" class="form-control">
              </div>
              <button type="submit" class="btn btn-primary">Save Package</button>
            </form>
          </div>
        </div>
      </div>
    </div>

  </section>
</div>


  <!-- General JS Scripts -->
  <script src="../../assets/admin/cater-admin/assets/modules/jquery.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/popper.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/tooltip.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/moment.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/stisla.js"></script>
  
  <!-- JS Libraies -->

  <!-- Page Specific JS File -->
  
  <!-- Template JS File -->
  <script src="../../assets/admin/cater-admin/assets/js/scripts.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/custom.js"></script>
</body>
</html>