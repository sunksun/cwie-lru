<?php
session_start();
include_once('connect.php');

$jobid = $_GET["jobid"];
$sql = "SELECT * FROM job_cwie WHERE job_cwie.id = $jobid";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$img = $row["filename"];
$dir = "img_job";
unlink($dir . '/' . $img);

// sql to delete a record
$sql = "DELETE FROM job_cwie WHERE job_cwie.id = $jobid";

if ($conn->query($sql) === TRUE) {
    echo '<script language="javascript">';
    echo 'alert("ลบข้อมูลเรียบร้อยแล้ว"); location.href="jobAdd.php"';
    echo '</script>';
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
