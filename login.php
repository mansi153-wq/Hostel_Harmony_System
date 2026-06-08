<?php
session_start();
include 'includes/db.php';

// Redirect if already logged in
if (isset($_SESSION['student_id'])) {
    header("Location: student/index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM students WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($student_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                $_SESSION['student_id'] = $student_id;
                header("Location: student/index.php");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('img/e.png') no-repeat center center/cover;
        }
        .login-container {
            background: rgba(10, 10, 10, 0.85);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 350px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .login-container:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(77, 74, 74, 0.3);
        }
        h2 {
            color: #fff;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .error-msg {
            background: rgba(255,50,50,0.15);
            color: #ff6b6b;
            border: 1px solid #ff6b6b;
            border-radius: 5px;
            padding: 8px 12px;
            margin-bottom: 12px;
            font-size: 14px;
        }
        label { color: #fff; display: block; text-align: left; margin-top: 10px; }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background: rgba(15, 15, 15, 0.9);
            color: #fff;
            box-sizing: border-box;
            transition: border 0.3s, box-shadow 0.3s;
        }
        input::placeholder { color: #aaa; }
        input:focus {
            border-color: #00ff99;
            outline: none;
            box-shadow: 0 0 8px #00ff99;
        }
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(45deg, #ff6600, #ffcc00);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            margin-top: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        button:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(255, 102, 0, 0.5);
        }
        .link-container {
            margin-top: 20px;
        }
        .link-container a {
            text-decoration: none;
            color: #00ff99;
            font-weight: bold;
            transition: color 0.3s;
        }
        .link-container a:hover {
            text-shadow: 0px 0px 6px #00ff99;
            color: #ffcc00;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Student Login</h2>
        <?php if ($error): ?>
            <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required placeholder="Enter your email" autocomplete="email">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required placeholder="Enter your password" autocomplete="current-password">
            <button type="submit">Login</button>
        </form>
        <div class="link-container">
            <a href="adlogin.php">Admin Login</a>
        </div>
    </div>
</body>
</html>
