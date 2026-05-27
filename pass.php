<?php
include 'includes/db.php'; // Make sure to include your database connection

$username = 'admin1';
$password = 'admin1234'; // The password you want to set

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert into the database
$sql = "INSERT INTO admins (username, password) VALUES ('$username', '$hashed_password')";
if ($conn->query($sql) === TRUE) {
    echo "Admin user created successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>