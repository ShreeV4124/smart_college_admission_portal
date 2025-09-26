<?php
include_once '../session.php';
require_once '../db_connect.php';
require_once '../db_functions.php';
require_once '../layout.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Access denied.");
}
$conn = connectDB();

?>
<link rel="stylesheet" href="../styles/form.css">
<link rel="stylesheet" href="../styles/dashboard.css">


<?php renderPage("Fee Head", function() use ($user) {?>
<h2>Add New Fee Head</h2>
<form action="insert_fee_head.php" method="POST">
    <label>Fee Head Id: </label>    
    <input type="number" name="fee_head_id" required><br><br>
    <label>Fee Head Name:</label>
    <input type="text" name="fee_head_name" required><br><br>

    <label>Amount:</label>
    <input type="number" name="fee_amount" step="0.01" required><br><br>

        <?php
        $studentTypes = getVal("SELECT ns_value, ns_description FROM namespace WHERE ns_type = ?", ['stu_type'], true);
        ?>

        <label>Student Type:</label>
        <select name="student_type" id="student_type" required>
            <option value="">Select</option>
            <?php foreach ($studentTypes as $type): ?>
                <option value="<?= htmlspecialchars($type['ns_value']) ?>">
                    <?= htmlspecialchars($type['ns_description']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        
        <div class="checkbox-group">
        <label><strong>Select Applicable Courses: </strong></label><br>
                <?php
                require_once '../db_connect.php';
                $conn = connectDB();

                $stmt = $conn->query("SELECT course_id, course_name FROM courses");
                $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($courses as $course) {
                echo '<label>';
                echo "<input type='checkbox' name='course_ids[]' value='{$course['course_id']}'> ";
                echo htmlspecialchars($course['course_name']);
                echo '</label><br>';
                }
                ?></div>
                <br><br>
    <input type="submit" value="Add Fee Head"><br><br>

    <!-- <a href="../dashboard.php">⬅ Back to Dashboard</a> -->

</form>
<?php  }); 