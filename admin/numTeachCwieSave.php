<?php
session_start();
include_once('connect.php');

date_default_timezone_set("Asia/Bangkok");

$course_fac = $_POST["course_fac"];
$course_fac = explode(".", $course_fac);
$course = $course_fac[0];
$id_faculty = $course_fac[1];


$name_tea_cwie = $_POST["name_tea_cwie"];
$num_tea_cwie = $_POST["num_tea_cwie"];
$note = $_POST["note"];

if (isset($_POST['save'])) {
	$sql = "INSERT INTO num_tea_cwie (course, name_tea_cwie, num_tea_cwie, id_faculty, note, `date_regis`)
	VALUES ('$course', '$name_tea_cwie', '$num_tea_cwie', '$id_faculty', '$note', current_timestamp());";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="numTeachCwieAdd.php"';
	echo '</script>';
} elseif (isset($_POST['update'])){
	$numTeacid = $_GET["numTeacid"];
	$sql = "UPDATE num_tea_cwie SET course = '$course',
	name_tea_cwie = '$name_tea_cwie',
	num_tea_cwie = '$num_tea_cwie',
	note = '$note'
	WHERE num_tea_cwie.id = $numTeacid";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="numTeachCwieAdd.php"';
	echo '</script>';
}

?>