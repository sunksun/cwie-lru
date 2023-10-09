<?php
session_start();
include_once('connect.php');

date_default_timezone_set("Asia/Bangkok");

$student_id = $_GET["student_id"];

// sql to delete a record
$sql = "DELETE FROM tblstudent WHERE student_id = '$student_id';";
echo "$sql";

if ($conn->query($sql) === TRUE) {
	echo '<script language="javascript">';
	echo 'alert("ลบข้อมูลเรียบร้อยแล้ว"); location.href="studentAdd.php"';
	echo '</script>';
} else {
	echo "Error deleting record: " . $conn->error;
}

$conn->close();
