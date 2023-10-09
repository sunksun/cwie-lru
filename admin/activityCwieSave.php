<?php
session_start();
include_once('connect.php');
$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];

$target_dir = "img_act_cwie/";
date_default_timezone_set("Asia/Bangkok");
$newfilename = date('dmYHis');

$temp = explode(".", $_FILES["filename"]["name"]);
$newfilename = $newfilename . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


$activity_type = $_POST["activity_type"];
$activity_name = $_POST["activity_name"];
$course = $_POST["course"];
$activity_date = $_POST["activity_date"];
$amount = $_POST["amount"];
$note = $_POST["note"];

if (isset($_POST['save'])) {
	if($_FILES["filename"]["tmp_name"]=="") {
	$newfilename = "";
	$sql = "INSERT INTO activity_cwie (activity_type, activity_name, course, activity_date, amount, note, filename, date_regis) 
	VALUES ('$activity_type', '$activity_name', '$course', '$activity_date', '$amount', '$note', '$newfilename', current_timestamp());";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="activityCwieAdd.php"';
	echo '</script>';
	}
	// Check if image file is a actual image or fake image
	$check = getimagesize($_FILES["filename"]["tmp_name"]);
	if($check !== false) {
	  echo "File is an image - " . $check["mime"] . ".";
	  $uploadOk = 1;
	} else {
	  echo "File is not an image.";
	  $uploadOk = 0;
	}
	// Check if file already exists
	if (file_exists($target_file)) {
		echo "Sorry, file already exists.";
		$uploadOk = 0;
	}
	// Check file size
	if ($_FILES["filename"]["size"] > 500000) {
		echo '<script language="javascript">';
		echo 'alert("Sorry, your file is too large."); location.href="newsAdd.php"';
		echo '</script>';
		$uploadOk = 0;
	}
	
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
		//echo '<script language="javascript">';
		//echo 'alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed."); location.href="activityCwieAdd.php"';
		//echo '</script>';
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	if (move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)) {

	$sql = "INSERT INTO activity_cwie (activity_type, activity_name, course, activity_date, amount, note, filename, date_regis) 
	VALUES ('$activity_type', '$activity_name', '$course', '$activity_date', '$amount', '$note', '$newfilename', current_timestamp());";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="activityCwieAdd.php"';
	echo '</script>';
	} else {
		echo "Sorry, there was an error uploading your file.";
		}
	}

} elseif (isset($_POST['update'])){
	if($_FILES["filename"]["tmp_name"]=="") {
	$actid = $_GET["actid"];
	$sql = "UPDATE activity_cwie SET activity_type = '$activity_type',
	activity_name = '$activity_name',
	course = '$course',
	activity_date = '$activity_date',
	amount = '$amount',
	note = '$note' 
	WHERE activity_cwie.id = $actid";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="activityCwieAdd.php"';
	echo '</script>';
	}

	// Check if image file is a actual image or fake image
	$check = getimagesize($_FILES["filename"]["tmp_name"]);
	if($check !== false) {
	  echo "File is an image - " . $check["mime"] . ".";
	  $uploadOk = 1;
	} else {
	  echo "File is not an image.";
	  $uploadOk = 0;
	}
	// Check if file already exists
	if (file_exists($target_file)) {
		echo "Sorry, file already exists.";
		$uploadOk = 0;
	}
	// Check file size
	if ($_FILES["filename"]["size"] > 500000) {
		echo '<script language="javascript">';
		echo 'alert("Sorry, your file is too large."); location.href="activityCwieAdd.php"';
		echo '</script>';
		$uploadOk = 0;
	}
	
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
		//echo '<script language="javascript">';
		//echo 'alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed."); location.href="activityCwieAdd.php"';
		//echo '</script>';
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)) {
		$actid = $_GET["actid"];
		$sql = "UPDATE activity_cwie SET activity_type = '$activity_type',
		activity_name = '$activity_name',
		course = '$course',
		activity_date = '$activity_date',
		amount = '$amount',
		note = '$note' ,
		filename = '$newfilename' 
		WHERE activity_cwie.id = $actid";
		mysqli_query($conn, $sql);
		echo '<script language="javascript">';
		echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="activityCwieAdd.php"';
		echo '</script>';
		} else {
			echo "Sorry, there was an error uploading your file.";
			}
		}
}
?>