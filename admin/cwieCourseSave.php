<?php
error_reporting(~E_NOTICE);
session_start();
include_once('connect.php');

date_default_timezone_set("Asia/Bangkok");

//$faculty = $_POST["faculty"];
$course = $_POST["course"];
$type = $_POST["type"];
$note = $_POST["note"];
$year = $_POST["year"];

$cwieCoid = $_GET["cwieCoid"];

if (isset($_POST['save'])) {
	$sql = "INSERT INTO cwie_course (major, type, note, date_regis, year) VALUES 
	('$course', '$type', '$note', current_timestamp(), '$year');";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="cwieCourseAdd.php"';
	echo '</script>';
} elseif (isset($_POST['update'])) {
	$sql = "UPDATE cwie_course SET major = '$course', 
	type = '$type', 
	note = '$note', 
	year = '$year' 
	WHERE cwie_course.id = $cwieCoid";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="cwieCourseAdd.php"';
	echo '</script>';
}
