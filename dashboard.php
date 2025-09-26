<?php 
require_once 'session.php';
// Prevent browser from caching this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once 'db_functions.php';


if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$currentUser = $user;

$courseAssigned = null;
$preferredCourse = null;

if ($currentUser['role'] == 'student') {
    $courseAssigned = getStudentCourse($currentUser['id']);

    // Get preferred course name
    $conn = connectDB();
    $stmt = $conn->prepare("
        SELECT c.course_name 
        FROM students s
        LEFT JOIN courses c ON s.preferred_course_id = c.course_id
        WHERE s.id = ?
    ");
    $stmt->execute([$user['id']]);
    $preferredCourse = $stmt->fetch(PDO::FETCH_ASSOC);
    $conn = null;
}

// Now include layout
include 'html/dashboardHTML.php';