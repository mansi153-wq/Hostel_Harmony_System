<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../includes/db.php';

$student_id = $_SESSION['student_id'];
$message    = '';
$msg_type   = '';

// Fetch student's current room
$stmt = $conn->prepare("SELECT room_number FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->bind_result($current_room);
$stmt->fetch();
$stmt->close();

// Handle room booking
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$current_room) {
    $room_id        = intval($_POST['room_id']);
    $payment_method = trim($_POST['payment_method']);

    if ($room_id <= 0 || !in_array($payment_method, ['cash', 'credit_card', 'bank_transfer'])) {
        $message  = "Invalid booking data.";
        $msg_type = "error";
    } else {
        $conn->begin_transaction();
        try {
            // Check if the room is still available (lock row)
            $stmt = $conn->prepare("SELECT status, fee, room_number FROM rooms WHERE id = ? FOR UPDATE");
            $stmt->bind_param("i", $room_id);
            $stmt->execute();
            $stmt->bind_result($status, $fee, $room_no);
            $stmt->fetch();
            $stmt->close();

            if ($status !== 'available') {
                throw new Exception("Room is no longer available.");
            }

            // Update room status
            $stmt = $conn->prepare("UPDATE rooms SET status = 'booked' WHERE id = ?");
            $stmt->bind_param("i", $room_id);
            $stmt->execute();
            $stmt->close();

            // Assign room to student
            $stmt = $conn->prepare("UPDATE students SET room_number = ? WHERE id = ?");
            $stmt->bind_param("si", $room_no, $student_id);
            $stmt->execute();
            $stmt->close();

            // Record payment
            $pay = $conn->prepare("INSERT INTO payments (student_id, amount, payment_method, status) VALUES (?, ?, ?, 'completed')");
            $pay->bind_param("ids", $student_id, $fee, $payment_method);
            $pay->execute();
            $pay->close();

            $conn->commit();
            $current_room = $room_no;
            $message  = "Room booked and payment processed successfully!";
            $msg_type = "success";
        } catch (Exception $e) {
            $conn->rollback();
            $message  = "Error: " . $e->getMessage();
            $msg_type = "error";
        }
    }
}

// Fetch available rooms
$available_rooms = $conn->query("SELECT * FROM rooms WHERE status = 'available' ORDER BY room_number");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('roomss.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        p { color: white; text-align: center; font-size: 1.2em; }
        h2 { color: rgb(234,235,183); text-align: center; font-size: 2.5em; margin-bottom: 20px; }
        h3 { color: white; font-size: 2em; margin-top: 30px; }
        .msg {
            text-align: center;
            font-weight: bold;
            font-size: 1.1em;
            padding: 10px;
            border-radius: 8px;
            margin: 10px 0;
        }
        .msg.error   { background: rgba(255,0,0,0.2);   color: #ffcccc; border: 1px solid #f00; }
        .msg.success { background: rgba(0,200,0,0.2);   color: #ccffcc; border: 1px solid #0c0; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        th, td { padding: 15px; text-align: left; border: 1px solid #ddd; }
        th { background-color: rgb(41,82,42); color: white; font-size: 1.1em; }
        tr:nth-child(even) { background-color: rgba(206,195,179,0.8); }
        tr:hover { background-color: rgba(202,196,169,0.9); }
        button {
            background-color: rgb(21,32,22);
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 1em;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        button:hover { background-color: #45a049; transform: scale(1.05); }
        select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 0.95em;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            color: rgb(255,251,251);
            text-decoration: none;
            font-size: 1.3em;
        }
        a:hover { color: rgb(86,126,88); }
    </style>
</head>
<body>
    <h2>Book a Room</h2>

    <?php if ($message): ?>
        <div class="msg <?php echo $msg_type; ?>"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if ($current_room): ?>
        <p>Your current room: <strong><?php echo htmlspecialchars($current_room); ?></strong></p>
        <p>You can only book one room at a time.</p>
    <?php else: ?>
        <p>You have not booked a room yet.</p>
        <h3>Available Rooms</h3>

        <?php if ($available_rooms && $available_rooms->num_rows > 0): ?>
        <table>
            <tr>
                <th>Room Number</th>
                <th>Type</th>
                <th>Fee (per semester)</th>
                <th>Action</th>
            </tr>
            <?php while ($room = $available_rooms->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($room['room_number']); ?></td>
                <td><?php echo htmlspecialchars(ucfirst($room['type'])); ?></td>
                <td>₹<?php echo number_format($room['fee'], 2); ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="room_id" value="<?php echo (int)$room['id']; ?>">
                        <select name="payment_method" required>
                            <option value="cash">Cash</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                        <button type="submit">Book &amp; Pay</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p>No rooms are currently available.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="index.php">← Back to Dashboard</a>
</body>
</html>
