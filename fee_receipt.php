<?php
ob_start();
require_once 'session.php';
require_once 'db_functions.php';
require_once __DIR__ . '/vendor/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$conn = connectDB();

// 1. Get assigned course
$courseRow = getVal("SELECT course_id FROM student_courses WHERE student_id = ?", [$user['id']]);
$course_id = $courseRow['course_id'] ?? null;

// 2. Get course name
$courseData = getVal("SELECT course_name FROM courses WHERE course_id = ?", [$course_id]);
$courseName = $courseData['course_name'] ?? 'N/A';

// 3. Get fee template details
$template = getVal("SELECT fee_head, grand_total FROM fee_templates WHERE fee_temp_name = ? AND course_id = ?", [$user['student_type'], $course_id]);
$feeHeadIdsCSV = $template['fee_head'] ?? '';
$grandTotal = $template['grand_total'] ?? 0;

// 4. Get fee head breakdown
$feeHeads = [];
if (!empty($feeHeadIdsCSV)) {
    $feeHeadIds = explode(',', $feeHeadIdsCSV);
    $placeholders = implode(',', array_fill(0, count($feeHeadIds), '?'));

    $stmt = $conn->prepare("SELECT fee_head_name, fee_amount FROM fee_heads WHERE f_id IN ($placeholders)");
    $stmt->execute($feeHeadIds);
    $feeHeads = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 5. Get payment ID
$paymentData = getVal("SELECT payment_id,mode_of_payment FROM payments WHERE student_id = ?", [$user['id']]);
$payment_id = $paymentData['payment_id'] ?? 'N/A';
$payment_mode = $paymentData['mode_of_payment'] ?? 'N/A';

// 6. Build HTML
$html = '
<style>
    body { font-family: DejaVu Sans; font-size: 14px; }
    .header { text-align: center; font-size: 22px; margin-bottom: 20px; }
    .section { margin-bottom: 10px; }
    .label { font-weight: bold; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    .total-row { font-weight: bold; }
</style>

<div class="header">Student Admission Portal<br>Fee Receipt</div>

<div class="section"><span class="label">Payment ID:</span> ' . htmlspecialchars($payment_id) . '</div>
<div class="section"><span class="label">Username:</span> ' . htmlspecialchars($user['username']) . '</div>
<div class="section"><span class="label">Email:</span> ' . htmlspecialchars($user['email']) . '</div>
<div class="section"><span class="label">Student Type:</span> ' . htmlspecialchars($user['student_type']) . '</div>
<div class="section"><span class="label">Course:</span> ' . htmlspecialchars($courseName) . '</div>

<table>
    <tr>
        <th>Fee Head</th>
        <th>Amount (₹)</th>
    </tr>';

foreach ($feeHeads as $head) {
    $html .= '<tr><td>' . htmlspecialchars($head['fee_head_name']) . '</td><td>₹' . htmlspecialchars($head['fee_amount']) . '</td></tr>';
}

$html .= '<tr class="total-row"><td>Total</td><td>₹' . $grandTotal . '</td></tr>';
$html .= '</table>';

$html .= '<br>
<div class="section"><span class="label">Payment Mode:</span> ' . htmlspecialchars($payment_mode) . '</div>
<div class="section"><span class="label">Payment Status:</span> Paid</div>
<div style="text-align: center; margin-top: 30px;">Thank you for your payment!</div>';

// 7. Generate PDF
$mpdf = new \Mpdf\Mpdf([
    'default_font' => 'dejavusans'
]);
$mpdf->WriteHTML($html);
ob_end_clean();
$mpdf->Output('Fee_Receipt_' . $user['username'] . '.pdf', 'I');
exit;
