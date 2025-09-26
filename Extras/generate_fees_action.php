<?php
include_once '../db_connect.php';
include_once '../db_functions.php';
include_once'insert_course_fees.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] === 'student') {
    die("⛔ Access denied.");
}

$conn = connectDB();

// Add all insert calls for each course/type/caste here
// insertCourseFeesForRegular($conn, 6, 1001, 'General');
// insertCourseFeesForRegular($conn, 6, 1005, 'OBC');
// insertCourseFeesForRegular($conn, 6, 1009, 'SC/ST');
// insertCourseFeesForSpecialType($conn, 6, 1010, 'Handicapped');
// insertCourseFeesForSpecialType($conn, 6, 1014, 'War Widow Dependent');
// insertCourseFeesForSpecialType($conn, 6, 1018, 'Jail Inmate');

// insertCourseFeesForRegular($conn, 7, 1001, 'General');
// insertCourseFeesForRegular($conn, 7, 1005, 'OBC');
// insertCourseFeesForRegular($conn, 7, 1009, 'SC/ST');
// insertCourseFeesForSpecialType($conn, 7, 1010, 'Handicapped');
// insertCourseFeesForSpecialType($conn, 7, 1014, 'War Widow Dependent');
// insertCourseFeesForSpecialType($conn, 7, 1018, 'Jail Inmate');

// insertCourseFeesForRegular($conn, 8, 1002, 'General');
// insertCourseFeesForRegular($conn, 8, 1006, 'OBC');
// insertCourseFeesForRegular($conn, 8, 1009, 'SC/ST');
// insertCourseFeesForSpecialType($conn, 8, 1011, 'Handicapped');
// insertCourseFeesForSpecialType($conn, 8, 1015, 'War Widow Dependent');
// insertCourseFeesForSpecialType($conn, 8, 1018, 'Jail Inmate');

// insertCourseFeesForRegular($conn, 9, 1002, 'General');
// insertCourseFeesForRegular($conn, 9, 1006, 'OBC');
// insertCourseFeesForRegular($conn, 9, 1009, 'SC/ST');
// insertCourseFeesForSpecialType($conn, 9, 1011, 'Handicapped');
// insertCourseFeesForSpecialType($conn, 9, 1015, 'War Widow Dependent');
// insertCourseFeesForSpecialType($conn, 9, 1018, 'Jail Inmate');

// insertCourseFeesForRegular($conn, 10, 1003, 'General');
// insertCourseFeesForRegular($conn, 10, 1007, 'OBC');
// insertCourseFeesForRegular($conn, 10, 1009, 'SC/ST');
// insertCourseFeesForSpecialType($conn, 10, 1012, 'Handicapped');
// insertCourseFeesForSpecialType($conn, 10, 1016, 'War Widow Dependent');
// insertCourseFeesForSpecialType($conn, 10, 1018, 'Jail Inmate');

// insertCourseFeesForRegular($conn, 11, 1003, 'General');
// insertCourseFeesForRegular($conn, 11, 1008, 'OBC');
// insertCourseFeesForRegular($conn, 11, 1009, 'SC/ST');
// insertCourseFeesForSpecialType($conn, 11, 1013, 'Handicapped');
// insertCourseFeesForSpecialType($conn, 11, 1017, 'War Widow Dependent');
// insertCourseFeesForSpecialType($conn, 11, 1018, 'Jail Inmate');

header("Location: ../dashboard.php?fees_generated=1");
exit;
?>
