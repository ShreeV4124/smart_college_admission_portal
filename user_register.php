<?php
include_once 'db_connect.php';
include_once 'security/secutiry.php';

function insertFaculty($username, $password, $email, $role){
    $conn = connectDB();

    $stmt = $conn->prepare('SELECT * FROM users WHERE role = "admin"');
    $stmt->execute([]);
    if( $stmt->rowCount() > 0 && $role === 'admin'){
        return "Admin Already Exists!";
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute([
        ':username' => $username
    ]);

    if ($stmt->rowCount() > 0) {
        return "Username already used.";
    }
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([
        ':email' => $email
    ]);
       if ($stmt->rowCount() > 0) {
        return "Email already used.";
    }

    $stmt = $conn->prepare("INSERT INTO users(username, password, email, role)
     VALUES(:username, :password, :email, :role)");
    
    $result = $stmt->execute([
        ':username' => $username,
        ':password' => $password,
        ':email' => $email,
        ':role' => $role
    ]);

    return $result? true : "Error Registering user!";
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if(containInjectionKeywords($username) || containInjectionKeywords($password)){
        die("<script>alert('Malicious input detected. Registration blocked.'); window.location.href='index.php';</script>");
    }
    $res = insertFaculty($_POST['username'], $_POST['password'], $_POST['email'], $_POST['role']);
    echo is_string($res)? "<p style='color:red;'>$res</p>" : "<p style='color:green; text-align:center;'>Registered! <a href='login.php' style='color:blue;'>Login</a></p>";
}

include 'html/user_register.html';
?>