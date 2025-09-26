<?php


require_once 'db_connect.php';


function getAllUsers() {
    $conn = connectDB();
    $stmt = $conn->query("SELECT * FROM students ORDER BY id ASC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;
    return $users;
}



function getUserByUsername($username, $role) {
    $conn = connectDB();
    if($role === 'Student'){
        $stmt = $conn->prepare("SELECT * FROM students WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $user;
    }
    else{
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $user;
    }
}



function getUserById($id) {
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // echo "<pre>"; print_r($user); echo "</pre>";
    $conn = null;
    return $user;
}



function getAllCourses() {
    $conn = connectDB();
    $stmt = $conn->query("SELECT * FROM courses");
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;
    return $courses;
}


function isCourseAssigned($studentId) {
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT * FROM student_courses WHERE student_id = ?");
    $stmt->execute([$studentId]);
    $conn = null;
    return $stmt->rowCount() > 0;
}


function assignCourseToStudent($studentId, $courseId) {
    $conn = connectDB();
    $stmt = $conn->prepare("INSERT INTO student_courses (student_id, course_id) VALUES (?, ?)");
    $stmt->execute([$studentId, $courseId]);
    $conn = null;
}


function getStudentCourse($studentId) {
    $conn = connectDB();
    $stmt = $conn->prepare("
        SELECT c.* FROM student_courses sc

        JOIN courses c ON sc.course_id = c.course_id
        WHERE sc.student_id = ?
    ");
    $stmt->execute([$studentId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $conn = null;
    return $result;
}


function canDeleteStudent($id){
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT * FROM student_courses WHERE student_id = ?");
    $stmt->execute([$id]);
    return $stmt->rowCount() === 0; 
}


function deleteStudent($id){
    $conn = connectDB();
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    return $stmt->execute([$id]);
}



function getAllUsersWithCourses(){
    $conn = connectDB();
    $sql = "
        SELECT s.id, s.username, s.email, s.mobile, s.dob, s.gender,s.marks,s.preferred_course_id, s.isVerified,
               p.course_name AS preferred_course_name, c.course_name AS assigned_course_name
        FROM students s
        LEFT JOIN student_courses sc ON s.id = sc.student_id
        LEFT JOIN courses c ON c.course_id = sc.course_id
        LEFT JOIN courses p ON s.preferred_course_id = p.course_id
        ORDER BY s.id ASC
    ";
    $stmt = $conn->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;
    return $users;
}



function getStudentWithCourse($studentId) {
    $conn = connectDB();
    $sql = "
        SELECT s.*, c.course_name
        FROM students s
        LEFT JOIN student_courses sc ON s.id = sc.student_id
        LEFT JOIN courses c ON c.course_id = sc.course_id
        WHERE s.id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$studentId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $conn = null;
    return $user;
}




function getVal($sql, $params = [], $fetchAll = false) {
    $conn = connectDB();
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $fetchAll ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAllRows($sql, $params = []) {
    $conn = connectDB();
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



?>

