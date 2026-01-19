<?php
require_once '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$record_id = intval($_GET['id'] ?? 0);
$user_id = $_SESSION['user_id'];

if ($record_id <= 0) {
    header('Location: dashboard.php');
    exit;
}

$conn = getDBConnection();

// Verify user is assigned to this record
$stmt = $conn->prepare("SELECT id FROM user_assignments WHERE user_id = ? AND record_id = ?");
$stmt->bind_param("ii", $user_id, $record_id);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    header('Location: dashboard.php');
    exit;
}
$stmt->close();

// Get record details
$stmt = $conn->prepare("SELECT * FROM records WHERE id = ?");
$stmt->bind_param("i", $record_id);
$stmt->execute();
$record = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$record) {
    header('Location: dashboard.php');
    exit;
}

// Get options with response counts (only responses collected by this user)
// Formula for density table: COUNT(responses WHERE user_id = current_user) GROUP BY option_id
$options_query = "
    SELECT ro.id, ro.option_text, ro.option_order,
           COUNT(res.id) as response_count
    FROM record_options ro
    LEFT JOIN responses res ON ro.id = res.option_id AND res.user_id = ?
    WHERE ro.record_id = ?
    GROUP BY ro.id
    ORDER BY ro.option_order
";
$stmt = $conn->prepare($options_query);
$stmt->bind_param("ii", $user_id, $record_id);
$stmt->execute();
$options = $stmt->get_result();
$stmt->close();

// Get total responses collected by this user for this record
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM responses WHERE record_id = ? AND user_id = ?");
$stmt->bind_param("ii", $record_id, $user_id);
$stmt->execute();
$total_responses = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Prepare data for charts
$chart_data = [];
while ($option = $options->fetch_assoc()) {
    $chart_data[] = $option;
}
$options->data_seek(0);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Data - <?php echo htmlspecialchars($record['theme']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    <div class="main-container">
        <div class="header-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h2><i class="bi bi-bar-chart"></i> <?php echo htmlspecialchars($record['theme']); ?></h2>
                    <?php if (!empty($record['description'])): ?>
                        <p class="text-muted mb-0"><?php echo htmlspecialchars($record['description']); ?></p>
                    <?php endif; ?>
                    <p class="text-muted mt-2 mb-0">
                        <i class="bi bi-person-circle"></i> Data collected by: <?php echo htmlspecialchars($_SESSION['user_fullname']); ?>
                    </p>
                </div>
                <a href="dashboard.php" class="back-btn">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
            <div class="mt-3">
                <span class="stat-badge">
                    <i class="bi bi-check-circle"></i> Total Responses Collected: <?php echo $total_responses; ?>
                </span>
            </div>
        </div>
        
        <!-- Density Table -->
        <div class="data-card">
            <h4 class="mb-4"><i class="bi bi-table"></i> Data Summary</h4>
            <div class="table-responsive">
                <table class="table table-hover density-table">
                    <thead>
                        <tr>
                            <th>Option</th>
                            <th>Count</th>
                            <th>Percentage</th>
                            <th>Visual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $options->data_seek(0);
                        while ($option = $options->fetch_assoc()): 
                            // Formula for percentage: (response_count / total_responses) * 100
                            $percentage = $total_responses > 0 ? ($option['response_count'] / $total_responses) * 100 : 0;
                        ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($option['option_text']); ?></strong></td>
                                <td><?php echo $option['response_count']; ?></td>
                                <td><?php echo number_format($percentage, 1); ?>%</td>
                                <td>
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar progress-bar-custom" 
                                             role="progressbar" 
                                             style="width: <?php echo $percentage; ?>%"
                                             aria-valuenow="<?php echo $percentage; ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            <?php echo number_format($percentage, 1); ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if ($total_responses == 0): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">No responses collected yet. Start collecting data!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php if ($total_responses > 0): ?>
        <div class="row">
            <!-- Pie Chart -->
            <div class="col-md-6">
                <div class="data-card">
                    <h4 class="mb-3"><i class="bi bi-pie-chart"></i> Distribution Chart</h4>
                    <div class="chart-container">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Histogram -->
            <div class="col-md-6">
                <div class="data-card">
                    <h4 class="mb-3"><i class="bi bi-bar-chart-fill"></i> Response Count</h4>
                    <div class="chart-container">
                        <canvas id="histogram"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php if ($total_responses > 0): ?>
    <script>
        // Prepare data from PHP
        const chartData = <?php echo json_encode($chart_data); ?>;
        
        // Extract labels and values
        // Formula: labels = option_text array, values = response_count array
        const labels = chartData.map(item => item.option_text);
        const values = chartData.map(item => item.response_count);
        
        // Generate colors for charts
        const colors = [
            '#667eea', '#764ba2', '#f093fb', '#f5576c', 
            '#4facfe', '#00f2fe', '#43e97b', '#38f9d7',
            '#fa709a', '#fee140', '#30cfd0', '#330867',
            '#a8edea', '#fed6e3', '#ff6e7f'
        ];
        
        // Pie Chart Configuration
        // Formula for pie chart: percentage = (value / sum of all values) * 360 degrees
        // Chart.js calculates this automatically from the data values
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                // Formula: percentage = (value / total) * 100
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
        
        // Histogram (Bar Chart) Configuration
        // Formula for histogram: height of each bar = response_count
        // X-axis = categories (options), Y-axis = frequency (count)
        const histogramCtx = document.getElementById('histogram').getContext('2d');
        const histogram = new Chart(histogramCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Number of Responses',
                    data: values,
                    backgroundColor: colors,
                    borderWidth: 2,
                    borderColor: colors.map(color => color + 'dd')
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Responses: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            // Formula: Y-axis displays integer counts (stepSize = 1)
                            stepSize: 1,
                            callback: function(value) {
                                if (Number.isInteger(value)) {
                                    return value;
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Number of Responses'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Options'
                        }
                    }
                }
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>