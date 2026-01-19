<?php
// Get current time
date_default_timezone_set('UTC'); // Set your timezone
$current_time = date('h:i A');
$current_date = date('l, F j, Y');
?>

<style>
    /* Root Variables for Light Theme */
:root {
    --primary-color: #0d2693;
    --secondary-color: #183d8c;
    --bg-color: #f8f9fa;
    --sidebar-bg: #2d3748;
    --sidebar-text: #e2e8f0;
    --card-bg: #ffffff;
    --text-color: #2d3748;
    --border-color: #e2e8f0;
    --hover-bg: #f7fafc;
    --shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Dark Theme Variables */
[data-theme="dark"] {
    --bg-color: #1a202c;
    --sidebar-bg: #0f1419;
    --sidebar-text: #e2e8f0;
    --card-bg: #2d3748;
    --text-color: #e2e8f0;
    --border-color: #4a5568;
    --hover-bg: #374151;
    --shadow: 0 2px 8px rgba(0,0,0,0.3);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: var(--bg-color);
    color: var(--text-color);
    transition: background-color 0.3s, color 0.3s;
}

/* Navbar Styles */
.navbar {
    background-color: var(--card-bg) !important;
    box-shadow: var(--shadow);
    transition: background-color 0.3s;
}

.navbar-brand {
    font-weight: 600;
    color: var(--primary-color) !important;
    font-size: 1.25rem;
}

.nav-time {
    color: var(--text-color);
    font-size: 0.9rem;
    white-space: nowrap;
}

.profile-avatar {
    font-size: 1.8rem;
    color: var(--primary-color);
}

.theme-switch {
    display: flex;
    align-items: center;
    margin: 0;
}

.theme-switch .form-check-input {
    width: 3rem;
    height: 1.5rem;
    cursor: pointer;
}

.theme-switch label {
    margin-left: 0.5rem;
    cursor: pointer;
    font-size: 1.2rem;
}

.dropdown-menu {
    background-color: var(--card-bg);
    border: 1px solid var(--border-color);
}

.dropdown-item {
    color: var(--text-color);
}

.dropdown-item:hover {
    background-color: var(--hover-bg);
}

.dropdown-item.disabled {
    color: var(--text-color);
    opacity: 0.7;
}

/* Sidebar Styles */
.sidebar {
    background: var(--sidebar-bg);
    min-height: calc(100vh - 56px);
    color: var(--sidebar-text);
    box-shadow: var(--shadow);
    transition: all 0.3s;
}

.sidebar-header {
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.sidebar h4 {
    color: var(--sidebar-text);
    font-size: 1.3rem;
}

.sidebar-nav {
    padding: 1rem 0;
}

.sidebar .nav-link {
    color: var(--sidebar-text);
    padding: 0.9rem 1.5rem;
    transition: all 0.3s;
    border-left: 3px solid transparent;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.sidebar .nav-link:hover {
    background: rgba(255,255,255,0.1);
    color: #fff;
    border-left-color: var(--primary-color);
}

.sidebar .nav-link.active {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: #fff;
    border-left-color: #fff;
    box-shadow: 0 4px 6px rgba(0,0,0,0.2);
}

.sidebar .nav-link i {
    font-size: 1.2rem;
    width: 24px;
}

/* Main Content Area */
.content-area {
    padding: 2rem;
    min-height: calc(100vh - 56px);
}

.header-section {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    color: white;
    box-shadow: var(--shadow);
}

.header-section h2 {
    color: white;
    margin-bottom: 0.5rem;
    font-size: 1.8rem;
}

/* Cards */
.card {
    border: 1px solid var(--border-color);
    border-radius: 12px;
    box-shadow: var(--shadow);
    transition: all 0.3s;
    background-color: var(--card-bg);
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
}

.stat-card {
    margin-bottom: 1.5rem;
}

.stat-card.users {
    border-top: 4px solid #667eea;
}

.stat-card.records {
    border-top: 4px solid #48bb78;
}

.stat-card.responses {
    border-top: 4px solid #ed8936;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.action-btn {
    border-radius: 8px;
    padding: 0.5rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Tables */
.table-card {
    background: var(--card-bg);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: var(--shadow);
    margin-top: 2rem;
}

.table {
    color: var(--text-color);
}

.table thead th {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    border: none;
    font-weight: 600;
    padding: 1rem;
}

.table tbody tr {
    transition: all 0.2s;
}

.table tbody tr:hover {
    background-color: var(--hover-bg);
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-color: var(--border-color);
}

/* Record Cards */
.record-card {
    background: var(--card-bg);
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: var(--shadow);
    margin-bottom: 1.5rem;
    transition: all 0.3s;
}

.record-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
}

.record-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.record-header h4 {
    color: var(--text-color);
    margin: 0;
}

.record-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.95rem;
}

.stat-item i {
    font-size: 1.3rem;
}

/* Data Cards */
.data-card {
    background: var(--card-bg);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: var(--shadow);
    margin-bottom: 2rem;
}

.data-card h4 {
    color: var(--text-color);
    margin-bottom: 1.5rem;
}

/* Charts */
.chart-container {
    position: relative;
    height: 300px;
}

/* Density Table */
.density-table .progress {
    background-color: var(--border-color);
}

.progress-bar-custom {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    font-weight: 600;
}

/* Modal */
.modal-content {
    background-color: var(--card-bg);
    color: var(--text-color);
    border: 1px solid var(--border-color);
}

.modal-header {
    border-bottom-color: var(--border-color);
}

.modal-footer {
    border-top-color: var(--border-color);
}

.form-control,
.form-select {
    background-color: var(--card-bg);
    color: var(--text-color);
    border-color: var(--border-color);
}

.form-control:focus,
.form-select:focus {
    background-color: var(--card-bg);
    color: var(--text-color);
    border-color: var(--primary-color);
}

.form-label {
    color: var(--text-color);
}

.option-input-group {
    margin-bottom: 0.75rem;
}

/* Badges */
.badge {
    padding: 0.5rem 0.75rem;
    font-weight: 500;
    border-radius: 6px;
}

/* Alerts */
.alert {
    border-radius: 8px;
    border: none;
}

/* Buttons */
.btn {
    border-radius: 8px;
    padding: 0.5rem 1.25rem;
    font-weight: 500;
    transition: all 0.3s;
}

.btn:hover {
    transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 991.98px) {
    .sidebar {
        min-height: auto;
        margin-bottom: 1rem;
    }
    
    .content-area {
        padding: 1rem;
    }
    
    .header-section {
        padding: 1.5rem;
    }
    
    .header-section h2 {
        font-size: 1.5rem;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .table-card {
        padding: 1rem;
        overflow-x: auto;
    }
    
    .record-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .chart-container {
        height: 250px;
    }
}

@media (max-width: 767.98px) {
    .content-area {
        padding: 0.75rem;
    }
    
    .header-section {
        padding: 1rem;
    }
    
    .header-section h2 {
        font-size: 1.25rem;
    }
    
    .stat-number {
        font-size: 1.75rem;
    }
    
    .data-card {
        padding: 1rem;
    }
    
    .record-stats {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .stat-item {
        font-size: 0.85rem;
    }
    
    .action-btn {
        padding: 0.4rem 1rem;
        font-size: 0.9rem;
    }
}

@media (max-width: 575.98px) {
    .navbar-brand {
        font-size: 1rem;
    }
    
    .profile-avatar {
        font-size: 1.5rem;
    }
    
    .header-section h2 {
        font-size: 1.1rem;
    }
    
    .card-title {
        font-size: 1rem;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .table {
        font-size: 0.85rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
}
</style>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">
            <i class="bi bi-shield-check"></i> Survey System
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item me-3 d-none d-lg-block">
                    <span class="nav-time">
                        <i class="bi bi-clock"></i> <?php echo $current_time; ?>
                        <span class="d-none d-xl-inline">| <?php echo $current_date; ?></span>
                    </span>
                </li>
                
                <li class="nav-item me-3">
                    <div class="form-check form-switch theme-switch">
                        <input class="form-check-input" type="checkbox" id="themeToggle">
                        <label class="form-check-label" for="themeToggle">
                            <i class="bi bi-moon-stars"></i>
                        </label>
                    </div>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" 
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar me-2">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <span class="d-none d-md-inline"><?php echo htmlspecialchars($_SESSION['superadmin_username']); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li>
                            <a class="dropdown-item disabled" href="#">
                                <i class="bi bi-person"></i> 
                                <?php echo htmlspecialchars($_SESSION['superadmin_username']); ?>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="../logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
// Theme Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('themeToggle');
    const html = document.documentElement;
    
    // Check for saved theme preference or default to light mode
    const currentTheme = localStorage.getItem('theme') || 'light';
    html.setAttribute('data-theme', currentTheme);
    
    if (currentTheme === 'dark') {
        themeToggle.checked = true;
    }
    
    // Theme toggle event
    themeToggle.addEventListener('change', function() {
        if (this.checked) {
            html.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
        } else {
            html.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
        }
    });
    
    // Update time every minute
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: true 
        });
        const dateString = now.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
        const timeElement = document.querySelector('.nav-time');
        if (timeElement) {
            timeElement.innerHTML = `<i class="bi bi-clock"></i> ${timeString} <span class="d-none d-xl-inline">| ${dateString}</span>`;
        }
    }
    
    // Update time every minute
    setInterval(updateTime, 60000);
});
</script>