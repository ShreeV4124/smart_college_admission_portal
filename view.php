<link rel="stylesheet" href="styles/view.css">

<?php
require_once 'db_functions.php';
require_once 'session.php';
require_once 'layout.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$currentUser = $_SESSION['user'];
$users = getAllUsersWithCourses();
?>



<link rel="stylesheet" href="styles/view.css">
<link rel="stylesheet" href="styles/dashboard.css">

<!-- <h2>All Students</h2> -->

<?php if($currentUser['role'] == 'admin' || $currentUser['role'] == 'faculty'){ ?>
<?php renderPage('All Students', function() use ($user){ 
    
$currentUser = $_SESSION['user'];
$users = getAllUsersWithCourses();   ?>
    <table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>DOB</th>
        <th>Gender</th>
        <th>Marks</th>
        <th>Preferred</th>
        <th>Course</th>

        <?php if ($currentUser['role'] == 'admin'): ?>
            <th>Assign</th>
        <?php endif; ?>
        <?php if ($currentUser['role'] == 'admin'): ?>
            <th>Verified</th>
        <?php endif; ?>

        <th>Action</th>
    </tr>

    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['mobile']) ?></td>
            <td><?= htmlspecialchars($user['dob']) ?></td>
            <td><?= htmlspecialchars($user['gender']) ?></td>
            <td><?= htmlspecialchars($user['marks']) ?></td>
            <td><?= htmlspecialchars($user['preferred_course_name'] ?? 'NULL') ?></td>
            <td><?= htmlspecialchars($user['assigned_course_name'] ?? 'NULL') ?></td>

            <?php if ($currentUser['role'] == 'admin'): ?>
            <td> 
                <?php if($user['isVerified'] && $user['assigned_course_name'] == NULL): ?>
                    <form method="post" action="assign_course.php?id=<?= $user['id'] ?>">
                    <select name="course_id" required>
                        <option value="">-- Select Course --</option> 
                        <?php foreach (getAllCourses() as $course): ?>
                            <option value="<?= $course['course_id'] ?>"><?= $course['course_name'] ?></option>
                        <?php endforeach; ?> 
                    </select> 
                    <input type="submit" value="Assign Course">
                    </form> 
                    
                    <?php elseif($user['isVerified'] && $user['assigned_course_name'] !== NULL):?> 
                        <p>Assigned</p>

                    <?php else: ?>    
                            <p>Not Verified</p>
                    <?php endif; ?>
            </td>
            <?php endif; ?>


                        
            <!-- set unverified should not show if the course is assigned to the student  -  DONE    -->
            <?php if ($currentUser['role'] == 'admin'): ?>
                <td>
                    <?= $user['isVerified'] ? '✅' : '❌' ?><br>

                    <?php if ($user['isVerified'] && $user['assigned_course_name'] == NULL): ?>
                        <a href="verify.php?id=<?= $user['id'] ?>&status=0">Set Unverified</a>
                    <?php elseif (!$user['isVerified']): ?>
                        <a href="verify.php?id=<?= $user['id'] ?>&status=1">Set Verified</a>
                    <?php endif; ?>
                </td>
            <?php endif; ?>

            <td>
                <?php if ($currentUser['role'] == 'admin' || $currentUser['role'] == 'faculty'): ?>
                    <a href="edit.php?id=<?= $user['id'] ?>">Edit</a>
                <?php else: ?>
                    N/A
                <?php endif; ?>

                
                <?php if ($currentUser['role'] == 'admin' || $currentUser['role'] == 'faculty'): ?>
                    <a href="delete.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                <?php endif; ?>
            </td>

        </tr>
    <?php endforeach; ?>
</table>
<?php
}); ?>

<?php } else{ 
    renderPage('Your Info.', function() use ($user){ 
    $user = getStudentWithCourse($_SESSION['user']['id']);
    ?>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>DOB</th>
        <th>Gender</th>
        <th>Marks</th>
        <th>Course</th>
        <th>Verified</th>
        <th>Action</th>
    </tr>
    <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['mobile']) ?></td>
            <td><?= htmlspecialchars($user['dob']) ?></td>
            <td><?= htmlspecialchars($user['gender']) ?></td>
            <td><?= htmlspecialchars($user['marks']) ?></td>
            <td><?= htmlspecialchars($user['course_name'] ?? 'NULL') ?></td>
            <td>
                    <?= $user['isVerified'] ? '✅' : '❌' ?>
            </td>
            <td>
                    <a href="edit.php?id=<?= $user['id'] ?>&type=student">Edit</a>

                    <a href="delete.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
            </td>
    </tr>
</table>
<?php 
    });
} ?>

