<?php
session_start();
include '../includes/db.php';

// Enforce admin authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../adlogin.php");
    exit();
}

$error   = '';
$success = '';

// Handle room addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_room'])) {
    $room_number = trim($_POST['room_number']);
    $room_type   = trim($_POST['room_type']);
    $room_fee    = floatval($_POST['room_fee']);

    if (empty($room_number) || empty($room_type) || $room_fee <= 0) {
        $error = 'All fields are required.';
    } elseif (!in_array($room_type, ['single', 'double', 'dormitory'])) {
        $error = 'Invalid room type.';
    } else {
        $check = $conn->prepare("SELECT id FROM rooms WHERE room_number = ?");
        $check->bind_param("s", $room_number);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = 'Room number already exists.';
        } else {
            $stmt = $conn->prepare("INSERT INTO rooms (room_number, type, fee, status) VALUES (?, ?, ?, 'available')");
            $stmt->bind_param("ssd", $room_number, $room_type, $room_fee);
            if ($stmt->execute()) {
                $success = 'Room added successfully.';
            } else {
                $error = 'Failed to add room.';
            }
            $stmt->close();
        }
        $check->close();
    }
}

// Fetch all rooms
$rooms = $conn->query("SELECT * FROM rooms ORDER BY room_number");

// Fetch booked rooms with student details
$booked_rooms = $conn->query("
    SELECT r.room_number, s.name AS student_name, s.email 
    FROM rooms r
    JOIN students s ON r.room_number = s.room_number
    WHERE r.status = 'booked'
    ORDER BY r.room_number
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('../img/m.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            min-height: 100vh;
        }
        .logo-container {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            z-index: 10;
        }
        .logo-container img { width: 100%; height: 100%; object-fit: cover; }
        .container {
            width: 40%;
            background: rgba(206,203,203,0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
            margin-right: 5%;
            backdrop-filter: blur(10px);
            animation: fadeIn 1s ease-in-out;
            max-height: 90vh;
            overflow-y: auto;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(50px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        h2, h3 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            transition: 0.3s;
        }
        input:focus, select:focus {
            border-color: #000;
            box-shadow: 0 0 8px rgba(0,123,255,0.3);
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            background: rgb(196,178,21);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        button:hover { background: rgb(65,70,75); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: rgb(216,172,27); color: white; }
        tr:nth-child(even) { background: rgb(250,247,247); }
        .msg {
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 10px;
        }
        .msg.error   { background: rgb(255,21,21); color: white; }
        .msg.success { background: rgb(224,187,18); color: white; }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
            text-decoration: none;
            color: #000;
            font-weight: bold;
        }
        a:hover { text-decoration: underline; }
        @media (max-width: 900px) {
            .container { width: 90%; margin: 20px auto; }
            .logo-container { position: static; margin: 20px auto 0; display: block; }
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="logo.png" alt="Logo">
    </div>

    <div class="container">
        <h2>Room Management</h2>

        <?php if ($error): ?>
            <div class="msg error"><?php echo htmlspecialchars($error); ?></div>
        <?php elseif ($success): ?>
            <div class="msg success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <h3>Add New Room</h3>
        <form method="post" action="">
            <div class="form-group">
                <label>Room Number:</label>
                <input type="text" name="room_number" required maxlength="10">
            </div>
            <div class="form-group">
                <label>Room Type:</label>
                <select name="room_type" required>
                    <option value="single">Single</option>
                    <option value="double">Double</option>
                    <option value="dormitory">Dormitory</option>
                </select>
            </div>
            <div class="form-group">
                <label>Room Fee (per semester):</label>
                <input type="number" name="room_fee" step="0.01" min="1" required>
            </div>
            <button type="submit" name="add_room">Add Room</button>
        </form>

        <h3>All Rooms</h3>
        <table>
            <tr>
                <th>Room Number</th>
                <th>Type</th>
                <th>Fee</th>
                <th>Status</th>
            </tr>
            <?php if ($rooms && $rooms->num_rows > 0): ?>
                <?php while ($room = $rooms->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($room['room_number']); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($room['type'])); ?></td>
                    <td>₹<?php echo number_format($room['fee'], 2); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($room['status'])); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">No rooms available.</td></tr>
            <?php endif; ?>
        </table>

        <h3>Booked Rooms</h3>
        <table>
            <tr>
                <th>Room Number</th>
                <th>Student Name</th>
                <th>Student Email</th>
            </tr>
            <?php if ($booked_rooms && $booked_rooms->num_rows > 0): ?>
                <?php while ($room = $booked_rooms->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($room['room_number']); ?></td>
                    <td><?php echo htmlspecialchars($room['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($room['email']); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3">No booked rooms.</td></tr>
            <?php endif; ?>
        </table>

        <a href="ind.php">Back to Admin Panel</a>
    </div>
</body>
</html>
