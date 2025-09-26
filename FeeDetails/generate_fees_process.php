<?php
include_once '../db_connect.php';
include_once 'insert_course_fees.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] === 'student') {
    die("Access denied.");
}

$conn = connectDB();

$course_id = $_POST['course_id'];
$student_type = $_POST['student_type']; 

// Step 1: Fetch fee_temp_id based on student_type (which could be REG, OBC, etc.)
$stmt = $conn->prepare("SELECT fee_temp_id FROM fee_templates WHERE fee_temp_name = ? AND course_id = ?");
$stmt->execute([$student_type, $course_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "⚠️ No fee template found for: $student_type and Course ID: $course_id";
    exit;
}

$fee_temp_id = $row['fee_temp_id'];

$stmt = $conn->prepare("SELECT course_fee_id FROM course_fees WHERE fee_temp_id = ? AND student_type = ? AND course_id = ?");
$stmt->execute([$fee_temp_id, $student_type, $course_id]);
$count = $stmt->fetch(PDO::FETCH_ASSOC);

if($count){
    echo "⚠️ Course fees already present for: $fee_temp_id , $student_type and Course ID: $course_id";
    exit;
}

// Step 2: Call correct function based on type
$success = false;

    $success = insertCourseFees($conn, $course_id, $fee_temp_id, $student_type);

// Step 3: Show output
if ($success) {
    echo "<p style='color:green;'>✅ Course fee inserted for Course ID: $course_id | $student_type</p>";
} else {
    echo "<p style='color:red;'>❌ Failed to insert course fee. Check if data already exists or fee_heads are missing.</p>";
}
?>
<a href="../dashboard.php">← Back to Dashboard</a>
