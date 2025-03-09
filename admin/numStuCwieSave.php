<?php
error_reporting(~E_NOTICE);
session_start();
include_once('connect.php');
$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];

date_default_timezone_set("Asia/Bangkok");

$course = $_POST["course"];
$num_practice = $_POST["num_practice"];
$num_cwie = $_POST["num_cwie"];
$num_pundit = $_POST["num_pundit"];
$num_pundit_job = $_POST["num_pundit_job"];
$num_pundit_job_work = $_POST["num_pundit_job_work"];
$note = $_POST["note"];
$year = $_POST["year"];

if (isset($_POST['save'])) {
	$sql = "INSERT INTO num_stu_cwie (faculty_id, major, num_practice, num_cwie, num_pundit, 
num_pundit_job, num_pundit_job_work, note, date_regis, year) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, current_timestamp(), ?)";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param(
		"sssssssss",
		$faculty_id,
		$course,
		$num_practice,
		$num_cwie,
		$num_pundit,
		$num_pundit_job,
		$num_pundit_job_work,
		$note,
		$year
	);
	$stmt->execute();
	echo '<script language="javascript">';
	echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="numStuCwieAdd.php"';
	echo '</script>';
} elseif (isset($_POST['update'])) {
	$numStuid = intval($_GET["numStuid"]);

	// ตรวจสอบว่า numStuid เป็นของ faculty นี้จริงหรือไม่
	$check_sql = "SELECT id FROM num_stu_cwie WHERE id = ? AND faculty_id = ?";
	$check_stmt = $conn->prepare($check_sql);
	$check_stmt->bind_param("is", $numStuid, $faculty_id);
	$check_stmt->execute();
	$check_result = $check_stmt->get_result();

	if ($check_result->num_rows == 0) {
		echo '<script language="javascript">';
		echo 'alert("ไม่พบข้อมูลที่ต้องการแก้ไข"); location.href="numStuCwieAdd.php"';
		echo '</script>';
		exit;
	}

	// อัปเดตข้อมูล
	$sql = "UPDATE num_stu_cwie SET 
        major = ?, 
        num_practice = ?, 
        num_cwie = ?,
        num_pundit = ?,
        num_pundit_job = ?,
        num_pundit_job_work = ?,
        note = ?,
        year = ? 
        WHERE id = ? AND faculty_id = ?";

	$stmt = $conn->prepare($sql);
	$stmt->bind_param(
		"ssssssssis",
		$course,
		$num_practice,
		$num_cwie,
		$num_pundit,
		$num_pundit_job,
		$num_pundit_job_work,
		$note,
		$year,
		$numStuid,
		$faculty_id
	);

	if ($stmt->execute()) {
		echo '<script language="javascript">';
		echo 'alert("อัปเดตข้อมูลเรียบร้อยแล้ว"); location.href="numStuCwieAdd.php"';
		echo '</script>';
	} else {
		echo '<script language="javascript">';
		echo 'alert("เกิดข้อผิดพลาด: ' . $conn->error . '"); location.href="numStuCwieAdd.php"';
		echo '</script>';
	}
}
