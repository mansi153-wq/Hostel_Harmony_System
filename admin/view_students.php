<?php
session_start();
include '../includes/db.php';

// Enforce admin authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../adlogin.php");
    exit();
}

// Fetch all students with their room info and payment status
$sql = "SELECT s.id, s.name, s.email, s.room_number,
               r.type AS room_type, r.fee,
               COALESCE(p.status, 'unpaid') AS payment_status
        FROM students s
        LEFT JOIN rooms r    ON s.room_number = r.room_number
        LEFT JOIN payments p ON s.id = p.student_id
        ORDER BY s.name ASC";

$students = $conn->query($sql);

if (!$students) {
    die("Error fetching students: " . htmlspecialchars($conn->error));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; color: #333; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #4CAF50; color: white; }
        tr:hover { background-color: #f1f1f1; }
        .btn-pay {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-pay:hover { background-color: #45a049; }
        .alert {
            padding: 10px 14px;
            border-radius: 5px;
            margin: 15px 0;
            text-align: center;
        }
        .alert.success { background: #eafaf1; color: #1e8449; border: 1px solid #27ae60; }
        .alert.error   { background: #fdecea; color: #c0392b; border: 1px solid #e74c3c; }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registered Students</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">Payment status updated successfully!</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert error">Error: <?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Room Number</th>
                <th>Room Type</th>
                <th>Room Fee</th>
                <th>Payment Status</th>
                <th>Action</th>
            </tr>
            <?php if ($students->num_rows > 0): ?>
                <?php while ($student = $students->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                    <td><?php echo htmlspecialchars($student['room_number'] ?? 'Not assigned'); ?></td>
                    <td><?php echo htmlspecialchars($student['room_type'] ?? 'N/A'); ?></td>
                    <td><?php echo isset($student['fee']) ? '₹' . number_format($student['fee'], 2) : 'N/A'; ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($student['payment_status'])); ?></td>
                    <td>
                        <?php if ($student['payment_status'] == 'unpaid'): ?>
                            <form method="POST" action="update_payment_status.php" style="display:inline;">
                                <input type="hidden" name="student_id" value="<?php echo (int)$student['id']; ?>">
                                <button type="submit" class="btn-pay">Mark as Paid</button>
                            </form>
                        <?php else: ?>
                            <span style="color:#27ae60;font-weight:bold;">✓ Paid</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" style="text-align:center;">No students registered yet.</td></tr>
            <?php endif; ?>
        </table>

        <a href="ind.php">Back to Admin Panel</a>
    </div>
</body>
</html>
