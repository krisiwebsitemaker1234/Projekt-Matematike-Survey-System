<!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-4">
                    <h4 class="mb-4"><i class="bi bi-shield-check"></i> Superadmin</h4>
                    <p class="small mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['superadmin_username']); ?></p>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="dashboard.php">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="manage_users.php">
                        <i class="bi bi-people"></i> Manage Users
                    </a>
                    <a class="nav-link" href="manage_records.php">
                        <i class="bi bi-file-earmark-text"></i> Manage Records
                    </a>
                    <a class="nav-link" href="view_records.php">
                        <i class="bi bi-bar-chart"></i> View Records
                    </a>
                    <a class="nav-link" href="../logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </nav>
            </div>