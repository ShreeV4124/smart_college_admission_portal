 <?php 
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// include_once '../db_functions.php';
// include_once '../db_connect.php';
// include_once '../session.php';

// echo "<h3>🔁 Script Started</h3>";

// $conn = connectDB(); // PDO connection
// if (!$conn) {
//     die("❌ DB connection failed");
// }

// $course_ids = [6, 9, 10, 11];
// $student_types = ['Regular', 'Handicapped', 'War Widow Dependent', 'Jail Inmate','General', 'OBC', 'SC/ST'];
// $student_castes = ['General', 'OBC', 'SC/ST'];

// // Mapping caste → template_name (used in fee_templates.fee_temp_name)
// $caste_template_map = [
//     'General' => 'Regular',      // 'General' caste uses 'Regular' template
//     'OBC'     => 'OBC',
//     'SC/ST'   => 'SC/ST'
// ];

// foreach ($course_ids as $course_id) {
//     echo "<br>📌 Course ID: $course_id";

//     foreach ($student_types as $student_type) {
//         echo "<br>— Student Type: $student_type";

        // $castes_to_loop = ($student_type === 'Regular') ? $student_castes : ['General'];

        // foreach ($castes_to_loop as $student_caste) {
        //     // echo "<br>&nbsp;&nbsp;&nbsp;👉 Caste: $student_caste";

        //     // Get template name using mapping
        //     $mapped_template_name = $caste_template_map[$student_caste] ?? null;

        //     if (!$mapped_template_name) {
        //         echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;❌ Mapping error: No template for $student_caste";
        //         continue;
        //     }

            // Fetch fee template
            // $templateStmt = $conn->prepare("SELECT fee_temp_id, fee_head FROM fee_templates WHERE fee_temp_name = :fee_temp_name");
            // $templateStmt->execute([':fee_temp_name' => $mapped_template_name]);
            // $template = $templateStmt->fetch(PDO::FETCH_ASSOC);

            // if (!$template) {
            //     echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;⚠️ No template found for name: $mapped_template_name";
            //     continue;
            // }

            // $fee_temp_id = $template['fee_temp_id'];
            // $fee_head_ids = explode(',', $template['fee_head']);

            // $fee_records = [];
            // $grand_total = 0.00;

            // // Prepare statement to fetch each fee_head
            // $feeStmt = $conn->prepare("
            //     SELECT fee_head_name, fee_amount 
            //     FROM fee_heads 
            //     WHERE f_id = :f_id AND course_id = :course_id AND student_type = :student_type
            // ");

            // foreach ($fee_head_ids as $fid) {
            //     $feeStmt->execute([
            //         ':f_id' => (int)$fid,
            //         ':course_id' => $course_id,
            //         ':student_type' => $student_type
            //     ]);
            //     $fee = $feeStmt->fetch(PDO::FETCH_ASSOC);

            //     if ($fee) {
            //         $fee_records[] = $fee['fee_head_name'] . " - " . $fee['fee_amount'];
            //         $grand_total += (float)$fee['fee_amount'];
            //     } else {
            //         echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;⚠️ No fee_head match for f_id=$fid, course_id=$course_id, type=$student_type";
            //     }
            // }

//             if (count($fee_records) === 0) {
//                 echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;⛔ Skipped (no matching fee_heads)";
//                 continue;
//             }

//             $fee_records_str = implode(", ", $fee_records);

//             // Insert into courses_fees
//             $insertStmt = $conn->prepare("
//                 INSERT INTO courses_fees 
//                 (course_id, fee_temp_id, student_type, fee_records, grand_total)
//                 VALUES
//                 (:course_id, :fee_temp_id, :student_type, :fee_records, :grand_total)
//             ");

//             $insertSuccess = $insertStmt->execute([
//                 ':course_id' => $course_id,
//                 ':fee_temp_id' => $fee_temp_id,
//                 ':student_type' => $student_type,
//                 // ':student_caste' => $student_caste,
//                 ':fee_records' => $fee_records_str,
//                 ':grand_total' => $grand_total
//             ]);

//             if ($insertSuccess) {
//                 echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;✅ Inserted: ₹$grand_total";
//             } else {
//                 echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;❌ Insert Error: " . implode(" | ", $insertStmt->errorInfo());
//             }
//         }
//     }
// }

// echo "<br><br>✅ Script finished.";
// ?>
