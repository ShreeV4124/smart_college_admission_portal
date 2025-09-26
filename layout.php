<?php
// layout/layout.php
require_once 'session.php';
require_once 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$currentUser = $_SESSION['user'];

function renderPage($pageTitle, $contentCallback) {
    global $user;
    $user = $_SESSION['user'];
    $currentUser = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <strong class="logo">My College</strong>
            <div class="page-title"> <?= htmlspecialchars($pageTitle) ?></div>
            <p>Welcome, <strong><?= htmlspecialchars($user['username']) ?></strong>!</p>
        </div>

       
        <!-- Sidebar -->
       <div class="menuBar">
        <a href="<?= BASE_URL ?>/dashboard.php">Dashboard</a>
        <hr style="border: 1px solid #ccc;">

        <a href="<?= BASE_URL ?>/view.php">View All Students</a>
        <hr style="border: 1px solid #ccc;">
        
        <a href="<?= BASE_URL ?>/view_users.php">View All Faculty</a>
        <hr style="border: 1px solid #ccc;">

        <a href="<?= BASE_URL ?>/view_courses.php">View All Courses</a>
        <hr style="border: 1px solid #ccc;">

        <?php if ($user['role'] == 'admin' || $user['role'] == 'faculty'): ?>
        <a href="<?= BASE_URL ?>/Payment/view_payments.php">View All Payments</a>
        <hr style="border: 1px solid #ccc;">
        <?php endif; ?>

        <?php if ($user['role'] == 'student'): ?>
        <a href="<?= BASE_URL ?>/Payment/view_payments.php">Payments History</a>
        <hr style="border: 1px solid #ccc;">
        <?php endif; ?>

        <?php if ($user['role'] == 'admin'): ?>
            
            <a href="<?= BASE_URL ?>/FeeDetails/generate_fees_form.php">Generate Course Fees</a>
            <hr style="border: 1px solid #ccc;">
            
            <a href="<?= BASE_URL ?>/FeeDetails/add_fee_head_form.php">Generate Fee Heads</a>
            <hr style="border: 1px solid #ccc;">
            
            <a href="<?= BASE_URL ?>/FeeDetails/add_fee_template_form.php">Generate Fee Template</a>
                    <hr style="border: 1px solid #ccc;">

        <?php endif; ?>

        
        <a href="<?= BASE_URL ?>/logout.php">Logout</a>
    </div>

        <!-- Main Content -->
        <div class="content">
            <?php call_user_func($contentCallback) ?>
        </div>

    </div>

    <footer class="dashboard-footer">
    <div class="footer-content">
        <p>&copy; <?= date('Y') ?> My College. All Rights Reserved.</p>
        <p>Made by the College IT Team</p>
    </div>
    </footer>
</body>
</html>
<?php } ?>
