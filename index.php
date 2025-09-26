<?php
session_start();
require_once "db_functions.php";

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome - Student Admission Portal</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #bad2edff;
            text-align: center;
            margin-top: 50px;
           display: flex;
    flex-direction: column;      /* stack items vertically */
    justify-content: space-between; /* distribute vertical space */
    min-height: 93vh;           
}
        /* .logo {
            width: 120px;
            margin-bottom: 20px;
        } */
        h1 {
            margin-bottom: 30px;
        }
        .options{
            margin-top: -150px;
        }
        .btn {
            display: inline-block;
            margin: 10px;
            padding: 12px 24px;
            font-size: 16px;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 6px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .dashboard-footer {
            position: static;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 28px;
            background-color: #a4c9f0;
            color: rgb(0, 0, 0);
            text-align: center;
            padding: 12px 0;
            font-size: 14px;
            font-family: 'Segoe UI', sans-serif;
            box-shadow: 0 -2px 5px rgba(0,0,0,0.05);
            z-index: 1000;
        }

        .footer-content p {
            margin: -0.8px 1px;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- College Logo -->
        <img src="images/mycollegelogo.jpeg" alt="College Logo" class="logo">

        <h1>Welcome to the Student Admission Portal</h1>
    </div>
    
    <div class="options">
        <!-- Options -->
        <a href="register.php" class="btn">Register as New Student</a>
        <a href="login.php" class="btn">Login</a>
    </div>
    

    <footer class="dashboard-footer">
    <div class="footer-content">
        <p>&copy; <?= date('Y') ?> My College. All Rights Reserved.</p>
        <p>Made by the College IT Team</p>
    </div>
    </footer>
</body>
</html>
