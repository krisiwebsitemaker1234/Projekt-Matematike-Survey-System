<?php
require_once '../config.php';

// Check if superadmin is logged in
if (!isset($_SESSION['superadmin_id'])) {
    header('Location: ../index.php');
    exit;
}

$conn = getDBConnection();

// Get all records with statistics
$records = $conn->query("
    SELECT r.*, 
           COUNT(DISTINCT res.id) as response_count,
           COUNT(DISTINCT ua.user_id) as assigned_users_count
    FROM records r
    LEFT JOIN responses res ON r.id = res.record_id
    LEFT JOIN user_assignments ua ON r.id = ua.record_id
    GROUP BY r.id
    ORDER BY r.created_at DESC
");

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Records - Superadmin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="icon" href="https://www.iconpacks.net/icons/1/free-chart-icon-671-thumb.png">
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-4">
                    <h4 class="mb-4"><i class="bi bi-shield-check"></i> Superadmin</h4>
                    <p class="small mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['superadmin_username']); ?></p>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="dashboard.php">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="manage_users.php">
                        <i class="bi bi-people"></i> Manage Users
                    </a>
                    <a class="nav-link" href="manage_records.php">
                        <i class="bi bi-file-earmark-text"></i> Manage Records
                    </a>
                    <a class="nav-link active" href="view_records.php">
                        <i class="bi bi-bar-chart"></i> View Records
                    </a>
                    <a class="nav-link" href="../logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10">
                <div class="content-area">
                    <div class="header-section">
                        <h2><i class="bi bi-bar-chart"></i> View Records</h2>
                        <p class="text-muted mb-0">View detailed statistics and visualizations for each record</p>
                    </div>
                    
                    <?php if ($records->num_rows > 0): ?>
                        <?php while ($record = $records->fetch_assoc()): ?>
                            <div class="record-card">
                                <div class="record-header">
                                    <div>
                                        <h4 class="mb-1"><?php echo htmlspecialchars($record['theme']); ?></h4>
                                        <?php if (!empty($record['description'])): ?>
                                            <p class="text-muted mb-0"><?php echo htmlspecialchars($record['description']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <a href="view_record_details.php?id=<?php echo $record['id']; ?>" 
                                       class="btn btn-primary">
                                        <i class="bi bi-eye"></i> View Details
                                    </a>
                                </div>
                                
                                <div class="record-stats">
                                    <div class="stat-item">
                                        <i class="bi bi-list-ul text-primary"></i>
                                        <strong><?php echo $record['num_options']; ?></strong> Options
                                    </div>
                                    <div class="stat-item">
                                        <i class="bi bi-people text-success"></i>
                                        <strong><?php echo $record['assigned_users_count']; ?></strong> Users Assigned
                                    </div>
                                    <div class="stat-item">
                                        <i class="bi bi-chat-dots text-info"></i>
                                        <strong><?php echo $record['response_count']; ?></strong> Responses
                                    </div>
                                    <div class="stat-item">
                                        <i class="bi bi-calendar text-secondary"></i>
                                        Created: <strong><?php echo date('M d, Y', strtotime($record['created_at'])); ?></strong>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No records found. 
                            <a href="manage_records.php" class="alert-link">Create your first record</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
