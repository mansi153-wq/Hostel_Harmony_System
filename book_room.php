<?php
session_start();

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
            echo "<p>Room booked and payment processed successfully!</p>";
        } else {
            throw new Exception("Room is already booked.");
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }

}

// Fetch available rooms
$available_rooms = $conn->query("SELECT * FROM rooms WHERE status = 'available'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Room</title>
    <link rel="stylesheet" type="text/css" href="../css/styleS.css">
</head>
<body>
    <h2>Book a Room</h2>
    <?php if ($current_room): ?>
        <p>Your current room: <?php echo $current_room; ?></p>
        <p class="error">You can only book one room at a time.</p>
    <?php else: ?>
        <p>You have not booked a room yet.</p>
        <h3>Available Rooms</h3>
        
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
    <?php endif; ?>
    
    <br>
    <a href="index.php">Back to Dashboard</a>
</body>
</html>
