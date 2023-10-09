<?php
session_start();
include_once('connect.php');

date_default_timezone_set("Asia/Bangkok");

$actid = $_GET["actid"];

// sql to delete a record
$sql = "DELETE FROM activity_cwie WHERE activity_cwie.id = $actid";

if ($conn->query($sql) === TRUE) {
	echo '<script language="javascript">';
	echo 'alert("ลบข้อมูลเรียบร้อยแล้ว"); location.href="activityCwieAdd.php"';
	echo '</script>';
} else {
  echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>