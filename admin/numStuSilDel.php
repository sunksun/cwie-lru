<?php
session_start();
include_once('connect.php');

date_default_timezone_set("Asia/Bangkok");

$numStuid = $_GET["numStuid"];

// sql to delete a record
$sql = "DELETE FROM num_stu_cwie WHERE num_stu_cwie.id = $numStuid";

if ($conn->query($sql) === TRUE) {
	echo '<script language="javascript">';
	echo 'alert("ลบข้อมูลเรียบร้อยแล้ว"); location.href="numStuSilAdd.php"';
	echo '</script>';
} else {
	echo "Error deleting record: " . $conn->error;
}

$conn->close();
