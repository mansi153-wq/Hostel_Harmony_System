<?php
session_start();
include 'includes/db.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: admin/ind.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($admin_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                $_SESSION['admin_id'] = $admin_id;
                header("Location: admin/ind.php");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            // Same error message for both wrong user and wrong password (prevents enumeration)
            $error = "Invalid username or password.";
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
    <title>Admin Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            height: 120vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('img/a.avif') no-repeat center center/cover;
            position: relative;
        }
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.45);
            z-index: 0;
        }
        .login-container {
            background: rgba(9, 16, 26, 0.9);
            padding: 35px;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            position: relative;
            z-index: 1;
            width: 370px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .login-container:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        }
        h2 {
            margin-bottom: 20px;
            color: #fff;
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
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background: rgba(255,255,255,0.1);
            color: #fff;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
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
            background: rgb(31, 194, 16);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        button:hover {
            background: rgb(7, 53, 11);
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 255, 128, 0.4);
        }
        .link-container {
            margin-top: 25px;
        }
        .link-container a {
            text-decoration: none;
            color: rgb(185, 194, 186);
            font-weight: bold;
            transition: color 0.3s, text-shadow 0.3s;
        }
        .link-container a:hover {
            text-decoration: underline;
            color: #00ff99;
            text-shadow: 0px 0px 6px #00ff99;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if ($error): ?>
            <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required autocomplete="username">
            <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
            <button type="submit">Login</button>
        </form>
        <div class="link-container">
            <a href="login.php">Click here for Student Login</a>
        </div>
    </div>
</body>
</html>
