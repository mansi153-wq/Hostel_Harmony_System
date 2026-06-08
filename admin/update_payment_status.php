<?php
session_start();
include '../includes/db.php';

// Enforce admin authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../adlogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = intval($_POST['student_id']);

    if ($student_id <= 0) {
        header("Location: view_students.php?error=" . urlencode("Invalid student ID."));
        exit();
    }

    $stmt = $conn->prepare("UPDATE payments SET status = 'completed' WHERE student_id = ? AND status != 'completed'");
    $stmt->bind_param("i", $student_id);

    if ($stmt->execute()) {
        header("Location: view_students.php?success=1");
    } else {
        header("Location: view_students.php?error=" . urlencode($conn->error));
    }
    $stmt->close();
} else {
    header("Location: view_students.php");
}
exit();
?>
