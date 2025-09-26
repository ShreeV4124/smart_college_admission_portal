<?php
require_once '../db_functions.php';
require_once '../session.php';
require_once '../layout.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$currentUser = $_SESSION['user'];

// Only admin or faculty can access this page
if (!in_array($currentUser['role'], ['admin', 'faculty', 'student'])) {
    die("Access denied.");
}

// Fetch all payment records joined with users and courses
if (in_array($currentUser['role'], ['admin', 'faculty'])) {

$sql = "SELECT p.payment_id, u.username, c.course_name, p.payment_time, p.amount_paid, p.pay_status, p.mode_of_payment
        FROM payments p
        JOIN students u ON p.student_id = u.id
        JOIN courses c ON p.course_id = c.course_id
        ORDER BY p.payment_time DESC";

$payments = getAllRows($sql);  // This function should return all rows as array
?>

<link rel="stylesheet" href="../styles/view.css">
<link rel="stylesheet" href="../styles/dashboard.css">

<?php
renderPage("All Payment Records", function () use ($payments) {
?>
    <!-- <h2 style="text-align: center;">All Payment Records</h2> -->
    <table border="1" cellpadding="12" style="margin:auto;">
        <tr>
            <th>Payment ID</th>
            <th>Student Name</th>
            <th>Course Name</th>
            <th>Payment Time</th>
            <th>Paid Amount</th>
            <th>Pay Status</th>
            <th>Mode</th>
        </tr>
        <?php foreach ($payments as $payment): ?>
            <tr>
                <td><?= htmlspecialchars($payment['payment_id']) ?></td>
                <td><?= htmlspecialchars($payment['username']) ?></td>
                <td><?= htmlspecialchars($payment['course_name']) ?></td>
                <td><?= htmlspecialchars($payment['payment_time']) ?></td>
                <td>₹<?= htmlspecialchars($payment['amount_paid']) ?></td>
                <td><?= htmlspecialchars($payment['pay_status']) ?></td>
                <td><?= htmlspecialchars($payment['mode_of_payment']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
});
} else {
    $sql = "SELECT p.payment_id, u.username, c.course_name, p.payment_time, p.amount_paid, p.pay_status, p.mode_of_payment
            FROM payments p
            JOIN students u ON p.student_id = u.id
            JOIN courses c ON p.course_id = c.course_id
            WHERE p.student_id = ?
            ORDER BY p.payment_time DESC";

    $payments = getAllRows($sql, [$currentUser['id']]);
?>


<link rel="stylesheet" href="../styles/view.css">
<link rel="stylesheet" href="../styles/dashboard.css">

<?php
    renderPage("All Payment Records", function () use ($payments) {
?>
        <!-- <h2 style="text-align: center;">All Payment Records</h2> -->
        <table border="1" cellpadding="12" style="margin:auto;">
            <tr>
                <th>Payment ID</th>
                <th>Student Name</th>
                <th>Course Name</th>
                <th>Payment Time</th>
                <th>Paid Amount</th>
                <th>Pay Status</th>
                <th>Mode</th>
            </tr>
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?= htmlspecialchars($payment['payment_id']) ?></td>
                    <td><?= htmlspecialchars($payment['username']) ?></td>
                    <td><?= htmlspecialchars($payment['course_name']) ?></td>
                    <td><?= htmlspecialchars($payment['payment_time']) ?></td>
                    <td>₹<?= htmlspecialchars($payment['amount_paid']) ?></td>
                    <td><?= htmlspecialchars($payment['pay_status']) ?></td>
                    <td><?= htmlspecialchars($payment['mode_of_payment']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

<?php
    });
}  ?>