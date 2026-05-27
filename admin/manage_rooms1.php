<?php
session_start();
include '../includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
   
    
}

$error = '';
$success = '';

// Handle room addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_room'])) {
    $room_number = trim($_POST['room_number']);
    $room_type = trim($_POST['room_type']);
    $room_fee = floatval($_POST['room_fee']);

    // Validate inputs
    if (empty($room_number) || empty($room_type) || $room_fee <= 0) {
        $error = 'All fields are required';
    } else {
        // Check if room already exists
        $stmt = $conn->prepare("SELECT id FROM rooms WHERE room_number = ?");
        $stmt->bind_param("s", $room_number);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'Room number already exists';
        } else {
            // Insert new room
            $stmt = $conn->prepare("INSERT INTO rooms (room_number, type, fee, status) VALUES (?, ?, ?, 'available')");
            $stmt->bind_param("ssd", $room_number, $room_type, $room_fee);

            if ($stmt->execute()) {
                $success = 'Room added successfully';
            } else {
                $error = 'Failed to add room';
            }
        }
        $stmt->close();
    }
}

// Fetch all rooms with their status
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
<html>
<head>
    <title>Manage Rooms</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: url('m.png') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    height: 100vh;
}
.logo-container {
    position: absolute;
    top: 20px;
    left: 20px;
    width: 160px;/* Adjust size as needed */
    height: 160px;
    border-radius: 65%;
    overflow: hidden; /* Ensures circular shape */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.logo-container img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensures the image fits nicely */
}

        .container {
            width: 40%;
            background: rgba(206, 203, 203, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            margin-right: 5%;
            backdrop-filter: blur(10px);
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        h2, h3 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: 0.3s;
        }
        input:focus, select:focus {
            border-color:rgb(0, 0, 0);
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
        }
        button {
            width: 100%;
            padding: 12px;
            background: rgb(196, 178, 21);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease-in-out;
        }
        button:hover {
            background: rgb(65, 70, 75);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background:rgb(216, 172, 27);
            color: white;
        }
        tr:nth-child(even) {
            background: rgb(250, 247, 247);
        }
        .error {
            background: rgb(255, 21, 21);
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background: rgb(224, 187, 18);
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
            text-decoration: none;
            color: rgb(0, 0, 0);
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="logo-container">
    <img src="logo.png" alt="Logo">
</div>

    <div class="container">
        <h2>Room Management</h2>

        <?php if (@$error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (@$success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <h3>Add New Room</h3>
        <form method="post" action="">
            <div class="form-group">
                <label>Room Number:</label>
                <input type="text" name="room_number" required>
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
                <input type="number" name="room_fee" step="0.01" required>
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
            <?php if (isset($rooms) && $rooms->num_rows > 0): ?>
                <?php while ($room = $rooms->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($room['room_number']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($room['type'])); ?></td>
                        <td>₹<?php echo number_format($room['fee'], 2); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($room['status'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No rooms available</td>
                </tr>
            <?php endif; ?>
        </table>

        <h3>Booked Rooms</h3>
        <table>
            <tr>
                <th>Room Number</th>
                <th>Student Name</th>
                <th>Student Email</th>
            </tr>
            <?php if (isset($booked_rooms) && $booked_rooms->num_rows > 0): ?>
                <?php while ($room = $booked_rooms->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($room['room_number']); ?></td>
                        <td><?php echo htmlspecialchars($room['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($room['email']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No booked rooms available</td>
                </tr>
            <?php endif; ?>
        </table>

        <p><a href="ind.php">Back to Admin Panel</a></p>
    </div>
</body>
</html>
