<?php
session_start();
include '../includes/db.php';

// Enforce admin authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../adlogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $complaint_id = intval($_POST['complaint_id']);
    $status       = $_POST['status'];

    // Whitelist-validate status
    if (!in_array($status, ['pending', 'resolved'])) {
        $_SESSION['error'] = 'Invalid status value.';
        header("Location: view_complaints2.php");
        exit();
    }

    $stmt = $conn->prepare("UPDATE complaints SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $complaint_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Complaint status updated successfully.';
    } else {
        $_SESSION['error'] = 'Failed to update complaint status.';
    }
    $stmt->close();
} else {
    $_SESSION['error'] = 'Invalid request.';
}

header("Location: view_complaints2.php");
exit();
?>
