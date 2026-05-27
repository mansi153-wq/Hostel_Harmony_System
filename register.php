<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO students (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

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

        /* Left Side */
        .left {
            width: 50%;
            background: linear-gradient(135deg, #26A69A, #009688);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
        }

        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .left h2 {
            margin-top: 80px;
            font-size: 24px;
        }

        .left p {
            font-size: 14px;
            margin-top: 10px;
        }

        .sign-in-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background: transparent;
            border: 2px solid white;
            color: white;
            font-size: 16px;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .sign-in-btn:hover {
            background: white;
            color: #009688;
        }

        /* Right Side */
        .right {
            width: 50%;
            padding: 40px;
            text-align: center;
        }

        .right h2 {
            color: #009688;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 10px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
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

        .sign-up-btn:hover {
            background: #00796B;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Left Side -->
        <div class="left">
            <img src="logo.png" alt="Logo" class="logo">
            <h2>Welcome Back!</h2>
            <p>To keep connected with us please login with your personal info</p>
            <button class="sign-in-btn">SIGN IN</button>
        </div>

        <!-- Right Side -->
        <div class="right">
            <h2>Create Account</h2>
            <p>Use your email for registration:</p>
            <form method="POST" action="">
            <div class="form-group">
            
                <input type="text" placeholder="Name">
                <input type="email" placeholder="Email">
                <input type="password" placeholder="Password">
            </div>
            <button class="sign-up-btn">SIGN UP</button>
    </form>
        </div>
    </div>

</body>
</html>

