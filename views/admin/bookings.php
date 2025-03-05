<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Cater Bookings</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/datatables/datatables.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../assets/admin/cater-admin/assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">

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
            <h1>Booking Management</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Bookings</a></div>
              <!-- <div class="breadcrumb-item"></div> -->
            </div>
          </div>

          <div class="section-body">


            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <!-- <h4>Booking Management</h4> -->
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>                                 
                          <tr>
                            <th class="text-center">#</th>
                            <th>Customer Name</th>
                            <th>Booking Date</th>
                            <th>Event Type</th>
                            <th>Guests</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>                                 
                          <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>2025-03-10</td>
                            <td>Wedding</td>
                            <td>150</td>
                            <td><div class="badge badge-success">Confirmed</div></td>
                            <td><a href="#" class="btn btn-secondary">View</a></td>
                          </tr>
                          <tr>
                            <td>2</td>
                            <td>Jane Smith</td>
                            <td>2025-03-15</td>
                            <td>Birthday Party</td>
                            <td>50</td>
                            <td><div class="badge badge-warning">Pending</div></td>
                            <td><a href="#" class="btn btn-secondary">View</a></td>
                          </tr>
                          <tr>
                            <td>3</td>
                            <td>Michael Johnson</td>
                            <td>2025-04-05</td>
                            <td>Corporate Event</td>
                            <td>200</td>
                            <td><div class="badge badge-info">Ongoing</div></td>
                            <td><a href="#" class="btn btn-secondary">View</a></td>
                          </tr>
                          <tr>
                            <td>4</td>
                            <td>Emily Brown</td>
                            <td>2025-04-20</td>
                            <td>Anniversary</td>
                            <td>80</td>
                            <td><div class="badge badge-danger">Cancelled</div></td>
                            <td><a href="#" class="btn btn-secondary">View</a></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
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
  
  <!-- JS Libraies -->
  <script src="../../assets/admin/cater-admin/assets/modules/datatables/datatables.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
  <script src="../../assets/admin/cater-admin/assets/modules/jquery-ui/jquery-ui.min.js"></script>

  <!-- Page Specific JS File -->
  <script src="../../assets/admin/cater-admin/assets/js/page/modules-datatables.js"></script>
  
  <!-- Template JS File -->
  <script src="../../assets/admin/cater-admin/assets/js/scripts.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/custom.js"></script>
</body>
</html>