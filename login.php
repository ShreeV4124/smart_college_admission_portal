<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'db_functions.php';
require_once 'session.php';
require_once 'security/security.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';

    if(containInjectionKeywords($username)){
        die("<script>alert('Malicious input detected. Login blocked.'); window.location.href='index.php';</script>");
    }    
    if($_POST['role'] === 'Student'){
        $user = getUserByUsername($_POST['username'], $_POST['role']);
        if ($user && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user'] = $user;
            $_SESSION['user']['role'] = 'student';
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<p style='color:red;'>Invalid username or password.</p>";
        }
    }
    else{
        $user = $user = getUserByUsername($_POST['username'], $_POST['role']);
        if ($user && ($_POST['password'] === $user['password'])) {
            $_SESSION['user'] = $user;
            $_SESSION['user']['role']= $user['role'];
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<p style='color:red;'>Invalid username or password.</p>";
        }
    }
}
include 'html/login.html';
?>
