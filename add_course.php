<?php
require_once 'db_connect.php';
$conn = connectDB();

require_once 'db_functions.php';
require_once 'session.php';
require_once 'PhpMailer/toPayFees.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = $_POST['course_name'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $min_marks = $_POST['min_marks'] ?? 0;
    $intake = $_POST['intake'] ?? 0;

    // You can add validation if needed

    $sql = "INSERT INTO courses (course_name, duration, cutoff_marks, intake) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $success = $stmt->execute([$course_name, $duration, $min_marks, $intake]);

    if ($success) {
        echo "<script>alert('Course added successfully.'); window.location.href='view_courses.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error adding course.');</script>";
    }
}
?>

<link rel="stylesheet" href="styles/form.css">

<h2>Add New Course</h2>
<form method="post" action="">
    Course name: *<input name="course_name" required><br><br>
    Duration: *<input name="duration" required><br><br>
    Min. Marks: *<input type="number" name="min_marks" required><br><br>
    Intake: *<input type="number" name="intake" required><br><br>
    <input type="submit" value="Submit">
</form>
<a href="view_courses.php">⬅ Go Back</a>
