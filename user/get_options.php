<?php
require_once '../config.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$record_id = intval($_GET['record_id'] ?? 0);
$user_id = $_SESSION['user_id'];

if ($record_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid record ID']);
    exit;
}

$conn = getDBConnection();

// Verify user is assigned to this record
$stmt = $conn->prepare("SELECT id FROM user_assignments WHERE user_id = ? AND record_id = ?");
$stmt->bind_param("ii", $user_id, $record_id);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

// Get options
$stmt = $conn->prepare("SELECT id, option_text, option_order FROM record_options WHERE record_id = ? ORDER BY option_order");
$stmt->bind_param("i", $record_id);
$stmt->execute();
$result = $stmt->get_result();

$options = [];
while ($row = $result->fetch_assoc()) {
    $options[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode(['success' => true, 'options' => $options]);
?>
