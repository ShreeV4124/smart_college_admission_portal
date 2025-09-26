<?php
include_once '../session.php';
include_once '../db_functions.php';
include_once '../layout.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] === 'student') {
    die("Access denied.");
}
?>
<link rel="stylesheet" href="../styles/form.css">
<link rel="stylesheet" href="../styles/dashboard.css">

<?php  renderPage("Course Fees", function() use ($user){
    $user = $_SESSION['user'];
    ?>
<h2>Generate Course Fees</h2>
<form action="generate_fees_process.php" method="POST">
    <label>Course: </label>
    <select name="course_id" required>
        <option value="">Select</option>
        <?php foreach (getAllCourses() as $course): ?>
                <option value="<?= $course['course_id'] ?>"><?= $course['course_name'] ?></option>
            <?php endforeach; ?>
    </select><br><br>

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

    <!-- <div id="casteField">
        <label>Student Caste (for Regular only):</label>
        <select name="student_type">
            <option value="General">General</option>
            <option value="OBC">OBC</option>
            <option value="SC/ST">SC/ST</option>
        </select><br><br>
    </div> -->

    <!-- <button type="submit" style="padding:8px 16px;">Generate</button><br><br> -->
        <input type="submit" value="Generate"><br><br>

    <a href="../dashboard.php">⬅ Back to Dashboard</a>

</form>

<?php }); ?>

<script>
function toggleCasteField() {
    const type = document.getElementById("student_type").value;
    const casteDiv = document.getElementById("casteField");
    casteDiv.style.display = (type === 'Regular') ? 'block' : 'none';
}
window.onload = toggleCasteField;
</script>
