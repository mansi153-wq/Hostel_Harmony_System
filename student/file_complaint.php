



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint</title>
    <style>
        /* Apply background image */
        /* Apply background image */
body {
    font-family: 'Arial', sans-serif;
    background: url('type.png') no-repeat center center fixed;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    flex-direction: column;
}


        /* Chat-style container */
        .chat-box {
            background: rgb(194, 196, 202);
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 390px;
            text-align: left;
            position: relative;
            animation: fadeIn 1.5s ease-in-out;
            bottom: 15%;
        }

        /* Chat tail effect */
        .chat-box::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 20px;
            border-width: 10px;
            border-style: solid;
            border-color: #fff transparent transparent transparent;
        }

        /* Header message */
        .header {
            font-size: 1em;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        /* Complaint textarea */
        textarea {
            width: 94%;
            height: 80px;
            padding: 10px;
            background: rgb(236, 238, 243);
            border: 2px solidrgb(43, 40, 207);
            border-radius: 10px;
            font-size: 1em;
            resize: none;
            transition: 0.3s;
        }

        textarea:focus {
            border-color:rgb(45, 59, 182);
            outline: none;
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.5);
        }

        /* Submit button */
        button {
            background-color:rgb(38, 57, 167);
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
            background-color:rgb(30, 55, 168);
            transform: scale(1.05);
        }

        /* Back link */
        .back-link {
            display: block;
            margin-top: 10px;
            color:rgb(38, 47, 170);
            text-decoration: none;
            font-size: 0.9em;
            text-align: center;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="chat-box">
        <div class="header">🔒 Your complaint will remain confidential.</div>
        <form method="POST">
            <textarea name="complaint" placeholder="Type your complaint here..." required></textarea>
            <button type="submit">Submit</button>
        </form>
        <a href="index.php" class="back-link">Back to Dashboard</a>
    </div>

</body>
</html>
