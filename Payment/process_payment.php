<?php 
require_once '../db_connect.php';
require_once '../db_functions.php';
require_once '../session.php';
require_once '../PhpMailer/toPayFees.php'; 

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$conn = connectDB();

// Get payment mode from form
$mode = $_POST['mode'] ?? null;

// Validate mode
$allowedModes = ['UPI', 'NetBanking', 'CreditCard', 'DebitCard'];
if (!$mode || !in_array($mode, $allowedModes)) {
    die("Invalid payment mode.");
}

// Fetch course_id (you already assigned a course to student)
$sql = "SELECT course_id FROM student_courses WHERE student_id = ?";
$courseRow = getVal($sql, [$user['id']]);
$courseId = $courseRow['course_id'] ?? null;

// Fetch amount from fee_templates
$sql = "SELECT grand_total FROM fee_templates WHERE fee_temp_name = ? AND course_id = ?";
$template = getVal($sql, [$user['student_type'], $courseId]);
$amountPaid = $template['grand_total'] ?? 0;

$paymentTime = date('Y-m-d H:i:s');
$status = 'Success'; // for now, assume success

// Insert into payments table
$stmt = $conn->prepare("
    INSERT INTO payments (student_id, course_id, payment_time, amount_paid, pay_status, mode_of_payment)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $user['id'],
    $courseId,
    $paymentTime,
    $amountPaid,
    $status,
    $mode
]);

$update = $conn->prepare("UPDATE students SET is_admitted = 1 WHERE id = ?");
$update->execute([$user['id']]);

// = update session data too
$_SESSION['user']['is_admitted'] = 1;
$receiptLink = "http://localhost/PHP_LOGIN/simple_version/login.php"; 

sendPaymentConfirmationMail($studentEmail = $user['email'], $studentName = $user['username'], $receiptLink);

echo "<script>alert('Payment Successful!'); window.location.href='../dashboard.php';</script>";
exit;
?>
