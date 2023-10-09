<?php
session_start();
include_once('connect.php');

$offid = $_GET["offid"];
$sql = "SELECT * FROM tblusers WHERE tblusers.id = $offid";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$img = $row["img"];
$dir = "img_officer";
unlink($dir.'/'.$img);

// sql to delete a record
$sql = "DELETE FROM tblusers WHERE tblusers.id = $offid";

if ($conn->query($sql) === TRUE) {
	echo '<script language="javascript">';
	echo 'alert("ลบข้อมูลเรียบร้อยแล้ว"); location.href="officerAdd.php"';
	echo '</script>';
} else {
  echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>