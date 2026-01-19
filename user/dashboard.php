<?php
require_once '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$conn = getDBConnection();

// Get records assigned to this user
$stmt = $conn->prepare("
    SELECT r.*, 
           COUNT(DISTINCT res.id) as my_responses
    FROM records r
    INNER JOIN user_assignments ua ON r.id = ua.record_id
    LEFT JOIN responses res ON r.id = res.record_id AND res.user_id = ?
    WHERE ua.user_id = ?
    GROUP BY r.id
    ORDER BY r.created_at DESC
");
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$records = $stmt->get_result();
$stmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    <div class="main-container">
        <div class="header-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-person-circle"></i> Welcome, <?php echo htmlspecialchars($_SESSION['user_fullname']); ?>!</h2>
                    <p class="text-muted mb-0">Survey Data Collector</p>
                </div>
                <a href="../logout.php" class="btn logout-btn">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
        
        <h4 class="text-white mb-4"><i class="bi bi-clipboard-data"></i> Your Assigned Records</h4>
        
        <?php if ($records->num_rows > 0): ?>
            <?php while ($record = $records->fetch_assoc()): ?>
                <div class="record-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="record-title"><?php echo htmlspecialchars($record['theme']); ?></div>
                            <?php if (!empty($record['description'])): ?>
                                <p class="text-muted mb-2"><?php echo htmlspecialchars($record['description']); ?></p>
                            <?php endif; ?>
                            <div class="mt-3">
                                <span class="badge bg-info me-2">
                                    <i class="bi bi-list-ul"></i> <?php echo $record['num_options']; ?> Options
                                </span>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> <?php echo $record['my_responses']; ?> Responses Collected
                                </span>
                            </div>
                        </div>
                        <div>
                            <a href="view_my_data.php?id=<?php echo $record['id']; ?>" 
                               class="btn btn-info text-white me-2">
                                <i class="bi bi-bar-chart"></i> View Data
                            </a>
                            <button class="btn btn-collect" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#collectModal"
                                    data-record-id="<?php echo $record['id']; ?>"
                                    data-record-theme="<?php echo htmlspecialchars($record['theme']); ?>">
                                <i class="bi bi-plus-circle"></i> Collect Data
                            </button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No records assigned to you yet. Please contact your administrator.
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Data Collection Modal -->
    <div class="modal fade" id="collectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Collect Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="collectDataForm">
                    <div class="modal-body">
                        <div id="modalAlert" class="alert d-none"></div>
                        
                        <div class="mb-3">
                            <label for="respondent_name" class="form-label">Person's Name</label>
                            <input type="text" class="form-control" id="respondent_name" 
                                   placeholder="Enter the respondent's name" required>
                        </div>
                        
                        <div id="optionsContainer"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Response</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentRecordId = null;
        const collectModal = document.getElementById('collectModal');
        
        // Load options when modal opens
        collectModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            currentRecordId = button.getAttribute('data-record-id');
            const recordTheme = button.getAttribute('data-record-theme');
            
            document.getElementById('modalTitle').textContent = 'Collect Data: ' + recordTheme;
            document.getElementById('respondent_name').value = '';
            document.getElementById('modalAlert').classList.add('d-none');
            
            // Load options via AJAX
            fetch('get_options.php?record_id=' + currentRecordId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayOptions(data.options);
                    } else {
                        showAlert('Error loading options', 'danger');
                    }
                })
                .catch(error => {
                    showAlert('Error loading options', 'danger');
                });
        });
        
        function displayOptions(options) {
            const container = document.getElementById('optionsContainer');
            container.innerHTML = '<label class="form-label">Select an Option</label>';
            
            options.forEach(option => {
                const div = document.createElement('div');
                div.className = 'form-check mb-2';
                div.innerHTML = `
                    <input class="form-check-input" type="radio" name="option_id" 
                           id="option_${option.id}" value="${option.id}" required>
                    <label class="form-check-label" for="option_${option.id}">
                        ${escapeHtml(option.option_text)}
                    </label>
                `;
                container.appendChild(div);
            });
        }
        
        // Submit form
        document.getElementById('collectDataForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('record_id', currentRecordId);
            formData.append('respondent_name', document.getElementById('respondent_name').value);
            
            const selectedOption = document.querySelector('input[name="option_id"]:checked');
            if (selectedOption) {
                formData.append('option_id', selectedOption.value);
            } else {
                showAlert('Please select an option', 'warning');
                return;
            }
            
            fetch('submit_response.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Response submitted successfully!', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert(data.message || 'Error submitting response', 'danger');
                }
            })
            .catch(error => {
                showAlert('Error submitting response', 'danger');
            });
        });
        
        function showAlert(message, type) {
            const alert = document.getElementById('modalAlert');
            alert.className = 'alert alert-' + type;
            alert.textContent = message;
            alert.classList.remove('d-none');
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>