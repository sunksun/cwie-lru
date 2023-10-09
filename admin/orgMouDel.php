<?php
session_start();
include_once('connect.php');

date_default_timezone_set("Asia/Bangkok");

$orgMouid = $_GET["orgMouid"];

// sql to delete a record
$sql = "DELETE FROM organization_mou WHERE id = $orgMouid";

if ($conn->query($sql) === TRUE) {
	echo '<script language="javascript">';
	echo 'alert("ลบข้อมูลเรียบร้อยแล้ว"); location.href="orgMouAdd.php"';
	echo '</script>';
} else {
  echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>