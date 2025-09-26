<?php
require_once 'db_connect.php';
require_once 'db_functions.php';
require_once 'PhpMailer/toPayFees.php';
require_once 'security/security.php';

function insertUser($username, $password, $email, $mobile, $dob, $gender,$student_type, $preferred, $marks) {
    $conn = connectDB();
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT * FROM students WHERE username = :username");
    $stmt->execute([
        ':username' => $username
    ]);

    if ($stmt->rowCount() > 0) {
        return "Username already used.";
    }
    $stmt = $conn->prepare("SELECT * FROM students WHERE email = :email");
    $stmt->execute([
        ':email' => $email
    ]);
       if ($stmt->rowCount() > 0) {
        return "Email already used.";
    }

    $stmt = $conn->prepare("SELECT * FROM students WHERE mobile = :mobile");
    $stmt->execute([
        ':mobile' => $mobile
    ]);
       if ($stmt->rowCount() > 0) {
        return "Mobile already used.";
    }

    $marks = $_POST['marks'];
    if(!is_numeric($marks) || $marks<0 || $marks>100){
        return "Marks are invalid";
    }
 
    $stmt = $conn->prepare("INSERT INTO students (username, password, email, mobile, dob, gender, student_type, preferred_course_id, marks)
                            VALUES (:username, :password, :email, :mobile, :dob, :gender, :student_type,:preferred, :marks)");
    $result = $stmt->execute([
        ':username' => $username,
        ':password' => $hashed,
        ':email' => $email,
        ':mobile' => $mobile,
        ':dob' => $dob,
        ':gender' => $gender,
        ':student_type' => $student_type,
        // ':student_caste' => $student_caste,
        ':preferred' => $preferred,
        ':marks' => $marks
    ]);
    return $result ? true : "Error registering user.";
}


require_once 'db_functions.php';

$conn = connectDB();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if(containInjectionKeywords($username) || containInjectionKeywords($password)){
        die("<script>alert('Malicious input detected. Registration blocked.'); window.location.href='index.php';</script>");
    }   
    $res = insertUser($_POST['username'], $_POST['password'], $_POST['email'], $_POST['mobile'], $_POST['dob'], $_POST['gender'], $_POST['student_type'], $_POST['preferred'], $_POST['marks']);
    echo is_string($res) ? "<p style='color:red;'>$res</p>" : "<p style='color:green; text-align:center;'>Registered! <a href='login.php' style='color:blue;'>Login</a></p>";

    if(!is_string($res)){
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $plainPassword = $_POST['password']; 
            $email = $_POST['email'];

            
                require_once 'PhpMailer/toPayFees.php';
                sendRegistrationMail($email, $username, $plainPassword);

                // echo "Registration successful! Please check your email.";
                echo "<script>alert('Registration successful! Please check your email.'); window.location.href='index.php';</script>";
            }
             else {
                echo "Error: Could not register.";
            
            }
    }
}

include 'html/registerHTML.php';
?>
