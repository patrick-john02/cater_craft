<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Admin | Profile</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/bootstrap-social/bootstrap-social.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/summernote/summernote-bs4.css">

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
      <h1>Admin Profile</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item">Profile</div>
      </div>
    </div>

    <div class="row mt-sm-4">
      <div class="col-12 col-md-12 col-lg-5">
        <div class="card profile-widget">
          <div class="profile-widget-header">
           
            <div class="profile-widget-items">
              <div class="profile-widget-item">
                <div class="profile-widget-item-label">Role</div>
                <div class="profile-widget-item-value">Administrator</div>
              </div>
            </div>
          </div>
          <div class="profile-widget-description">
            <div class="profile-widget-name">Ingga's Catering 
              <div class="text-muted d-inline font-weight-normal">
                <div class="slash"></div> System Admin
              </div>
            </div>
            John is a dedicated administrator managing the Cater Craft system, ensuring smooth operations and secure transactions.
          </div>
          
        </div>
      </div>

      <div class="col-12 col-md-12 col-lg-7">
        <div class="card">
          <form method="post" class="needs-validation" novalidate="">
            <div class="card-header">
              <h4>Edit Profile</h4>
            </div>
            <div class="card-body">
              <div class="row">                               
                <div class="form-group col-md-6 col-12">
                  <label>First Name</label>
                  <input type="text" class="form-control" value="John" required="">
                </div>
                <div class="form-group col-md-6 col-12">
                  <label>Last Name</label>
                  <input type="text" class="form-control" value="Doe" required="">
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-7 col-12">
                  <label>Email</label>
                  <input type="email" class="form-control" value="admin@catercraft.com" required="">
                </div>
                <div class="form-group col-md-5 col-12">
                  <label>Phone</label>
                  <input type="tel" class="form-control" value="+1234567890">
                </div>
              </div>
              <div class="row">
                <div class="form-group col-12">
                  <label>Address</label>
                  <input type="text" class="form-control" value="123 Admin Street, City, Country">
                </div>
              </div>
             
            </div>
            <div class="card-footer text-right">
              <button class="btn btn-primary">Save Changes</button>
            </div>
          </form>
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
  <script src="../../assets/admin/cater-admin/assets/modules/summernote/summernote-bs4.js"></script>

  <!-- Page Specific JS File -->
  
  <!-- Template JS File -->
  <script src="../../assets/admin/cater-admin/assets/js/scripts.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/custom.js"></script>
</body>
</html>