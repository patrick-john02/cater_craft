<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Cater Customers</title>
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
    <?php include 'includes/navbar.php'?>
    <?php include 'includes/sidebar.php'?>
    <div class="main-content">
      <section class="section">
        <div class="section-header">
          <h1>Manage Customers</h1>
          <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="admin_dashboard.php">Dashboard</a></div>
            <div class="breadcrumb-item">Customers</div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
  <h4>Customer List</h4>
  <input type="text" id="searchInput" class="form-control w-50" placeholder="Search customers...">
</div>
              <div class="card-body">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col" hidden>#</th>
                      <th scope="col">Full Name</th>
                      <th scope="col">Email</th>
                      <th scope="col">Phone</th>
                      <th scope="col">Address</th>
                      <!-- <th scope="col">Action</th> -->
                    </tr>
                  </thead>
                  <tbody id="customerTableBody">
    
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section>
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
                <input type="text" class="form-control" id="fullName" placeholder="Enter full name">
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" id="email" placeholder="Enter email">
              </div>
              <div class="form-group">
                <label>Phone</label>
                <input type="text" class="form-control" id="phone" placeholder="Enter phone number">
              </div>
              <div class="form-group">
                <label>Address</label>
                <textarea class="form-control" rows="3" id="address" placeholder="Enter address"></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Save Customer</button>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> -->
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
 document.addEventListener('DOMContentLoaded', function () {
  let customersData = [];
  function renderTable(data) {
    const tbody = document.querySelector('#customerTableBody');
    tbody.innerHTML = '';
    data.forEach((customer, index) => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td style="display:none;">${index + 1}</td>
        <td>${customer.name || ''}</td>
        <td>${customer.email || ''}</td>
        <td>${customer.phone || ''}</td>
        <td>${customer.address || ''}</td>
      `;
      tbody.appendChild(row);
    });
  }
  fetch('fetch_customers.php')
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        alert("Error: " + data.error);
        return;
      }
      customersData = data;
      renderTable(customersData);
    })
    .catch(error => {
      console.error('Error fetching customers:', error);
    });
  document.getElementById('searchInput').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase();
    const filtered = customersData.filter(customer => {
      return (
        (customer.name || '').toLowerCase().includes(searchValue) ||
        (customer.email || '').toLowerCase().includes(searchValue) ||
        (customer.phone || '').toLowerCase().includes(searchValue) ||
        (customer.address || '').toLowerCase().includes(searchValue)
      );
    });
    renderTable(filtered);
  });
});
</script>
</body>
</html>