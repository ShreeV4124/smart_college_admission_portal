<?php

require_once '../db_connect.php';

function insertCourseFees($conn, $course_id, $fee_temp_id, $student_type) {
    // Step 1: Get fee_head IDs from template
    $stmt = $conn->prepare("SELECT fee_head FROM fee_templates WHERE fee_temp_id = ?");
    $stmt->execute([$fee_temp_id]);
    $template = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$template) return false;

 
    $insert = $conn->prepare("
        INSERT INTO course_fees (course_id, fee_temp_id, student_type)
        VALUES (?, ?, ?)
    ");

    return $insert->execute([$course_id, $fee_temp_id, $student_type]);
}


