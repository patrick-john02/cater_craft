<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Cater Payments</title>

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
            <h1>Invoice</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item">Payments</div>
            </div>
          </div>

          <div class="section-body">
  <div class="invoice">
    <div class="invoice-print">
      <div class="row">
        <div class="col-lg-12">
          <div class="invoice-title">
            <h2>Invoice</h2>
            <div class="invoice-number">Order #98765</div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-6">
              <address>
                <strong>Billed To:</strong><br>
                John Doe<br>
                456 Elm Street<br>
                Suite 12B<br>
                Los Angeles, CA, USA
              </address>
            </div>
            <div class="col-md-6 text-md-right">
              <address>
                <strong>Shipped To:</strong><br>
                Sarah Smith<br>
                789 Pine Avenue<br>
                Floor 5<br>
                San Francisco, CA, USA
              </address>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <address>
                <strong>Payment Method:</strong><br>
                PayPal - john.doe@email.com<br>
              </address>
            </div>
            <div class="col-md-6 text-md-right">
              <address>
                <strong>Order Date:</strong><br>
                March 5, 2025<br><br>
              </address>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-md-12">
          <div class="section-title">Order Summary</div>
          <p class="section-lead">All items here are final and cannot be deleted.</p>
          <div class="table-responsive">
            <table class="table table-striped table-hover table-md">
              <tr>
                <th data-width="40">#</th>
                <th>Item</th>
                <th class="text-center">Price</th>
                <th class="text-center">Quantity</th>
                <th class="text-right">Totals</th>
              </tr>
              <tr>
                <td>1</td>
                <td>Wedding Catering Package</td>
                <td class="text-center">$1,200.00</td>
                <td class="text-center">1</td>
                <td class="text-right">$1,200.00</td>
              </tr>
              <tr>
                <td>2</td>
                <td>Beverage Package (100 guests)</td>
                <td class="text-center">$500.00</td>
                <td class="text-center">1</td>
                <td class="text-right">$500.00</td>
              </tr>
              <tr>
                <td>3</td>
                <td>Dessert Bar</td>
                <td class="text-center">$300.00</td>
                <td class="text-center">1</td>
                <td class="text-right">$300.00</td>
              </tr>
            </table>
          </div>
          <div class="row mt-4">
            <div class="col-lg-8">
              <div class="section-title">Payment Method</div>
              <p class="section-lead">We support multiple payment methods for your convenience.</p>
              <div class="images">
                <img src="assets/img/visa.png" alt="visa">
                <img src="assets/img/mastercard.png" alt="mastercard">
                <img src="assets/img/paypal.png" alt="paypal">
              </div>
            </div>
            <div class="col-lg-4 text-right">
              <div class="invoice-detail-item">
                <div class="invoice-detail-name">Subtotal</div>
                <div class="invoice-detail-value">$2,000.00</div>
              </div>
              <div class="invoice-detail-item">
                <div class="invoice-detail-name">Tax (5%)</div>
                <div class="invoice-detail-value">$100.00</div>
              </div>
              <div class="invoice-detail-item">
                <div class="invoice-detail-name">Discount</div>
                <div class="invoice-detail-value">-$50.00</div>
              </div>
              <hr class="mt-2 mb-2">
              <div class="invoice-detail-item">
                <div class="invoice-detail-name">Total</div>
                <div class="invoice-detail-value invoice-detail-value-lg">$2,050.00</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <hr>
    <div class="text-md-right">
      <div class="float-lg-left mb-lg-0 mb-3">
        <button class="btn btn-primary btn-icon icon-left"><i class="fas fa-credit-card"></i> Process Payment</button>
        <button class="btn btn-danger btn-icon icon-left"><i class="fas fa-times"></i> Cancel</button>
      </div>
      <button class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i> Print</button>
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

  <!-- Page Specific JS File -->
  
  <!-- Template JS File -->
  <script src="../../assets/admin/cater-admin/assets/js/scripts.js"></script>
  <script src="../../assets/admin/cater-admin/assets/js/custom.js"></script>
</body>
</html>