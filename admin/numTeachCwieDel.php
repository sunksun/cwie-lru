<?php
session_start();
include_once('connect.php');

date_default_timezone_set("Asia/Bangkok");

$numTeachid = $_GET["numTeachid"];

// sql to delete a record
$sql = "DELETE FROM num_tea_cwie WHERE num_tea_cwie.id = $numTeachid";

if ($conn->query($sql) === TRUE) {
	echo '<script language="javascript">';
	echo 'alert("ลบข้อมูลเรียบร้อยแล้ว"); location.href="numTeachCwieAdd.php"';
	echo '</script>';
} else {
  echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>