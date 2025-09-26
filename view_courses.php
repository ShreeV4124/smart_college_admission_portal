<!-- CREATE TABLE student_courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB; -->

<?php
    require_once 'db_functions.php';
    require_once 'session.php';
    require_once 'layout.php';
    
    if(!isset($_SESSION['user'])){
        header("Location:login.php");
        exit;
    }

    $currentUser=$_SESSION['user'];

    $courses = getAllCourses();
?>

<link rel="stylesheet" href="styles/view.css">
<link rel="stylesheet" href="styles/dashboard.css">


<?php renderPage("All Courses", function() use ($user) {

      $currentUser=$_SESSION['user'];

    $courses = getAllCourses();
 ?>

<table border="1" cellpadding="12">
    <tr>
        <th>Course ID</th>
        <th>Course Name</th>
        <th>Duration</th>
        <th>Min. Marks</th>
        <th>Intake</th>
        <?php  if($currentUser['role'] == 'admin' || $currentUser['role'] == 'faculty'){ ?>
            <th>Action</th>
        <?php } ?>
    </tr>
    <?php foreach($courses as $course): ?>
        <tr>
            <td><?= htmlspecialchars($course['course_id']) ?></td>
            <td><?= htmlspecialchars($course['course_name']) ?></td>
            <td><?= htmlspecialchars($course['duration']) ?></td>
            <td><?= htmlspecialchars($course['cutoff_marks']) ?></td>
            <td><?= htmlspecialchars($course['intake']) ?></td>
            <?php  if($currentUser['role'] == 'admin' || $currentUser['role'] == 'faculty'){ ?>
                <td>
                    <a href="edit_course.php?course_id=<?= $course['course_id'] ?>">Edit</a>
                    <a href="delete.php?course_id=<?= $course['course_id'] ?>">Delete</a>
                </td>
            <?php } ?>        
        </tr>
    <?php endforeach; ?>
    
    </table>
    
<?php if($currentUser['role'] == 'admin' || $currentUser['role'] == 'faculty'){ ?>

<br><a href="add_course.php">Add New Course</a><br><br>
<?php } ?>
<?php
});
?>

<!-- Add delete button to the course if user is an Admin -->

<!-- add on isapproved flag to check whether a student has payed the fees and his docs has verifed then set the flag to 1, else default 0 , 
and If flag = 1 , them only the student can opt for a course. Give admin the ability to change the isapproved flag. -->