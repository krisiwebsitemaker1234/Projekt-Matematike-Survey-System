<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div style="width: 250px;" class="col-lg-2 col-md-3 sidebar p-0">
    <div class="p-4 sidebar-header">
        <h4 class="mb-2"><i class="bi bi-shield-check"></i> Superadmin</h4>
        <p class="small mb-0 text-muted">Admin Panel</p>
    </div>
    <nav class="nav flex-column sidebar-nav">
        <a class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a class="nav-link <?php echo ($current_page == 'manage_users.php') ? 'active' : ''; ?>" href="manage_users.php">
            <i class="bi bi-people"></i> Manage Users
        </a>
        <a class="nav-link <?php echo ($current_page == 'manage_records.php') ? 'active' : ''; ?>" href="manage_records.php">
            <i class="bi bi-file-earmark-text"></i> Manage Records
        </a>
        <a class="nav-link <?php echo ($current_page == 'view_records.php' || $current_page == 'view_record_details.php') ? 'active' : ''; ?>" href="view_records.php">
            <i class="bi bi-bar-chart"></i> View Records
        </a>
    </nav>
</div>