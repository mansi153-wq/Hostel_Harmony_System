<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../includes/db.php';

$student_id = $_SESSION['student_id'];
$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $complaint = trim($_POST['complaint']);

    if (empty($complaint)) {
        $error = "Complaint cannot be empty.";
    } elseif (strlen($complaint) > 1000) {
        $error = "Complaint must not exceed 1000 characters.";
    } else {
        $stmt = $conn->prepare("INSERT INTO complaints (student_id, complaint, status) VALUES (?, ?, 'pending')");
        $stmt->bind_param("is", $student_id, $complaint);

        if ($stmt->execute()) {
            $success = "Your complaint has been submitted successfully.";
        } else {
            $error = "Failed to submit complaint. Please try again.";
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
    <title>Submit Complaint</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('type.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .chat-box {
            background: rgb(194, 196, 202);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 420px;
            text-align: left;
            position: relative;
            animation: fadeIn 1.5s ease-in-out;
        }
        .header {
            font-size: 1em;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .msg {
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .msg.error   { background: #fdecea; color: #c0392b; border: 1px solid #e74c3c; }
        .msg.success { background: #eafaf1; color: #1e8449; border: 1px solid #27ae60; }
        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            background: rgb(236, 238, 243);
            border: 2px solid #ccc;
            border-radius: 10px;
            font-size: 1em;
            resize: vertical;
            box-sizing: border-box;
            transition: 0.3s;
        }
        textarea:focus {
            border-color: rgb(45, 59, 182);
            outline: none;
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.5);
        }
        .char-count {
            font-size: 12px;
            color: #666;
            text-align: right;
            margin-top: 4px;
        }
        button {
            background-color: rgb(38, 57, 167);
            color: white;
            border: none;
            padding: 10px 15px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 20px;
            font-size: 0.9em;
            display: block;
            width: 100%;
            transition: all 0.3s ease-in-out;
        }
        button:hover {
            background-color: rgb(30, 55, 168);
            transform: scale(1.05);
        }
        .back-link {
            display: block;
            margin-top: 10px;
            color: rgb(38, 47, 170);
            text-decoration: none;
            font-size: 0.9em;
            text-align: center;
        }
        .back-link:hover { text-decoration: underline; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="chat-box">
        <div class="header">🔒 Your complaint will remain confidential.</div>

        <?php if ($error): ?>
            <div class="msg error"><?php echo htmlspecialchars($error); ?></div>
        <?php elseif ($success): ?>
            <div class="msg success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <textarea name="complaint" id="complaintText" placeholder="Type your complaint here..." maxlength="1000" required><?php echo isset($_POST['complaint']) ? htmlspecialchars($_POST['complaint']) : ''; ?></textarea>
            <div class="char-count"><span id="charCount">0</span>/1000</div>
            <button type="submit">Submit</button>
        </form>
        <a href="index.php" class="back-link">Back to Dashboard</a>
    </div>

    <script>
        const textarea  = document.getElementById('complaintText');
        const charCount = document.getElementById('charCount');
        textarea.addEventListener('input', () => {
            charCount.textContent = textarea.value.length;
        });
        charCount.textContent = textarea.value.length;
    </script>
</body>
</html>
