<?php
session_start();
include '../includes/db.php';

$student_id = $_SESSION['student_id'];
$stmt = $conn->prepare("SELECT name FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->bind_result($name);
$stmt->fetch();
$stmt->close();

// Get the current hour
$hour = date("H");

if ($hour < 12) {
    $greeting = "Good Morning";
} elseif ($hour < 18) {
    $greeting = "Good Afternoon";
} else {
    $greeting = "Good Evening";
}

// Output the greeting with the student's name
echo "$greeting, " . htmlspecialchars($name) . "!";
?>
