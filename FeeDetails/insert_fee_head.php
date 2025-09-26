<?php
session_start();
require_once '../db_connect.php';
require_once '../db_functions.php';


$conn = connectDB();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] === 'student') {
    die("Access denied.");
}

// Fetch form data
$fee_head_id = $_POST['fee_head_id'];
$fee_head_name = trim($_POST['fee_head_name']);
$fee_amount = trim($_POST['fee_amount']);
$student_type = $_POST['student_type'];
$created_by = $_SESSION['user']['username'];
$course_ids_array = $_POST['course_ids'] ?? [];

if (empty($course_ids_array)) {
    die("<p style='color:red;'>⚠️ Please select at least one course.</p>");
}

// Normalize fee head name (optional if you're storing clean names)
$normalized_name = strtolower(preg_replace('/[\s_-]+/', '', $fee_head_name));

// Step 1: Check for duplicates for each selected course
$duplicates = [];

foreach ($course_ids_array as $course_id) {
    $sql = "SELECT course_id FROM fee_heads 
            WHERE fee_head_name = ? AND student_type = ? 
              AND FIND_IN_SET(?, course_id) > 0 AND is_delete = 'N'";

    $row = getVal($sql, [$fee_head_name, $student_type, $course_id]);

    if ($row) {
        $duplicates[] = $course_id;
    }
}

if (!empty($duplicates)) {
    echo "<p style='color:red;'>❌ Fee Head <strong>$fee_head_name</strong> already exists for Course ID(s): " . implode(', ', $duplicates) . "</p>";
    echo "<a href='add_fee_head_form.php'>← Go Back</a>";
    exit;
}

// Step 2: Insert if no duplicate for selected courses
$course_ids_csv = implode(',', $course_ids_array);

$insert = $conn->prepare("INSERT INTO fee_heads 
    (fee_head_id, fee_head_name, fee_amount, student_type, course_id, create_by) 
    VALUES (?, ?, ?, ?, ?, ?)");

$success = $insert->execute([
    $fee_head_id,
    $fee_head_name,
    $fee_amount,
    $student_type,
    $course_ids_csv,
    $created_by
]);

if ($success) {
    echo "<p style='color:green;'>✅ Fee Head <strong>$fee_head_name</strong> added successfully for courses: $course_ids_csv</p>";
    echo "<a href='add_fee_head_form.php'>← Add Another</a>";
} else {
    echo "<p style='color:red;'>❌ Failed to insert fee head. Please try again.</p>";
}
?>
