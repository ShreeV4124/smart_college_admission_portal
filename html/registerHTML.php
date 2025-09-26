<link rel="stylesheet" href="styles/form.css">
<?php include_once 'db_functions.php'; ?>

<h2>Register</h2>
<form method="post" action="register.php">
    Username: *<input type="text" name="username" required><br><br>
    Password: *<input type="password" name="password" required><br><br>
    Email: *<input type="email" name="email" required><br><br>
    Mobile: *<input type="text" name="mobile" required><br><br>
    Date of Birth: *<input type="date" name="dob" required><br><br>
    Gender:
    *<select name="gender" required>
        <option value="">Select</option>
        <option>Male</option>
        <option>Female</option>
        <option>Others</option>
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


    Preferred Course: <select name="preferred" required>
        <option value="">Select</option>
        <?php foreach (getAllCourses() as $course): ?>
                <option value="<?= $course['course_id'] ?>"><?= $course['course_name'] ?></option>
            <?php endforeach; ?>
    </select><br><br>
    Marks: *<input type="text" name="marks" required><br><br> 
    <input type="submit" value="Register">
</form>
<a href="index.php">⬅ Go Back</a>


<!-- <script>
function toggleCasteField() {
    const type = document.getElementById("student_type").value;
    const casteDiv = document.getElementById("casteField");
    casteDiv.style.display = (type === 'Regular') ? 'block' : 'none';
}
window.onload = toggleCasteField;
</script> -->