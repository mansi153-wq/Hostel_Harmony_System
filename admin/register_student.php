<?php
session_start();
include '../includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $room_number = trim($_POST['room_number']);
    $payment_amount = floatval($_POST['payment_amount']);
    $payment_method = trim($_POST['payment_method']);

    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($room_number) || empty($payment_method) || $payment_amount <= 0) {
        $error = 'All fields are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'Email already registered';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Check room availability
            $room_stmt = $conn->prepare("SELECT status FROM rooms WHERE room_number = ?");
            $room_stmt->bind_param("s", $room_number);
            $room_stmt->execute();
            $room_stmt->store_result();

            if ($room_stmt->num_rows == 0) {
                $error = 'Invalid room number';
            } else {
                $room_stmt->bind_result($room_status);
                $room_stmt->fetch();

                if ($room_status != 'available') {
                    $error = 'Room is not available';
                } else {
                    // Start transaction
                    $conn->begin_transaction();

                    try {
                        // Insert student
                        $stmt = $conn->prepare("INSERT INTO students (name, email, password, room_number, payment_status) VALUES (?, ?, ?, ?, 'paid')");
                        $stmt->bind_param("ssss", $name, $email, $hashed_password, $room_number);

                        if (!$stmt->execute()) {
                            throw new Exception("Student registration failed");
                        }

                        $student_id = $stmt->insert_id;
                        $stmt->close();

                        // Insert payment
                        $payment_stmt = $conn->prepare("INSERT INTO payments (student_id, amount, payment_method, status) VALUES (?, ?, ?, 'completed')");
                        $payment_stmt->bind_param("ids", $student_id, $payment_amount, $payment_method);

                        if (!$payment_stmt->execute()) {
                            throw new Exception("Payment processing failed");
                        }
                        $payment_stmt->close();

                        // Update room status
                        $update_stmt = $conn->prepare("UPDATE rooms SET status = 'booked' WHERE room_number = ?");
                        $update_stmt->bind_param("s", $room_number);

                        if (!$update_stmt->execute()) {
                            throw new Exception("Room status update failed");
                        }
                        $update_stmt->close();

                        $conn->commit();
                        $success = 'Student registered and payment processed successfully';
                    } catch (Exception $e) {
                        $conn->rollback();
                        $error = 'Registration failed: ' . $e->getMessage();
                    }
                }
            }
            $room_stmt->close();
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
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(-45deg, #667eea, #764ba2, rgb(43, 40, 41), rgb(131, 125, 127));
            background-size: 400% 400%;
            animation: gradientMove 10s ease infinite;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            margin: 0;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            width: 80px; /* Adjust size as needed */
            height: 80px; /* Ensure it's a square */
            border-radius: 50%; /* Makes it circular */
            object-fit: cover; /* Ensures the image fits well */
            margin-right: 10px;
            border: 2px solid white; /* Optional: Adds a white border */
        }

        .logo-container h1 {
            color: white;
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }

        .wrapper {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 900px;
        }

        .image-side {
            flex: 1;
            background: url('reg.png') no-repeat center center;
            background-size: cover;
            height: 600px;
        }

        .container {
            flex: 1;
            padding: 25px;
            text-align: center;
            color: #fff;
        }

        h2 {
            color: #fff;
            margin-bottom: 15px;
            font-size: 26px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        td {
            padding: 12px;
            text-align: left;
            font-size: 16px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            background: rgba(255, 255, 255, 0.9);
            transition: 0.3s;
        }

        input:focus, select:focus {
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
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
            position: relative;
            overflow: hidden;
        }

        button::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease-in-out;
        }

        button:hover::before {
            transform: scaleX(1);
        }

        button:hover {
            background-color: #ff4d94;
            transform: scale(1.05);
        }

        .error, .success {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-size: 16px;
        }

        .error {
            background-color: #ff4d4d;
            color: white;
        }

        .success {
            background-color: #66ff99;
            color: black;
        }
    </style>
</head>
<body>

    <!-- Logo and Title -->
    <div class="logo-container">
        <img src="logo.png" alt="Logo">
        <h1>Register New Student</h1>
    </div>

    <div class="wrapper">
        <div class="image-side"></div>
        <div class="container">
            

            <?php if ($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="post">
                <table>
                    <tr><td>Name:</td><td><input type="text" name="name" required></td></tr>
                    <tr><td>Email:</td><td><input type="email" name="email" required></td></tr>
                    <tr><td>Password:</td><td><input type="password" name="password" required></td></tr>
                    <tr><td>Room Number:</td><td><select name="room_number" required></select></td></tr>
                    <tr><td>Payment Amount:</td><td><input type="number" name="payment_amount" required></td></tr>
                    <tr><td>Payment Method:</td><td><select name="payment_method" required></select></td></tr>
                    <tr><td colspan="2"><button type="submit">Register</button></td></tr>
                </table>
            </form>
        </div>
    </div>

</body>
</html>
