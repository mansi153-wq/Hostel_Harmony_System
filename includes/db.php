<?php
// Database configuration
// For deployment: update these values to match your hosting provider's database credentials
$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: 'hostel_management';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    // Log error securely — do not expose details to users in production
    error_log("DB Connection failed: " . $conn->connect_error);
    die("Service temporarily unavailable. Please try again later.");
}

// Set charset to prevent encoding issues
$conn->set_charset("utf8mb4");
?>
