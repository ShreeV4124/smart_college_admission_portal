<?php
require_once '../db_connect.php';
require_once '../db_functions.php';

$conn = connectDB();

// Fetch all fee_templates
$stmt = $conn->query("SELECT fee_temp_id, fee_head FROM fee_templates");
$templates = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($templates as $template) {
    $fee_temp_id = $template['fee_temp_id'];
    $csv_fids = $template['fee_head'];

    if (empty($csv_fids)) continue;

    // Calculate total from fee_heads
    $placeholders = implode(',', array_fill(0, count(explode(',', $csv_fids)), '?'));
    $fids = explode(',', $csv_fids);

    $sql = "SELECT SUM(fee_amount) AS total FROM fee_heads WHERE f_id IN ($placeholders)";
    $row = getVal($sql, $fids);
    $total = $row['total'] ?? 0;

    // Update fee_templates with grand_total
    $update = $conn->prepare("UPDATE fee_templates SET grand_total = ? WHERE fee_temp_id = ?");
    $update->execute([$total, $fee_temp_id]);

    echo "Updated fee_template ID $fee_temp_id with total ₹$total<br>";
}
?>