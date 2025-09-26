<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';

function sendCourseAssignmentMail($studentEmail, $studentName, $courseName, $studentId) {
    $mail = new PHPMailer(true);

    try {
        // SMTP config
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'vichareshriraj930@gmail.com';            
        $mail->Password = 'evkcpfjksudgqxyv';  //app password       
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Mail content
        $mail->setFrom('vichareshriraj930@gmail.com', 'Admission Office');
        $mail->addAddress($studentEmail, $studentName);

        $mail->isHTML(true);
        $mail->Subject = "Course Assigned: $courseName";
        $mail->Body = "
            Hi <strong>$studentName</strong>,<br><br>
            Congratulations! You have been assigned the course <strong>$courseName</strong> based on your inputs.<br>
            You may now proceed to confirm your admission by paying the fees.<br><br>
            <a href='http://localhost/PHP_LOGIN/simple_version/html/login.html'>Click here to Pay Fees</a><br><br>
            Regards,<br>
            Admission Team
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        return "Mailer Error: " . $mail->ErrorInfo;
    }
}


function sendRegistrationMail($email, $username, $password) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'vichareshriraj930@gmail.com';
        $mail->Password = 'evkcpfjksudgqxyv';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('vichareshriraj930@gmail.com', 'Student Admission Portal');
        $mail->addAddress($email, $username);

        $mail->isHTML(true);
        $mail->Subject = 'Registration Successful - Student Admission Portal';
        $mail->Body = "
            <h3>Welcome $username,</h3>
            <p>You have been successfully registered.</p>
            <p>Your login credentials:<br>
            Username: <strong>$username</strong><br>
            Password: <strong>$password</strong></p><br>
            You can now Login : <a href='http://localhost/PHP_LOGIN/simple_version/login.php'>Click here to Login</a>

            <p>Regards,<br>Admission Team</p>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        return "Mailer Error: " . $mail->ErrorInfo;
    }
}



function sendPaymentConfirmationMail($email, $username, $receiptLink) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'vichareshriraj930@gmail.com';
        $mail->Password = 'evkcpfjksudgqxyv';  
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('vichareshriraj930@gmail.com', 'Student Admission Portal');
        $mail->addAddress($email, $username);

        $mail->isHTML(true);
        $mail->Subject = 'Payment Successful - Admission Confirmed';
        $mail->Body = "
            <h3>Hello $username,</h3>
            <p>Your fee payment has been successfully received, and your admission is now <strong>confirmed</strong>.</p>
            <p>You can download your official fee receipt from the link below:</p>
            <p><a href='http://localhost/PHP_LOGIN/simple_version/login.php' target='_blank'>📄 Download Fee Receipt</a></p>
            <br>
            <p>Regards,<br>Admission Team</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Mailer Error: " . $mail->ErrorInfo;
    }
}
?>
