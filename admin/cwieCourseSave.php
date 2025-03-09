<?php
// เปิดการแสดงข้อผิดพลาดทั้งหมด
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['fullname']) || empty($_SESSION['fullname'])) {
	echo '<script language="javascript">';
	echo 'alert("กรุณา Login เข้าสู่ระบบ"); location.href="login.php"';
	echo '</script>';
	exit;
}

$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];
include_once('connect.php');

date_default_timezone_set("Asia/Bangkok");

// รับค่าจากฟอร์ม
$course = isset($_POST["course"]) ? $_POST["course"] : "";
$type = isset($_POST["type"]) ? $_POST["type"] : "";
$note = isset($_POST["note"]) ? $_POST["note"] : "";
$year = isset($_POST["year"]) ? $_POST["year"] : "";
$cwieCoid = isset($_GET["cwieCoid"]) ? intval($_GET["cwieCoid"]) : 0;

// กำหนดค่าเริ่มต้น
$separate = "";
$parallel = "";
$mix = "";

// กำหนดค่าตามประเภทที่เลือก
if ($type == "Separate") {
	$separate = "/";
} elseif ($type == "Parallel") {
	$parallel = "/";
} elseif ($type == "Mix") {
	$mix = "/";
}

if (isset($_POST['save'])) {
	// เพิ่มข้อมูลใหม่
	$sql = "INSERT INTO cwie_course (faculty_id, major, separate, parallel, mix, note, date_regis, year) VALUES 
    (?, ?, ?, ?, ?, ?, current_timestamp(), ?)";

	$stmt = mysqli_prepare($conn, $sql);
	mysqli_stmt_bind_param($stmt, "sssssss", $faculty_id, $course, $separate, $parallel, $mix, $note, $year);

	if (mysqli_stmt_execute($stmt)) {
		echo '<script language="javascript">';
		echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="cwieCourseAdd.php"';
		echo '</script>';
	} else {
		echo '<script language="javascript">';
		echo 'alert("เกิดข้อผิดพลาดในการบันทึก: ' . mysqli_error($conn) . '"); location.href="cwieCourseAdd.php"';
		echo '</script>';
	}
	mysqli_stmt_close($stmt);
} elseif (isset($_POST['update']) && $cwieCoid > 0) {
	// อัปเดตข้อมูล
	$sql = "UPDATE cwie_course SET major = ?, 
    separate = ?, 
    parallel = ?, 
    mix = ?, 
    note = ?, 
    year = ? 
    WHERE id = ?";

	$stmt = mysqli_prepare($conn, $sql);
	mysqli_stmt_bind_param($stmt, "ssssssi", $course, $separate, $parallel, $mix, $note, $year, $cwieCoid);

	if (mysqli_stmt_execute($stmt)) {
		echo '<script language="javascript">';
		echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="cwieCourseAdd.php"';
		echo '</script>';
	} else {
		echo '<script language="javascript">';
		echo 'alert("เกิดข้อผิดพลาดในการอัปเดต: ' . mysqli_error($conn) . '"); location.href="cwieCourseAdd.php"';
		echo '</script>';
	}
	mysqli_stmt_close($stmt);
}
