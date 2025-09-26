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

$studentId = $_GET['id'];
$courseId = $_POST['course_id'];

// Get student marks and email
$studentStmt = $conn->prepare("SELECT marks, email, username FROM students WHERE id = :id");
$studentStmt->execute([':id' => $studentId]);
$student = $studentStmt->fetch(PDO::FETCH_ASSOC);
$studentMarks = $student['marks'];
$studentEmail = $student['email'];
$studentName = $student['username'];

//  Get course details
$courseQuery = $conn->prepare("SELECT course_name, cutoff_marks, intake FROM courses WHERE course_id = :id");
$courseQuery->execute([':id' => $courseId]);
$course = $courseQuery->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    echo "Course not found.";
    exit;
}

$cutoff = $course['cutoff_marks'];
$intake = $course['intake'];
$courseName = $course['course_name'];

if ($studentMarks >= $cutoff) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM student_courses WHERE course_id = ?");
    $stmt->execute([$courseId]);
    $assignedCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    if (!isCourseAssigned($studentId) && $assignedCount < $intake) {

        // Assign course
        assignCourseToStudent($studentId, $courseId);

        $result = sendCourseAssignmentMail($studentEmail, $studentName, $courseName, $studentId);

        if ($result === true) {
            echo "✅ Course assignment email sent successfully.";
        } else {
            echo $result;  // Will contain the error from PHPMailer
        }

        // header("Location: dashboard.php");
        exit;

    } else {
        $msg = !isCourseAssigned($studentId)
            ? 'ALL SEATS ARE FULL!!!'
            : 'Course already assigned!';
        echo "<script>alert('$msg'); window.location.href='dashboard.php';</script>";
    }
} else {
    echo "<script>
        alert('Cutoff marks criteria not matched.');
        window.location.href='dashboard.php';
    </script>";
}