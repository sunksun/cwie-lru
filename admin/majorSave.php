<?php
session_start();
include_once('connect.php');

date_default_timezone_set("Asia/Bangkok");

$faculty = $_POST["faculty"];
$major = $_POST["major"];
$majid = $_GET["majid"];

if (isset($_POST['save'])) {
	$sql = "INSERT INTO major (faculty, major, date_regis) VALUES ('$faculty', '$major', current_timestamp());";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="majorAdd.php"';
	echo '</script>';
} elseif (isset($_POST['update'])){
	$sql = "UPDATE major SET major = '$major' WHERE major.id = $majid";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="majorAdd.php"';
	echo '</script>';
}

?>