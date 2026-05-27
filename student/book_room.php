<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    // Redirect or handle the case where the student is not logged in
}

include '../includes/db.php';

$student_id = $_SESSION['student_id'];

// Fetch student's current room (if any)
$stmt = $conn->prepare("SELECT room_number FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->bind_result($current_room);
$stmt->fetch();
$stmt->close();

// Handle room booking
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST['room_id'];
    $payment_method = $_POST['payment_method'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Check if the room is available
        $stmt = $conn->prepare("SELECT status, fee FROM rooms WHERE id = ?");
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        $stmt->bind_result($status, $fee);
        $stmt->fetch();
        $stmt->close();

        if ($status == 'available') {
            // Book the room
            $stmt = $conn->prepare("UPDATE rooms SET status = 'booked' WHERE id = ?");
            $stmt->bind_param("i", $room_id);
            $stmt->execute();
            $stmt->close();

            // Assign the room to the student
            $stmt = $conn->prepare("UPDATE students SET room_number = (SELECT room_number FROM rooms WHERE id = ?) WHERE id = ?");
            $stmt->bind_param("ii", $room_id, $student_id);
            $stmt->execute();
            $stmt->close();

            // Record payment
            $payment_stmt = $conn->prepare("INSERT INTO payments (student_id, amount, payment_method, status) VALUES (?, ?, ?, 'completed')");
            $payment_stmt->bind_param("ids", $student_id, $fee, $payment_method);
            $payment_stmt->execute();
            $payment_stmt->close();

            $conn->commit();
            echo "<p class='success'>Room booked and payment processed successfully!</p>";
        } else {
            throw new Exception("Room is already booked.");
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
    }
}

// Fetch available rooms
$available_rooms = $conn->query("SELECT * FROM rooms WHERE status = 'available'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Room</title>
    <style>
       body {
    font-family: 'Arial', sans-serif;
    background-image: url('room.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    margin: 0;
    padding: 20px;
    color: #333;
}
        p{
            color:white;
            text-align:center;
            font-size:1.9em;
        }

        h2 {
            color:rgb(234, 235, 183);
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 20px;
            animation: fadeIn 2s ease-in-out;
        }

        h3 {
            color: white;
            font-size: 3.5em;
            margin-top: 30px;
            animation: slideIn 1.5s ease-in-out;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: fadeIn 2s ease-in-out;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color:rgb(41, 82, 42);
            color: white;
            font-size: 1.2em;
        }

        tr:nth-child(even) {
            background-color:rgb(206, 195, 179);
        }

        tr:hover {
            background-color:rgb(202, 196, 169);
            transform: scale(1.02);
            transition: transform 0.3s ease;
        }

        button {
            background-color:rgb(21, 32, 22);
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 1em;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        .error {
            color: red;
            font-weight: bold;
            text-align: center;
            animation: shake 0.5s ease-in-out;
        }

        .success {
            color: green;
            font-weight: bold;
            text-align: center;
            animation: fadeIn 2s ease-in-out;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color:rgb(255, 251, 251);
            text-decoration: none;
            font-size: 2.5em;
            transition: color 0.3s ease;
        }

        a:hover {
            color:rgb(86, 126, 88);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }

        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            50% { transform: translateX(10px); }
            75% { transform: translateX(-10px); }
            100% { transform: translateX(0); }
        }

        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: slideIn 1.5s ease-in-out;
        }

        select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        select:focus {
            border-color:rgb(71, 102, 72);
            outline: none;
        }
    </style>
</head>
<body>
    <h2>Book a Room</h2>
    <?php if ($current_room): ?>
        <p>Your current room: <?php echo $current_room; ?></p>
        <p class="error">You can only book one room at a time.</p>
    <?php else: ?>
        <p>You have not booked a room yet.</p>
        <h3>Available Rooms</h3>
        
        <div class="form-container">
            <table>
                <tr>
                    <th>Room Number</th>
                    <th>Type</th>
                    <th>Fee (per semester)</th>
                    <th>Action</th>
                </tr>

                <?php while ($room = $available_rooms->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $room['room_number']; ?></td>
                        <td><?php echo ucfirst($room['type']); ?></td>
                        <td>₹<?php echo number_format($room['fee'], 2); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                                <select name="payment_method" required>
                                    <option value="cash">Cash</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                </select>
                                <button type="submit">Book & Pay</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    <?php endif; ?>
    
    <br>
    <a href="index.php">Back to Dashboard</a>
</body>
</html>