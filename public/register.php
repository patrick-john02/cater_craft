<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Register &mdash; Stisla</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="../assets/admin/cater-admin/dist/assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/admin/cater-admin/dist/assets/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="../assets/admin/cater-admin/dist/assets/modules/jquery-selectric/selectric.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="../assets/admin/cater-admin/dist/assets/admin/cater-admin/dist/assets/css/style.css">
  <link rel="stylesheet" href="../assets/admin/cater-admin/dist/assets/admin/cater-admin/dist/assets/css/components.css">
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
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
            <div class="login-brand">
              <img src="../assets/organi/img/logo1.png" alt="logo" width="100" class="shadow-light rounded-circle">
            </div>

            <div class="card card-primary">
              <div class="card-header"><h4>Register</h4></div>

              <div class="card-body">
              <form method="POST" action="landing_page.php">
            <div class="row">
                <div class="col-md-6">
                    <label>First Name</label>
                    <input type="text" class="form-control" name="first_name" required>
                </div>
                <div class="col-md-6">
                    <label>Last Name</label>
                    <input type="text" class="form-control" name="last_name" required>
                </div>
            </div>
            <div class="mt-3">
                <label>Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="col-md-6">
                    <label>Confirm Password</label>
                    <input type="password" class="form-control" name="password_confirm" required>
                </div>
            </div>
            <div class="mt-3">
                <label>Phone</label>
                <input type="text" class="form-control" name="phone">
            </div>
            <div class="mt-3">
                <label>Address</label>
                <input type="text" class="form-control" name="address">
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </form>
              </div>
            </div>
          
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="../assets/admin/cater-admin/dist/assets/admin/cater-admin/dist/assets/modules/jquery.min.js"></script>
  <script src="../assets/admin/cater-admin/dist/assets/admin/cater-admin/dist/assets/modules/popper.js"></script>
  <script src="../assets/admin/cater-admin/dist/assets/admin/cater-admin/dist/assets/modules/tooltip.js"></script>
  <script src="../assets/admin/cater-admin/dist/assets/admin/cater-admin/dist/assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="../assets/admin/cater-admin/dist/assets/admin/cater-admin/dist/assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="../assets/admin/cater-admin/dist/assets/admin/cater-admin/dist/assets/modules/moment.min.js"></script>
  <script src="../assets/admin/cater-admin/dist/assets/admin/cater-admin/dist/assets/js/stisla.js"></script>
  
  <!-- JS Libraies -->
  <script src="../assets/admin/cater-admin/dist/assets/admin/cater-admin/dist/assets/modules/jquery-pwstrength/jquery.pwstrength.min.js"></script>
  <script src="../assets/admin/cater-admin/dist/assets/admin/cater-admin/dist/assets/modules/jquery-selectric/jquery.selectric.min.js"></script>

  <!-- Page Specific JS File -->
  <script src="../assets/admin/cater-admin/dist/assets/admin/cater-admin/dist/assets/js/page/auth-register.js"></script>
  
  <!-- Template JS File -->
  <script src="../assets/admin/cater-admin/dist/assets/admin/cater-admin/dist/assets/js/scripts.js"></script>
  <script src="../assets/admin/cater-admin/dist/assets/admin/cater-admin/dist/assets/js/custom.js"></script>
</body>
</html>