<?php

require_once '../db_connect.php';
require_once '../db_functions.php';
require_once '../session.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

$sql = "SELECT course_name FROM courses WHERE course_id = ?";
$val = [$user['preferred_course_id']];
$course = getVal($sql, $val);
$courseName = $course['course_name'] ?? 'N/A';

$studentType = $user['student_type'];

$sql = "SELECT course_id FROM student_courses WHERE student_id = ?";
$val = [$user['id']];
$row = getVal($sql, $val);
$courseId = $row['course_id'] ?? null;

$sql = "SELECT  grand_total FROM fee_templates WHERE fee_temp_name = ? AND course_id = ?";
$val = [$user['student_type'], $courseId];
$template = getVal($sql, $val);

$grandTotal = $template['grand_total'] ?? 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Gateway</title>
    <link rel="stylesheet" href="../styles/payment_gateway.css">
</head>
<body>
    <h2 style="text-align:center">Payment Gateway</h2>

    <form action="process_payment.php" method="post" style="max-width:400px; margin:auto;">
        <table>
            <tr>
                <td>
                    Amount: 
                </td>
                <td>
                    ₹<?= $grandTotal ?>
                </td>
            </tr><br><br>
            <tr>
                <td><label for="mode">Mode of Payment:</label></td>
                <td>
                    <select name="mode" id="mode" required>
                        <option value="">-- Select --</option>
                        <option value="UPI">UPI</option>
                        <option value="NetBanking">Net Banking</option>
                        <option value="CreditCard">Credit Card</option>
                        <option value="DebitCard">Debit Card</option>
                    </select>
                </td>
            </tr><br><br>
            <tr>
                <td colspan="2" style="text-align:center; padding-top: 20px;">
                    <button type="submit" class="pay-button">Pay Now</button>
                </td>
            </tr>
        </table>
    </form>

    <div class="go_back" style="text-align:center; margin-top:20px;">
        <a href="../FeeDetails/fee_details.php">⬅ Back to Fee Details</a>
    </div>
</body>
</html>
