<?php
session_start();
include_once('connect.php');

date_default_timezone_set("Asia/Bangkok");

$faculty = $_POST["faculty"];
$facid = $_GET["facid"];

if (isset($_POST['save'])) {
	$sql = "INSERT INTO faculty (faculty) VALUES ('$faculty')";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="facultyAdd.php"';
	echo '</script>';
} elseif (isset($_POST['update'])){
	$sql = "UPDATE faculty SET faculty = '$faculty' WHERE faculty.id = $facid";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="facultyAdd.php"';
	echo '</script>';
}

?>