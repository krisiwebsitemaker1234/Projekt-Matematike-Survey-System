<?php
require_once '../config.php';

// Check if superadmin is logged in
if (!isset($_SESSION['superadmin_id'])) {
    header('Location: ../index.php');
    exit;
}

$conn = getDBConnection();

// Get statistics
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_records = $conn->query("SELECT COUNT(*) as count FROM records")->fetch_assoc()['count'];
$total_responses = $conn->query("SELECT COUNT(*) as count FROM responses")->fetch_assoc()['count'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="icon" href="https://www.iconpacks.net/icons/1/free-chart-icon-671-thumb.png">
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
             <?php include 'sidebar.php'; ?>
            
            <!-- Main Content -->
            <div class="col-md-10">
                <div class="content-area">
                    <div class="header-section">
                        <h2><i class="bi bi-speedometer2"></i> Dashboard Overview</h2>
                        <p class="text-muted mb-0">Monitor your survey system statistics</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card stat-card users">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-people"></i> Total Users</h5>
                                    <div class="stat-number"><?php echo $total_users; ?></div>
                                    <a href="manage_users.php" class="btn btn-light mt-3 action-btn">
                                        Manage Users
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card stat-card records">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-file-earmark-text"></i> Total Records</h5>
                                    <div class="stat-number"><?php echo $total_records; ?></div>
                                    <a href="manage_records.php" class="btn btn-light mt-3 action-btn">
                                        Manage Records
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card stat-card responses">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-chat-dots"></i> Total Responses</h5>
                                    <div class="stat-number"><?php echo $total_responses; ?></div>
                                    <a href="view_records.php" class="btn btn-light mt-3 action-btn">
                                        View Data
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-lightning"></i> Quick Actions</h5>
                                    <div class="d-flex gap-3 mt-3">
                                        <a href="manage_users.php?action=create" class="btn btn-primary action-btn">
                                            <i class="bi bi-person-plus"></i> Create New User
                                        </a>
                                        <a href="manage_records.php?action=create" class="btn btn-success action-btn">
                                            <i class="bi bi-plus-circle"></i> Create New Record
                                        </a>
                                        <a href="view_records.php" class="btn btn-info action-btn text-white">
                                            <i class="bi bi-eye"></i> View All Records
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
