




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
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn-pay {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-pay:hover {
            background-color: #45a049;
        }

        .success {
            color: green;
            margin: 20px 0;
            text-align: center;
        }

        .error {
            color: red;
            margin: 20px 0;
            text-align: center;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registered Students</h2>

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

            <?php while ($student = $students->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                    <td><?php echo htmlspecialchars($student['room_number'] ?? 'Not assigned'); ?></td>
                    <td><?php echo htmlspecialchars($student['room_type'] ?? 'N/A'); ?></td>
                    <td><?php echo isset($student['fee']) ? '₹' . number_format($student['fee'], 2) : 'N/A'; ?></td>
                    <td><?php echo ucfirst(htmlspecialchars($student['payment_status'])); ?></td>
                    <td>
                        <?php if ($student['payment_status'] == 'unpaid'): ?>
                            <form method="POST" action="update_payment_status.php" style="display:inline;">
                                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['id']); ?>">
                                <button type="submit" class="btn-pay">Mark as Paid</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <?php if (isset($_GET['success'])): ?>
            <div class="success">Payment status updated successfully!</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="error">Error updating payment status: <?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        <p><a href="ind.php">Back to Admin Panel</a></p>
    </div>
</body>
</html>