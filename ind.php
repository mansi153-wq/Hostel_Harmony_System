<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: rgb(245, 243, 223);
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #333;
            color: white;
            position: fixed;
            padding-top: 20px;
        }
        .sidebar h2 {
            text-align: center;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            font-size: 17px;
            padding: 21px;
            margin: 5px 0;
            text-align: center;
        }
        .sidebar a:hover {
            background: #575757;
        }
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #444;
            color: white;
            font-size: 25px;
            padding: 30px 60px;
            position: relative;
            width: 100%;
            min-height: 120px;
            box-sizing: border-box;
        }
        .header img {
            width: 120px;
            height: 120px;
            border-radius: 60%;
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
        .header h2 {
            flex-grow: 1;
            text-align: center;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="logo.png" alt="Logo">
        <h2>Admin Dashboard</h2>
    </div>
    <div class="sidebar">
        <h2>Menu</h2>
        <a href="register_student.php">Register Student</a>
        <a href="manage_rooms1.php">Manage Rooms</a>
        <a href="update_complaint_status">Update Complaints</a>
        <a href="view_students.php">View Students</a>
        <a href="view_complaints2.php">View Complaints</a>
        <a href="../logout.php">Logout</a>
    </div>
    <div class="main-content">
        <h3>Welcome, Admin!</h3>
        <p>Manage all administrative tasks efficiently from this dashboard.</p>
    </div>
</body>
</html>
