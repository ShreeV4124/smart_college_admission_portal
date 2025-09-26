<?php
session_start();
include_once '../db_connect.php';
include_once '../db_functions.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] === 'student') {
    die("Access denied.");
}

$conn = connectDB();

$fee_temp_name = $_POST['fee_temp_name'];   // student type
$course_id = $_POST['course_id'];
$fee_head_names = $_POST['fee_heads'] ?? [];

if (empty($fee_head_names)) {
    die("<p style='color:red;'>Please select at least one fee head.</p>");
}

// Step 1: Convert fee_head_name → fee_head_id (matching by student_type)
$fee_head_ids = [];

$query = $conn->prepare("SELECT f_id FROM fee_heads WHERE fee_head_name = ? AND student_type = ? AND FIND_IN_SET(?, course_id) > 0 AND is_delete = 'N'");
$fallbackQuery = $conn->prepare("SELECT f_id FROM fee_heads WHERE fee_head_name = ? AND student_type = 'REG' AND FIND_IN_SET(?, course_id) > 0 AND is_delete = 'N'");

foreach ($fee_head_names as $head_name) {
    // Special case: Jail Inmates get no fees
    if ($fee_temp_name === 'JIN') {
        echo "<p style='color:orange;'>Skipping <strong>$head_name</strong> because student type is Jail Inmate (JIN).</p>";
        continue;
    }

    // Try to find fee head for specific student_type
    $query->execute([$head_name, $fee_temp_name, $course_id]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $fee_head_ids[] = $result['f_id'];
    } else {
        // If not found, fallback to REG
        $fallbackQuery->execute([$head_name, $course_id]);
        $fallback = $fallbackQuery->fetch(PDO::FETCH_ASSOC);

        if ($fallback) {
            $fee_head_ids[] = $fallback['f_id'];
            echo "<p style='color:blue;'>Using REG fee for <strong>$head_name</strong> (no specific fee found for $fee_temp_name).</p>";
        } else {
            echo "<p style='color:red;'>Fee head <strong>$head_name</strong> not found for $fee_temp_name or REG. Skipping.</p>";
        }
    }
}


// Step 2: Check for duplicate template
// $checkStmt = $conn->prepare("SELECT * FROM fee_templates WHERE fee_temp_name = ? AND course_id = ?");
$sql = "SELECT * FROM fee_templates WHERE fee_temp_name = ? AND course_id = ?";
$val = [$fee_temp_name, $course_id];

$row = getVal($sql, $val);

if ($row) {
    echo "<p style='color:red;'>A fee template already exists for <strong>$fee_temp_name</strong> and course ID <strong>$course_id</strong>.</p>";
    echo "<a href='add_fee_template_form.php'>← Go Back</a>";
    exit;
}

// Step 3: Insert template with CSV of fee_head_ids
$fee_head_csv = implode(',', $fee_head_ids);

$insertStmt = $conn->prepare("INSERT INTO fee_templates (fee_temp_name, fee_head, course_id) VALUES (?, ?, ?)");
$success = $insertStmt->execute([$fee_temp_name, $fee_head_csv, $course_id]);

if ($success) {
    echo "<p style='color:green;'>Fee Template added successfully with fee head IDs: $fee_head_csv</p>";
    echo "<a href='add_fee_template_form.php'>← Add Another</a>";
} else {
    $error = $insertStmt->errorInfo();
    echo "<p style='color:red;'>Insert failed: {$error[2]}</p>";
}

?>
