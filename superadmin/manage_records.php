<?php
require_once '../config.php';

// Check if superadmin is logged in
if (!isset($_SESSION['superadmin_id'])) {
    header('Location: ../index.php');
    exit;
}

$conn = getDBConnection();
$message = '';
$error = '';

// Handle record creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $theme = trim($_POST['theme'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $num_options = intval($_POST['num_options'] ?? 0);
    
    if (empty($theme) || $num_options < 2) {
        $error = 'Theme is required and must have at least 2 options';
    } else {
        // Insert record
        $stmt = $conn->prepare("INSERT INTO records (theme, description, num_options) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $theme, $description, $num_options);
        
        if ($stmt->execute()) {
            $record_id = $stmt->insert_id;
            
            // Insert options
            $stmt = $conn->prepare("INSERT INTO record_options (record_id, option_text, option_order) VALUES (?, ?, ?)");
            for ($i = 1; $i <= $num_options; $i++) {
                $option_text = trim($_POST["option_$i"] ?? '');
                if (!empty($option_text)) {
                    $stmt->bind_param("isi", $record_id, $option_text, $i);
                    $stmt->execute();
                }
            }
            
            // Assign users
            if (isset($_POST['assigned_users']) && is_array($_POST['assigned_users'])) {
                $stmt = $conn->prepare("INSERT INTO user_assignments (user_id, record_id) VALUES (?, ?)");
                foreach ($_POST['assigned_users'] as $user_id) {
                    $user_id = intval($user_id);
                    $stmt->bind_param("ii", $user_id, $record_id);
                    $stmt->execute();
                }
            }
            
            $message = 'Record created successfully';
        } else {
            $error = 'Error creating record';
        }
        $stmt->close();
    }
}

// Handle record deletion
if (isset($_GET['delete'])) {
    $record_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM records WHERE id = ?");
    $stmt->bind_param("i", $record_id);
    if ($stmt->execute()) {
        $message = 'Record deleted successfully';
    } else {
        $error = 'Error deleting record';
    }
    $stmt->close();
}

// Get all records with response count
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

// Get all users for assignment
$users = $conn->query("SELECT id, username, full_name FROM users ORDER BY full_name");

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Records - Superadmin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="icon" href="https://www.iconpacks.net/icons/1/free-chart-icon-671-thumb.png">
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    <?php include 'include/navbar.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'include/sidebar.php'; ?>
            
            <div class="col-lg-10 col-md-9">
                <div class="content-area">
                    <div class="header-section">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h2><i class="bi bi-file-earmark-text"></i> Manage Records</h2>
                                <p class="mb-0">Create and manage survey records</p>
                            </div>
                            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createRecordModal">
                                <i class="bi bi-plus-circle"></i> Create New Record
                            </button>
                        </div>
                    </div>
                    
                    <?php if ($message): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="table-card">
                        <h5 class="mb-4">All Records</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Theme</th>
                                        <th>Options</th>
                                        <th>Assigned Users</th>
                                        <th>Responses</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($records->num_rows > 0): ?>
                                        <?php while ($record = $records->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $record['id']; ?></td>
                                                <td><strong><?php echo htmlspecialchars($record['theme']); ?></strong></td>
                                                <td><span class="badge bg-info"><?php echo $record['num_options']; ?> options</span></td>
                                                <td><span class="badge bg-primary"><?php echo $record['assigned_users_count']; ?> users</span></td>
                                                <td><span class="badge bg-success"><?php echo $record['response_count']; ?> responses</span></td>
                                                <td><?php echo date('M d, Y', strtotime($record['created_at'])); ?></td>
                                                <td>
                                                    <a href="view_records.php" class="btn btn-sm btn-info text-white">
                                                        <i class="bi bi-eye"></i> View
                                                    </a>
                                                    <a href="?delete=<?php echo $record['id']; ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       onclick="return confirm('Are you sure? This will delete all responses.')">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No records found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Create Record Modal -->
    <div class="modal fade" id="createRecordModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Create New Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="" id="createRecordForm">
                    <input type="hidden" name="action" value="create">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="theme" class="form-label">Theme/Title</label>
                            <input type="text" class="form-control" id="theme" name="theme" 
                                   placeholder="e.g., Favorite Books, Sports Preferences" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control" id="description" name="description" rows="2" 
                                      placeholder="Brief description of this survey"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="num_options" class="form-label">Number of Options</label>
                            <select class="form-select" id="num_options" name="num_options" required>
                                <option value="">Select...</option>
                                <?php for ($i = 2; $i <= 15; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?> options</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        
                        <div id="optionsContainer" class="mb-3"></div>
                        
                        <div class="mb-3">
                            <label class="form-label">Assign Users</label>
                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                <?php 
                                $users->data_seek(0);
                                if ($users->num_rows > 0): 
                                ?>
                                    <?php while ($user = $users->fetch_assoc()): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="assigned_users[]" 
                                                   value="<?php echo $user['id']; ?>" 
                                                   id="user_<?php echo $user['id']; ?>">
                                            <label class="form-check-label" for="user_<?php echo $user['id']; ?>">
                                                <?php echo htmlspecialchars($user['full_name']) . ' (' . htmlspecialchars($user['username']) . ')'; ?>
                                            </label>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="text-muted">No users available. Please create users first.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Create Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Generate option input fields dynamically
        document.getElementById('num_options').addEventListener('change', function() {
            const numOptions = parseInt(this.value);
            const container = document.getElementById('optionsContainer');
            container.innerHTML = '';
            
            if (numOptions > 0) {
                container.innerHTML = '<label class="form-label">Options</label>';
                for (let i = 1; i <= numOptions; i++) {
                    const div = document.createElement('div');
                    div.className = 'option-input-group';
                    div.innerHTML = `
                        <div class="input-group">
                            <span class="input-group-text">${i}</span>
                            <input type="text" class="form-control" name="option_${i}" 
                                   placeholder="Option ${i}" required>
                        </div>
                    `;
                    container.appendChild(div);
                }
            }
        });
    </script>
    <script src="script/script.js"></script>
</body>
</html>