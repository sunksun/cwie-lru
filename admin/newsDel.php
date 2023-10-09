<?php
session_start();
include_once('connect.php');

$newid = $_GET["newid"];
$sql = "SELECT * FROM news WHERE news.id = $newid";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$img = $row["img"];
$dir = "img_news";
unlink($dir.'/'.$img);

// sql to delete a record
$sql = "DELETE FROM news WHERE news.id = $newid";

if ($conn->query($sql) === TRUE) {
	echo '<script language="javascript">';
	echo 'alert("ลบข้อมูลเรียบร้อยแล้ว"); location.href="newsAdd.php"';
	echo '</script>';
} else {
  echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>