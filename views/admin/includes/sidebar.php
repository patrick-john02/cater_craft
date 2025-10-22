<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="admin_dashboard.php">Cater Craft</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="admin_dashboard.php">CC</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Admin Dashboard</li>
            <li class="<?= $currentPage == 'admin_dashboard.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="../../views/admin/admin_dashboard.php">
                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="menu-header">Bookings</li>
            <li class="<?= $currentPage == 'bookings.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="../../views/admin/bookings.php">
                    <i class="fas fa-calendar-check"></i> <span>Manage Bookings</span>
                </a>
            </li>
            <li class="<?= $currentPage == 'booking_packages.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="../../views/admin/booking_packages.php">
                    <i class="fas fa-calendar"></i> <span>Packages Bookings</span>
                </a>
            </li>

            <li class="menu-header">Menu Management</li>
            <li class="<?= $currentPage == 'menu.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="../../views/admin/menu.php">
                    <i class="fas fa-utensils"></i> <span>Menu Items</span>
                </a>
            </li>
            <li class="<?= $currentPage == 'categories.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="../../views/admin/categories.php">
                    <i class="fas fa-tags"></i> <span>Categories</span>
                </a>
            </li>
            <li class="<?= $currentPage == 'packages.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="packages.php">
                    <i class="fas fa-box"></i> <span>Packages</span>
                </a>
            </li>

            <li class="menu-header">Users</li>
            <li class="<?= $currentPage == 'customers.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="customers.php">
                    <i class="fas fa-users"></i> <span>Customers</span>
                </a>
            </li>

            <li class="menu-header">Reports Managements</li>
            <li class="<?= $currentPage == 'customers_reports.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="../../views/admin/customers_reports.php">
                    <i class="fas fa-exclamation-triangle"></i> <span>Customers Report</span>
                </a>
            </li>

             <li class="<?= $currentPage == 'customers_reports.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="../../views/admin/settings.php">
                    <i class="fas fa-cog"></i> <span>Settings</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
