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
	VALUES ('$faculty_id', '$course', '$num_practice', '$num_cwie', '$num_pundit', '$num_pundit_job', 
	'$num_pundit_job_work', '$note', current_timestamp(), '$year');";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="numStuSilAdd.php"';
	echo '</script>';
} elseif (isset($_POST['update'])) {
	$numStuid = $_GET["numStuid"];
	$sql = "UPDATE num_stu_cwie SET major = '$course', 
	num_practice = '$num_practice', 
	num_cwie = '$num_cwie',
	num_pundit = '$num_pundit',
	num_pundit_job = '$num_pundit_job',
	num_pundit_job_work = '$num_pundit_job_work',
	note = '$note',
	year = '$year' 
	WHERE num_stu_cwie.id = $numStuid";
	echo $sql;
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="numStuSilAdd.php"';
	echo '</script>';
}
