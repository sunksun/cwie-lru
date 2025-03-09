<?php
error_reporting(~E_NOTICE);
session_start();
include_once('connect.php');

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['fullname']) || $_SESSION['fullname'] == '') {
	echo '<script language="javascript">';
	echo 'alert("กรุณา Login เข้าสู่ระบบ"); location.href="login.php"';
	echo '</script>';
	exit;
}

// ดึงข้อมูลจาก Session
$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];

date_default_timezone_set("Asia/Bangkok");

// รับค่าจากฟอร์ม
$course = isset($_POST["course"]) ? $_POST["course"] : '';
$num_practice = isset($_POST["num_practice"]) ? $_POST["num_practice"] : 0;
$num_cwie = isset($_POST["num_cwie"]) ? $_POST["num_cwie"] : 0;
$num_pundit = isset($_POST["num_pundit"]) ? $_POST["num_pundit"] : 0;
$num_pundit_job = isset($_POST["num_pundit_job"]) ? $_POST["num_pundit_job"] : 0;
$num_pundit_job_work = isset($_POST["num_pundit_job_work"]) ? $_POST["num_pundit_job_work"] : 0;
$note = isset($_POST["note"]) ? $_POST["note"] : '';
$year = isset($_POST["year"]) ? $_POST["year"] : '';

// ตรวจสอบการบันทึกข้อมูลใหม่
if (isset($_POST['save'])) {
	// ตรวจสอบข้อมูลที่จำเป็น
	if (empty($course) || empty($year)) {
		echo '<script language="javascript">';
		echo 'alert("กรุณากรอกข้อมูลสาขาวิชาและปีการศึกษา"); history.back();';
		echo '</script>';
		exit;
	}

	// เพิ่มข้อมูลใหม่
	$sql = "INSERT INTO num_stu_cwie (faculty_id, major, num_practice, num_cwie, num_pundit, 
	num_pundit_job, num_pundit_job_work, note, date_regis, year) 
	VALUES ('$faculty_id', '$course', '$num_practice', '$num_cwie', '$num_pundit', '$num_pundit_job', 
	'$num_pundit_job_work', '$note', current_timestamp(), '$year')";

	if (mysqli_query($conn, $sql)) {
		echo '<script language="javascript">';
		echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="numStuSilAdd.php"';
		echo '</script>';
	} else {
		echo '<script language="javascript">';
		echo 'alert("เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . mysqli_error($conn) . '"); history.back();';
		echo '</script>';
	}
}
// ตรวจสอบการอัปเดตข้อมูล
elseif (isset($_POST['update'])) {
	// ตรวจสอบ ID ที่ต้องการอัปเดต
	$numStuid = isset($_GET["numStuid"]) ? intval($_GET["numStuid"]) : 0;

	if ($numStuid <= 0) {
		echo '<script language="javascript">';
		echo 'alert("ไม่พบข้อมูลที่ต้องการแก้ไข"); location.href="numStuSilAdd.php";';
		echo '</script>';
		exit;
	}

	// ตรวจสอบข้อมูลที่จำเป็น
	if (empty($course) || empty($year)) {
		echo '<script language="javascript">';
		echo 'alert("กรุณากรอกข้อมูลสาขาวิชาและปีการศึกษา"); history.back();';
		echo '</script>';
		exit;
	}

	// อัปเดตข้อมูล
	$sql = "UPDATE num_stu_cwie SET 
	major = '$course', 
	num_practice = '$num_practice', 
	num_cwie = '$num_cwie',
	num_pundit = '$num_pundit',
	num_pundit_job = '$num_pundit_job',
	num_pundit_job_work = '$num_pundit_job_work',
	note = '$note',
	year = '$year' 
	WHERE id = $numStuid AND faculty_id = '$faculty_id'";

	if (mysqli_query($conn, $sql)) {
		echo '<script language="javascript">';
		echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="numStuSilAdd.php"';
		echo '</script>';
	} else {
		echo '<script language="javascript">';
		echo 'alert("เกิดข้อผิดพลาดในการอัปเดตข้อมูล: ' . mysqli_error($conn) . '"); history.back();';
		echo '</script>';
	}
}
// ตรวจสอบว่ามีการส่งค่ามาจากฟอร์มหรือไม่
elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
	// กรณีอื่นๆ ที่เป็น POST แต่ไม่มีการกำหนด action
	echo '<script language="javascript">';
	echo 'alert("ไม่ได้ระบุการกระทำ"); location.href="numStuSilAdd.php"';
	echo '</script>';
} else {
	// กรณีเข้าถึงไฟล์โดยตรงโดยไม่ผ่านฟอร์ม
	echo '<script language="javascript">';
	echo 'alert("การเข้าถึงไม่ถูกต้อง"); location.href="numStuSilAdd.php"';
	echo '</script>';
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
