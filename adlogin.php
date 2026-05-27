<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username='$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            header("Location: admin/ind.php"); // Redirect to admin dashboard
        } else {
            echo "Invalid password";
        }
    } else {
        echo "No user found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
                body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            height: 120vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('a.avif') no-repeat center center/cover;
            position: relative;
           
        }

        /* Dark Overlay Effect for better readability */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 95%;
            height: 100%;
            background: url('a.avif') no-repeat center center/cover;
            filter: blur(5px);
            opacity: 1.5;
            z-index:0.1;
        }
        .login-container {
            background: rgba(9, 16, 26, 0.9);
            padding: 35px;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            position: absolute;
            top: 199px;
            left: 779px;
            width: 370px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        /* Hover effect for the login container */
        .login-container:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        }

        h2 {
            margin-bottom: 20px;
            color: #ffff;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        /* Hover effect for input fields */
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

        /* Hover effect for the button */
        button:hover {
            background: rgb(7, 53, 11);
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 255, 128, 0.4);
        }

        .link-container {
            margin-top: 25px;
        }

        /* Hover effect for the link */
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
        <form id="loginForm" method="POST">
            <input type="text" id="username" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="link-container">
            <a href="login.php">Click here for Student Login</a>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            let username = document.getElementById('username').value.trim();
            let password = document.getElementById('password').value.trim();
            
            if (username === "") {
                alert("Username is required.");
                event.preventDefault();
                return;
            }
            
            if (password === "") {
                alert("Password is required.");
                event.preventDefault();
                return;
            }
            
            if (password.length < 8) {
                alert("Password must be at least 8 characters long.");
                event.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>
