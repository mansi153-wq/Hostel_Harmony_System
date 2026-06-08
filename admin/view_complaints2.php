<?php
session_start();
include '../includes/db.php';

// Enforce admin authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../adlogin.php");
    exit();
}

$sql = "SELECT complaints.id, students.name, complaints.complaint, complaints.status, complaints.created_at 
        FROM complaints 
        JOIN students ON complaints.student_id = students.id
        ORDER BY complaints.created_at DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching complaints: " . htmlspecialchars($conn->error));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Complaints</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: white;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            min-height: 100vh;
            overflow: hidden;
        }
        .side-image {
            position: absolute;
            left: 0;
            width: 45%;
            height: 100vh;
            background: url('../img/com.png') no-repeat center center/cover;
        }
        .container {
            width: 45%;
            max-width: 900px;
            background: rgba(170, 176, 184, 0.1);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
            margin-right: 5%;
            transform: translateX(100%);
            animation: slideIn 0.8s ease-out forwards;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }
        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
        }
        .logo-container img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid black;
            object-fit: cover;
        }
        h2 {
            font-size: 28px;
            color: black;
            font-weight: 600;
            opacity: 0;
            animation: fadeIn 1s ease-in forwards;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .alert {
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 12px;
            font-size: 14px;
        }
        .alert.success { background: #eafaf1; color: #1e8449; border: 1px solid #27ae60; }
        .alert.error   { background: #fdecea; color: #c0392b; border: 1px solid #e74c3c; }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            opacity: 0;
            animation: fadeIn 1s ease-in 0.5s forwards;
        }
        th, td {
            padding: 12px 16px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            color: black;
            word-break: break-word;
        }
        th {
            background: rgba(9, 169, 190, 0.1);
            font-weight: bold;
        }
        tr:hover { background: rgba(0, 0, 0, 0.05); }
        .btn {
            padding: 10px 16px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            background: #27ae60;
            color: white;
            transition: transform 0.3s;
        }
        .btn:hover { transform: scale(1.1); background: #1e8449; }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 18px;
            background: rgb(194, 25, 61);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: transform 0.3s;
        }
        .back-link:hover { transform: scale(1.1); }
        @media (max-width: 900px) {
            .side-image { display: none; }
            .container  { width: 90%; margin: auto; }
        }
    </style>
</head>
<body>
    <div class="side-image"></div>
    <div class="container">
        <div class="logo-container">
            <img src="logo.png" alt="Logo">
        </div>
        <h2>Student Complaints</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Student Name</th>
                <th>Complaint</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo (int)$row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['complaint']); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($row['status'])); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        <?php if ($row['status'] == 'pending'): ?>
                            <form method="post" action="update_complaint_status.php" style="display:inline;">
                                <input type="hidden" name="complaint_id" value="<?php echo (int)$row['id']; ?>">
                                <button type="submit" name="status" value="resolved" class="btn">Mark Resolved</button>
                            </form>
                        <?php else: ?>
                            <span style="color:#27ae60;font-weight:bold;">✓ Resolved</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">No complaints found.</td></tr>
            <?php endif; ?>
        </table>
        <a href="ind.php" class="back-link">Back to Admin Panel</a>
    </div>
</body>
</html>
