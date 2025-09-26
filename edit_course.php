<?php
require_once 'db_functions.php';
require_once 'session.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$course_id = $_GET['course_id'];
$sql = "SELECT  course_name, duration, cutoff_marks, intake FROM courses WHERE course_id = ?";
$course = getVal($sql, [$course_id]);
if (!$course) { echo "Course not found."; exit; }


function updateCourse($course_id, $course_name, $duration, $cutoff_marks, $intake) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM courses WHERE course_name = :course_name AND course_id != :course_id");
    $stmt->execute([
        ':course_name' => $course_name,
        ':course_id' => $course_id
    ]);
    if ($stmt->rowCount() > 0) {
        return "Course Name already used.";
    }
 
    $stmt = $conn->prepare("UPDATE courses SET course_name = ?, duration = ?, cutoff_marks = ?, intake = ? WHERE course_id = ?");
    $stmt->execute([$course_name, $duration, $cutoff_marks, $intake, $course_id]);
    $conn = null;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = updateCourse($course_id, $_POST['course_name'], $_POST['duration'], $_POST['cutoff_marks'], $_POST['intake']);
    if($result == "")
        echo "Updated successfully. <a href='view_courses.php'>Back to List</a>";
    else
        echo $result;
    exit;
}
?>

<link rel="stylesheet" href="styles/form.css">

<form method="post">
    <br><h3>Update Page:</h3><hr><br>
    Course_name: <input name="course_name" value="<?= htmlspecialchars($course['course_name']) ?>" required><br><br>
    Duration: <input type="text" name="duration" value="<?= htmlspecialchars($course['duration']) ?>" required><br><br>
    Minimum Marks Req.: <input name="cutoff_marks" value="<?= htmlspecialchars($course['cutoff_marks']) ?>" required><br><br>
    
     Intake: <input name="intake" value="<?= htmlspecialchars($course['intake']) ?>" required><br><br>

    <input type="submit" value="Update">
</form>
<a href="view_courses.php">⬅ Back to View</a>
