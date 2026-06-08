<?php
include 'includes/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM students WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email is already registered.";
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO students (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed);

            if ($stmt->execute()) {
                $success = "Registration successful! <a href='login.php'>Login here</a>";
            } else {
                $error = "Registration failed. Please try again.";
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f5f5f5;
        }
        .container {
            display: flex;
            width: 800px;
            height: 500px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .left {
            width: 50%;
            background: linear-gradient(135deg, #26A69A, #009688);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-bottom: 20px;
        }
        .left h2 { font-size: 24px; }
        .left p { font-size: 14px; margin-top: 10px; }
        .sign-in-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background: transparent;
            border: 2px solid white;
            color: white;
            font-size: 16px;
            border-radius: 20px;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
            display: inline-block;
        }
        .sign-in-btn:hover { background: white; color: #009688; }
        .right { width: 50%; padding: 40px; text-align: center; display: flex; flex-direction: column; justify-content: center; }
        .right h2 { color: #009688; font-size: 24px; margin-bottom: 10px; }
        .msg {
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .msg.error { background: #fdecea; color: #c0392b; border: 1px solid #e74c3c; }
        .msg.success { background: #eafaf1; color: #1e8449; border: 1px solid #27ae60; }
        .msg a { color: #009688; }
        .form-group { display: flex; flex-direction: column; gap: 10px; margin-top: 10px; }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
            background: #f9f9f9;
        }
        .sign-up-btn {
            margin-top: 15px;
            padding: 12px;
            width: 100%;
            background: #009688;
            color: white;
            border: none;
            font-size: 18px;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.3s;
        }
        .sign-up-btn:hover { background: #00796B; }
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <img src="img/logo.png" alt="Logo" class="logo">
            <h2>Welcome Back!</h2>
            <p>Already have an account? Sign in here.</p>
            <a href="login.php" class="sign-in-btn">SIGN IN</a>
        </div>
        <div class="right">
            <h2>Create Account</h2>
            <?php if ($error): ?>
                <div class="msg error"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif ($success): ?>
                <div class="msg success"><?php echo $success; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <input type="text"     name="name"     placeholder="Full Name"    required>
                    <input type="email"    name="email"    placeholder="Email"        required>
                    <input type="password" name="password" placeholder="Password (min 8 chars)" required>
                </div>
                <button type="submit" class="sign-up-btn">SIGN UP</button>
            </form>
        </div>
    </div>
</body>
</html>
