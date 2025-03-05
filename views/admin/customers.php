<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Cater Customers</title>

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
      <h1>Manage Customers</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item">Customers</div>
      </div>
    </div>

    <div class="row">
      <div class="col-12 col-md-6 col-lg-12">
        <div class="card">
          <div class="card-header">
            <h4>Customer List</h4>
            <button class="btn btn-primary ml-auto" data-toggle="modal" data-target="#addCustomerModal">
              <i class="fas fa-plus"></i> Add Customer
            </button>
          </div>
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Full Name</th>
                  <th scope="col">Email</th>
                  <th scope="col">Phone</th>
                  <th scope="col">Address</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">1</th>
                  <td>Franklyn Dayag</td>
                  <td>johndoe@gmail.com</td>
                  <td>+63 094 567 890</td>
                  <td>23 balzain Tuguegarao City</td>
                  <td>
                    <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button>
                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">2</th>
                  <td>Leigh Pagulayan</td>
                  <td>janesmith@email.com</td>
                  <td>+63 094 567 892</td>
                  <td>11 Ugac Highway Tuguegarao City</td>
                  <td>
                    <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button>
                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">3</th>
                  <td>Jay Malillin</td>
                  <td>michaelj@email.com</td>
                  <td>+63 094 567 893</td>
                  <td>42 maggay st. Tuguegarao City</td>
                  <td>
                    <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button>
                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Customer Modal -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addCustomerLabel">Add New Customer</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <label>Full Name</label>
                <input type="text" class="form-control" placeholder="Enter full name">
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" placeholder="Enter email">
              </div>
              <div class="form-group">
                <label>Phone</label>
                <input type="text" class="form-control" placeholder="Enter phone number">
              </div>
              <div class="form-group">
                <label>Address</label>
                <textarea class="form-control" rows="3" placeholder="Enter address"></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Save Customer</button>
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