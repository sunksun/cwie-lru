<?php
session_start();
include_once('connect.php');

date_default_timezone_set("Asia/Bangkok");

$cwieCoid = $_GET["cwieCoid"];

// sql to delete a record
$sql = "DELETE FROM cwie_course WHERE cwie_course.id = $cwieCoid";

if ($conn->query($sql) === TRUE) {
	echo '<script language="javascript">';
	echo 'alert("ลบข้อมูลเรียบร้อยแล้ว"); location.href="cwieCourseAdd.php"';
	echo '</script>';
} else {
  echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>