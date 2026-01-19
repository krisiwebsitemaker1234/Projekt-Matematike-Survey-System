<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'u208951792_survey_system');
define('DB_PASS', 'V5kL9q@6Df^u');
define('DB_NAME', 'u208951792_survey_system');

// Create database connection
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
