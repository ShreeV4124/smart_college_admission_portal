<?php

require_once '../db_connect.php';
require_once '../db_functions.php';
require_once '../session.php';
require_once '../layout.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Access denied.");
}
$conn = connectDB();

?>
<link rel="stylesheet" href="../styles/form.css">
<link rel="stylesheet" href="../styles/dashboard.css">

<?php renderPage("Fee Template", function() use ($user) { 
    $conn = connectDB(); ?>

<h2>Add New Fee Template</h2>
<form action="insert_fee_template.php" method="POST">
   
    <!-- <label>Fee Head Name:</label>
    <input type="text" name="fee_head_name" required><br><br> -->


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

    For Course: <select name="course_id" required>
        <option value="">Select</option>
        <?php foreach (getAllCourses() as $course): ?>
                <option value="<?= $course['course_id'] ?>"><?= $course['course_name'] ?></option>
            <?php endforeach; ?>
    </select><br><br>

    <strong>Required Fee Heads: </strong><br><?php
                // require_once '../db_connect.php';
                // $conn = connectDB();

                $stmt = $conn->query("SELECT distinct fee_head_name FROM fee_heads");
                $heads = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($heads as $head) {
                echo '<label>';
                echo "<input type='checkbox' name='fee_heads[]' value='" . htmlspecialchars($head['fee_head_name']) . "'> ";
                echo htmlspecialchars($head['fee_head_name']);
                echo '</label><br>';
                }
                ?>
                <br><br>
                
    <input type="submit" value="Add Fee Template"><br><br>

</form>

<hr style="margin: 30px 0; border: 1px solid #ccc;">


<!-- Separate Update Button -->
<form action="update_grand_total.php" method="POST">
    If you have made any changes in the templates: 
    <button type="submit">🔄 Update Grand Totals</button>
</form>
<br><br>

<!-- <a href="../dashboard.php">⬅ Back to Dashboard</a> -->
<?php }); 
