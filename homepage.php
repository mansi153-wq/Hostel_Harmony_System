<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Harmony System</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .container {
            display: flex;
            height: 100vh;
        }
        .left-section {
            width: 50%;
            background:rgb(255, 255, 255);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .left-section img {
            width: 99%;
            height: 90%;
        }
        .right-section {
            width: 50%;
            background: rgb(51, 56, 61);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 50px;
        }
        .right-section h1 {
            font-size: 32px;
            margin-bottom: 20px;
        }
        .right-section p {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .btn {
            background: #36a420;
            color: white;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 10px;
        }
        .compare {
            color: white;
            text-decoration: underline;
            font-size: 24px;
        }

        .logo {
            display: flex;
            align-items: center;
        }
        .logo img {
            position: fixed;
            top: 42px;
            width: 199px;
            height: 199px;
            border-radius: 75%;
            margin-right: 299x;
        }


        .navbar {
    position: relative;
    top: 299px;
    width: 67%;
    margin-left: 17%; /* Adjusted margin for starting point */
    display: flex;
    justify-content: space-between;
    padding: 20px 50px;
    background: rgb(18, 51, 80); /* Elegant light green shade */
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Light shadow for a soft 3D effect */
    transition: background-color 0.3s ease;
    position: relative;
}




.navbar a {
    text-decoration: none;
    color: #fff; /* White text for the links */
    margin-left: 30px; /* Adjusted margin for spacing between links */
    font-size: 21px;
    font-weight: 559;
    padding: 10px 15px; /* Adds padding around the links */
    border-radius: 5px; /* Rounded corners for the links */
    transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth transition for background color and scaling */
}

.navbar a:hover {
    color:rgb(255, 255, 255); /* Green color on hover */
    background: rgba(1, 7, 0, 0.2); /* Light background color on hover to create the box effect */
    transform: scale(1.1); /* Slight zoom effect on hover */
}

.navbar a:active {
    color: #2c8f1e; /* Darker green when clicked */
}

.signup {
    background: #36a420; /* Green background for sign-up button */
    color: white;
    padding: 8px 15px;
    text-decoration: none;
    margin-left: 20px;
    border-radius: 5px; /* Rounded corners for the sign-up button */
    font-weight: 600;
    transition: background-color 0.3s ease; /* Smooth transition for background color */
}

.signup:hover {
    background: #2d8e1e; /* Darker green on hover */
}

.navbar a:last-child {
    margin-right: 0; /* Remove margin on the last link for better alignment */
}

/* Hover effect for navbar */
.navbar:hover {
    background: rgb(51, 224, 158); /* Slightly darker green on hover */
}

        /* Logo animation at top-left corner */
        /* Logo animation at top-left corner */
.animated-logo {
    position: fixed;
    top: 132px;
    left: 2000px; /* Start from outside the screen */
    font-size: 69px;
    font-weight: bold;
    color:rgb(0, 0, 0); /* Blue shade */
    text-transform: uppercase;
    letter-spacing: 5px;
    opacity: 1;
    filter: blur(0px);
    animation: slideIn 3s ease-out forwards;
    z-index: 80; /* Ensures visibility over all elements */
}

/* Sliding animation from left to right */
@keyframes slideIn {
    0% {
        left: -300px;
    }
    100% {
        left: 219px;
    }
}

    </style>
</head>
<body>

    <!-- Animated Logo Text -->
    <div class="animated-logo">Hostel Harmony System</div>
    <div class="logo">
            <img src="logo.png" alt="Logo"> <!-- Logo Image -->
                   </div>
    <div class="navbar">
        
        <div>
            <a href="">About Us</a>
            <a href="">Feedback</a>
            
            <a href="about.html">Facilities</a>
        </div>
        <div>
            <a href="login.php">Log In</a>
            <a href="register.php" class="signup">Sign Up</a>
        </div>
    </div>

    <div class="container">
        <div class="left-section">
            <img src="home.jpg" alt="Illustration">
        </div>
        <div class="right-section">
            <h1>Creating Comfort, Crafting Memories..</h1>
            <p>where comfort, safety, and a sense of community come together to offer you the ultimate living experience. Our hostel is designed with your needs in mind, providing a home-like atmosphere, modern amenities, and a vibrant space for learning, growing, and connecting with fellow students. Whether you're here for a short-term stay or a longer journey, we ensure every moment spent with us is both comfortable and memorable.".</p>
            <a href="#" class="btn">Start Your Free 30-Day Trial</a>
            <br>
            <a href="contact.html" class="compare">Contact Us</a>
        </div>
    </div>

</body>
</html>
