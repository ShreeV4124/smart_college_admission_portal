<?php 
require_once 'session.php';
require_once 'db_functions.php';

// if(!isset($_SESSION['user']) || $_SESSION['user']['username'] !== 'Admin')
if(!isset($_SESSION['user'])){
    die("Inauthorized access. Only Admin can Delete.");
}

if(isset($_GET['id'])){
    $id = $_GET['id'];

    if(canDeleteStudent($id)){
        if(deleteStudent($id)){
            header("Location: view.php?msg=deleted");
            exit;
        }else{
            echo "Error deleting student.";
        }
    }else{
        echo "<script>
        alert('Cannot Delete: Student has a course assigned.');
        window.location.href='view.php';
    </script>";
    }
}else if(isset($_GET['course_id'])){
    $course_id = $_GET['course_id'];

    $sql= "SELECT * FROM student_courses WHERE course_id = ?";
    $ans = getVal($sql, [$course_id]);

    if($ans){
        echo "<script>
        alert('Cannot Delete this Course: Student has this course assigned.');
        window.location.href='view_courses.php';
        </script>";
    }
    else{
        $sql= "DELETE FROM courses WHERE course_id = ?";
        getVal($sql, [$course_id]);
        echo "<script>
            alert('Deleted this Course as no Student has this course assigned.');
            window.location.href='view_courses.php';
        </script>";
    }
}
else{
    echo "Invalid request.";
}


?>