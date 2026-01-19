<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4></i>Superadmin Panel</h4>
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

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>