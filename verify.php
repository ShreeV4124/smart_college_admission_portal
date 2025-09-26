<?php
require_once 'db_connect.php';
require_once 'db_functions.php';
require_once 'session.php';

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['id'] != 1)) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $userId = intval($_GET['id']);
    $status = ($_GET['status'] == '1') ? 1 : 0;

    $conn = connectDB();
    $stmt = $conn->prepare("UPDATE students SET isVerified = ? WHERE id = ?");
    $stmt->execute([$status, $userId]);

    header("Location: view.php"); 
    exit;
}
