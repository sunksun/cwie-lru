<?php
session_start();
include_once('connect.php');

date_default_timezone_set("Asia/Bangkok");

$major = $_POST["major"];
$num_practice = $_POST["num_practice"];
$num_cwie = $_POST["num_cwie"];
$num_pundit = $_POST["num_pundit"];
$num_pundit_job = $_POST["num_pundit_job"];
$num_pundit_job_work = $_POST["num_pundit_job_work"];
$note = $_POST["note"];
$term = $_POST["term"];

if (isset($_POST['save'])) {
	$sql = "INSERT INTO num_stu_cwie (major, num_practice, num_cwie, num_pundit, 
	num_pundit_job, num_pundit_job_work, note, term, date_regis) 
	VALUES ('$major', '$num_practice', '$num_cwie', '$num_pundit', '$num_pundit_job', 
	'$num_pundit_job_work', '$note', '$term', current_timestamp());";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="numStuCwieAdd.php"';
	echo '</script>';
} elseif (isset($_POST['update'])){
	$numStuid = $_GET["numStuid"];
	$sql = "UPDATE num_stu_cwie SET major = '$major', 
	num_practice = '$num_practice', 
	num_cwie = '$num_cwie',
	num_pundit = '$num_pundit',
	num_pundit_job = '$num_pundit_job',
	num_pundit_job_work = '$num_pundit_job_work',
	note = '$note',
	term = '$term' 
	WHERE num_stu_cwie.id = $numStuid";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="numStuCwieAdd.php"';
	echo '</script>';
}

?>