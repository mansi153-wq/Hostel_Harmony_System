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

// Fetch available rooms for dropdown
$available_rooms = $conn->query("SELECT room_number FROM rooms WHERE status = 'available' ORDER BY room_number");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name           = trim($_POST['name']);
    $email          = trim($_POST['email']);
    $password       = $_POST['password'];
    $room_number    = trim($_POST['room_number']);
    $payment_amount = floatval($_POST['payment_amount']);
    $payment_method = trim($_POST['payment_method']);

    if (empty($name) || empty($email) || empty($password) || empty($room_number) || empty($payment_method) || $payment_amount <= 0) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif (!in_array($payment_method, ['cash', 'credit_card', 'bank_transfer'])) {
        $error = 'Invalid payment method.';
    } else {
        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM students WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = 'Email already registered.';
        } else {
            $check->close();

            // Check room availability
            $room_check = $conn->prepare("SELECT status FROM rooms WHERE room_number = ?");
            $room_check->bind_param("s", $room_number);
            $room_check->execute();
            $room_check->store_result();

            if ($room_check->num_rows == 0) {
                $error = 'Invalid room number.';
            } else {
                $room_check->bind_result($room_status);
                $room_check->fetch();
                $room_check->close();

                if ($room_status != 'available') {
                    $error = 'Room is not available.';
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $conn->begin_transaction();

                    try {
                        // Insert student
                        $stmt = $conn->prepare("INSERT INTO students (name, email, password, room_number, payment_status) VALUES (?, ?, ?, ?, 'paid')");
                        $stmt->bind_param("ssss", $name, $email, $hashed_password, $room_number);
                        if (!$stmt->execute()) throw new Exception("Student registration failed.");
                        $student_id = $stmt->insert_id;
                        $stmt->close();

                        // Insert payment
                        $pay_stmt = $conn->prepare("INSERT INTO payments (student_id, amount, payment_method, status) VALUES (?, ?, ?, 'completed')");
                        $pay_stmt->bind_param("ids", $student_id, $payment_amount, $payment_method);
                        if (!$pay_stmt->execute()) throw new Exception("Payment processing failed.");
                        $pay_stmt->close();

                        // Update room status
                        $upd_stmt = $conn->prepare("UPDATE rooms SET status = 'booked' WHERE room_number = ?");
                        $upd_stmt->bind_param("s", $room_number);
                        if (!$upd_stmt->execute()) throw new Exception("Room status update failed.");
                        $upd_stmt->close();

                        $conn->commit();
                        $success = 'Student registered and payment processed successfully.';

                        // Refresh available rooms list
                        $available_rooms = $conn->query("SELECT room_number FROM rooms WHERE status = 'available' ORDER BY room_number");

                    } catch (Exception $e) {
                        $conn->rollback();
                        $error = 'Registration failed: ' . $e->getMessage();
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Student</title>
    <style>
        @keyframes gradientMove {
            0%   { background-position: 0%   50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0%   50%; }
        }
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(-45deg, #667eea, #764ba2, rgb(43,40,41), rgb(131,125,127));
            background-size: 400% 400%;
            animation: gradientMove 10s ease infinite;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .logo-container img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            border: 2px solid white;
        }
        .logo-container h1 { color: white; font-size: 28px; font-weight: bold; margin: 0; }
        .wrapper {
            display: flex;
            align-items: stretch;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.3);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
        }
        .image-side {
            flex: 1;
            background: url('reg.png') no-repeat center center;
            background-size: cover;
            min-height: 500px;
        }
        .container { flex: 1; padding: 30px; text-align: center; color: #fff; }
        h2 { color: #fff; margin-bottom: 15px; font-size: 26px; }
        .msg {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-size: 15px;
        }
        .msg.error   { background: rgba(255,77,77,0.85); color: white; }
        .msg.success { background: rgba(102,255,153,0.85); color: #003; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td { padding: 10px; text-align: left; font-size: 15px; }
        input, select {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 15px;
            outline: none;
            background: rgba(255,255,255,0.9);
            box-sizing: border-box;
            transition: 0.3s;
        }
        input:focus, select:focus {
            background: rgba(255,255,255,1);
            box-shadow: 0 0 10px rgba(255,255,255,0.8);
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #ff7eb3;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }
        button:hover { background-color: #ff4d94; transform: scale(1.05); }
        @media (max-width: 700px) {
            .image-side { display: none; }
            .wrapper { max-width: 100%; }
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="logo.png" alt="Logo">
        <h1>Register New Student</h1>
    </div>

    <div class="wrapper">
        <div class="image-side"></div>
        <div class="container">
            <?php if ($error): ?>
                <div class="msg error"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif ($success): ?>
                <div class="msg success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="post" action="">
                <table>
                    <tr><td>Name:</td><td><input type="text" name="name" required></td></tr>
                    <tr><td>Email:</td><td><input type="email" name="email" required></td></tr>
                    <tr><td>Password:</td><td><input type="password" name="password" required placeholder="Min 8 characters"></td></tr>
                    <tr>
                        <td>Room Number:</td>
                        <td>
                            <select name="room_number" required>
                                <option value="">-- Select Room --</option>
                                <?php if ($available_rooms && $available_rooms->num_rows > 0): ?>
                                    <?php while ($room = $available_rooms->fetch_assoc()): ?>
                                        <option value="<?php echo htmlspecialchars($room['room_number']); ?>">
                                            <?php echo htmlspecialchars($room['room_number']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <option value="" disabled>No rooms available</option>
                                <?php endif; ?>
                            </select>
                        </td>
                    </tr>
                    <tr><td>Payment Amount:</td><td><input type="number" name="payment_amount" step="0.01" min="1" required></td></tr>
                    <tr>
                        <td>Payment Method:</td>
                        <td>
                            <select name="payment_method" required>
                                <option value="">-- Select Method --</option>
                                <option value="cash">Cash</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </td>
                    </tr>
                    <tr><td colspan="2"><button type="submit">Register Student</button></td></tr>
                </table>
            </form>

            <p style="margin-top:15px;"><a href="ind.php" style="color:#fff;text-decoration:underline;">Back to Admin Panel</a></p>
        </div>
    </div>
</body>
</html>
