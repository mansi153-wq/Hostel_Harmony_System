<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    // Redirect to login or show an error
    
}
include '../includes/db.php';

$student_id = $_SESSION['student_id'];
$stmt = $conn->prepare("SELECT name, room_number FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->bind_result($name, $room_number);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right,rgb(255, 255, 255),rgb(214, 240, 241));
            color: #333;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transition: width 0.3s;
        }

        .sidebar:hover {
            width: 260px;
        }

        .sidebar .logo {
            width: 96px;
            height: 93px;
            border-radius: 50%;
            display: block;
            margin: 0 auto 20px;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .sidebar .logo:hover {
            transform: rotate(360deg);
        }

        .sidebar h1 {
            margin-bottom: 20px;
            text-align: center;
            font-size: 24px;
            animation: fadeIn 0.5s;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin: 5px 0;
            border-radius: 4px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #34495e;
            transform: translateX(5px);
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            text-align: center;
            font-size: 18px;
        }

        header {
            background-color: rgba(222, 232, 238, 0.9);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: slideIn 0.5s;
        }

        .user-menu {
            position: relative;
            display: flex;
            align-items: center;
        }

        .user-menu img {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            cursor: pointer;
            margin-left: 13px;
            transition: transform 0.3s;
        }

        .user-menu img:hover {
            transform: scale(1.1);
        }

        .user-menu-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            width: 150px;
            z-index: 1000;
        }

        .user-menu:hover .user-menu-content {
            display: block;
        }

        .user-menu-content a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
            transition: background-color 0.3s;
        }

        .user-menu-content a:hover {
            background-color: #f0f4f7;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <img src="logo.png" alt="Logo" class="logo">
            <h1>Dashboard</h1>
            <ul>
                <br>
                <li><a href="book_room.php"><i class="fas fa-book"></i> Book Room</a></li>
                <br>
                <li><a href="file_complaint.php"><i class="fas fa-exclamation-circle"></i> File Complaint</a></li>
                <br>
                <li><a href="room_details.php"><i class="fas fa-sign-out-alt"></i> My Room Details</a></li>
                <br>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <br>
            </ul>
        </aside>
        <main class="main-content">
            <header>
                <h2>Welcome <span id="studentName"></span></h2>
                <div class="user-menu">
                    <img src="profile.png" alt="User ">
                    <span>Hello Student</span>
                    <div class="user-menu-content">
                        <a href="#">My Profile</a>
                        <a href="#">Account Settings</a>
                      
                    </div>
                </div>
            </header>
        </main>
    </div>
    <script>
        let studentName = "<?php echo htmlspecialchars($name); ?>";
        document.getElementById("studentName").innerText = studentName;
    </script>
</body>
</html>