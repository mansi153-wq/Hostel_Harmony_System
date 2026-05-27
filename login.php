
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
   
    <style>
        /* Body Styling */
        body {
            font-family: 'Arial', sans-serif;
            margin: 10;
            padding: 0;
            height: 100vh;
        

            display: flex;
            justify-content: center;
            align-items: center;
            
            background: url('e.png') no-repeat center center/cover;
        }

        /* Login Container */
        .login-container {
            background: rgba(10, 10, 10, 0.85);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
            position: absolute;
            top: 250px;
            left: 679px;
            width: 350px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        /* Hover Effect for Login Container */
        .login-container:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(77, 74, 74, 0.3);
        }

        /* Heading */
        h2 {
            color: #fff;
            margin-bottom: 20px;
            font-size: 24px;
        }

        /* Input Fields */
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background: rgba(15, 15, 15, 0.9);
            transition: border 0.3s, box-shadow 0.3s;
        }

        /* Hover Effect on Input Fields */
        input:focus {
            border-color: #00ff99;
            outline: none;
            box-shadow: 0 0 8px #00ff99;
        }

        /* Buttons */
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(45deg, #ff6600, #ffcc00);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        /* Hover Effect for Button */
        button:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(255, 102, 0, 0.5);
        }

        /* Error Messages */
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
            display: none;
        }

        /* Link Container */
        .link-container {
            margin-top: 20px;
        }

        /* Link Styling */
        .link-container a {
            text-decoration: none;
            color: #00ff99;
            font-weight: bold;
            transition: color 0.3s, text-shadow 0.3s;
        }

        /* Hover Effect for Links */
        .link-container a:hover {
            text-shadow: 0px 0px 6px #00ff99;
            color: #ffcc00;
        }

        /* Side Image */
        .side-image {
            position: absolute;
            right: 25%;
            top: 50%;
            transform: translateY(-50%);
            width: 300px;
            height: auto;
        }
    </style>
</head>
<body>
    <img src="b.png" alt="Side Image" class="side-image">
    <div class="login-container">
        <h2>Student Login</h2>
        <form id="loginForm" method="POST">
            <label for="email" style="color: #fff;">Email:</label>
            <input type="email" id="email" name="email" required placeholder="Enter your email">
            <div class="error-message" id="emailError">Invalid email format</div>
            
            <label for="password" style="color: #fff;">Password:</label>
            <input type="password" id="password" name="password" required placeholder="Enter your password">
            <div class="error-message" id="passwordError">Password must be at least 8 characters long</div>

            <button type="submit">Login</button>
        </form>
        <div class="link-container">
            <a href="adlogin.php">Admin Login</a>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            let email = document.getElementById('email').value.trim();
            let password = document.getElementById('password').value.trim();
            let emailError = document.getElementById('emailError');
            let passwordError = document.getElementById('passwordError');
            let valid = true;

            // Email Validation (Format Check)
            let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(email)) {
                emailError.style.display = "block";
                valid = false;
            } else {
                emailError.style.display = "none";
            }

            // Password Validation (Minimum 8 Characters)
            if (password.length < 8) {
                passwordError.style.display = "block";
                valid = false;
            } else {
                passwordError.style.display = "none";
            }

            if (!valid) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
