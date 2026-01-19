<?php
// Get current time
date_default_timezone_set('UTC'); // Set your timezone
$current_time = date('h:i A');
$current_date = date('l, F j, Y');
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <!-- Mobile Menu Toggle -->
        <button class="navbar-toggler d-lg-none" type="button" id="sidebarToggle">
            <i class="bi bi-list" style="font-size: 1.5rem; color: var(--primary-color);"></i>
        </button>

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
                        <i class="bi bi-clock"></i> 
                        <span id="currentTime"><?php echo $current_time; ?></span>
                        <span class="d-none d-xl-inline"> | <span id="currentDate"><?php echo $current_date; ?></span></span>
                    </span>
                </li>
                
                <li class="nav-item me-3">
                    <div class="form-check form-switch theme-switch">
                        <input class="form-check-input" type="checkbox" id="themeToggle">
                        <label class="form-check-label" for="themeToggle">
                            <i class="bi bi-moon-stars" id="themeIcon"></i>
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