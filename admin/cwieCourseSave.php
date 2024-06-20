<?php
error_reporting(~E_NOTICE);
session_start();
$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];
include_once('connect.php');

date_default_timezone_set("Asia/Bangkok");

//$faculty = $_POST["faculty"];
$course = $_POST["course"];
$type = $_POST["type"];
$note = $_POST["note"];
$year = $_POST["year"];

$cwieCoid = $_GET["cwieCoid"];

if (isset($_POST['save'])) {
	// กำหนดค่าเริ่มต้นให้กับทุกฟิลด์เป็น 0 หรือค่าที่เหมาะสม
	$separate = "";
	$parallel = "";
	$mix = "";

	// กำหนดฟิลด์ที่เลือกเป็น 1 หรือค่าที่เหมาะสม
	if ($type == "Separate") {
		$separate = "/";
	} elseif ($type == "Parallel") {
		$parallel = "/";
	} elseif ($type == "Mix") {
		$mix = "/";
	}

	$sql = "INSERT INTO cwie_course (faculty_id, major, separate, parallel, mix, note, date_regis, year) VALUES 
	('$faculty_id', '$course', '$separate', '$parallel', '$mix', '$note', current_timestamp(), '$year');";
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
