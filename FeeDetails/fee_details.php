<?php
require_once '../db_connect.php';
require_once '../db_functions.php';
require_once '../session.php';

$user = $_SESSION['user'];

if (!isset($_SESSION['user']))   {
    header("Location: login.php");
    exit;
}

$conn = connectDB();
$currentUser = $_SESSION['user'];

// Fetch course name
$sql = "SELECT course_name FROM courses WHERE course_id = ?";
$val = [$currentUser['preferred_course_id']];
$course = getVal($sql, $val);
$courseName = $course['course_name'] ?? 'N/A';

$studentType = $currentUser['student_type'];


$sql = "SELECT course_id FROM student_courses WHERE student_id = ?";
$val = [$currentUser['id']];
$row = getVal($sql, $val);
$courseId = $row['course_id'] ?? null;


// Step 1: Get fee_head CSV from fee_templates
$sql = "SELECT fee_head, grand_total FROM fee_templates WHERE fee_temp_name = ? AND course_id = ?";
$val = [$currentUser['student_type'], $courseId];
$template = getVal($sql, $val);

$feeHeadIdsCSV = $template['fee_head'] ?? '';
$grandTotal = $template['grand_total'] ?? 0;

// Step 2: Fetch fee head details
$feeHeadIds = explode(',', $feeHeadIdsCSV);
$placeholders = implode(',', array_fill(0, count($feeHeadIds), '?'));

$sql = "SELECT fee_head_name, fee_amount FROM fee_heads WHERE f_id IN ($placeholders)";
$stmt = $conn->prepare($sql);
$stmt->execute($feeHeadIds);
$feeHeads = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<link rel="stylesheet" href="../styles/fee_breakdown.css">


<h2>Your Fee Breakdown</h2>

<div class="student-info">
    <p><strong>Course Assigned:</strong> <?= htmlspecialchars($courseName) ?></p>
    <p><strong>Student Type:</strong> <?= htmlspecialchars($studentType) ?></p>
</div>
<br><br>

<table stylel='border="1"; cellpadding="10"'>
    <tr>
        <th>Fee Head</th>
        <th class='fee_amt'>Amount (₹)</th>
    </tr>
    <?php foreach ($feeHeads as $head): ?>
        <tr>
            <td><?= htmlspecialchars($head['fee_head_name']) ?></td>
            <td class='fee_amt'><?= htmlspecialchars($head['fee_amount']) ?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <th>Total</th>
        <th class='fee_amt'>₹<?= $grandTotal ?></th>
    </tr>
</table>
<br>

<?php if(!$user['is_admitted']): ?>
        <form action="../Payment/payment_gateway.php">
            <button type="submit" class="pay-button" >Proceed</button>
        </form>
        <br>
<?php else : ?>
        <p style='color:green; text-align:center;'>Your Admission is CONFIRMED!</p><br><br>
         <div style='text-align:center;'>

         <form action="../fee_receipt.php">
            <button type="submit" class="pay-button" >Generate Fee Receipt</button>
        </form>
        
        </div><br>
<?php endif; ?>


<div class="go_back"><a href="../dashboard.php">⬅ Back to Dashboard</a></div>

