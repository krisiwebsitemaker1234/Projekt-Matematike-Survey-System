<?php
require_once '../config.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$record_id = intval($_POST['record_id'] ?? 0);
$option_id = intval($_POST['option_id'] ?? 0);
$respondent_name = trim($_POST['respondent_name'] ?? '');
$user_id = $_SESSION['user_id'];

if ($record_id <= 0 || $option_id <= 0 || empty($respondent_name)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
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

// Verify option belongs to this record
$stmt = $conn->prepare("SELECT id FROM record_options WHERE id = ? AND record_id = ?");
$stmt->bind_param("ii", $option_id, $record_id);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid option']);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

// Insert response
$stmt = $conn->prepare("INSERT INTO responses (record_id, user_id, respondent_name, option_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iisi", $record_id, $user_id, $respondent_name, $option_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Response submitted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error submitting response']);
}

$stmt->close();
$conn->close();
?>
