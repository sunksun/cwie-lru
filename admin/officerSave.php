<?php
session_start();
include_once('connect.php');
$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$position = $_SESSION['position'];

$target_dir = "img_officer/";
date_default_timezone_set("Asia/Bangkok");
$newfilename = date('dmYHis');

$temp = explode(".", $_FILES["fileToUpload"]["name"]);
$newfilename = $newfilename . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

$fid = $_POST["fid"];
$fullname = $_POST["fullname"];
$position = $_POST["position"];
$useremail = $_POST["useremail"];
$username = $_POST["username"];
$password = md5($_POST["password"]);
$post_by = $fullname;

$sql = "SELECT * FROM faculty WHERE fid = '$fid'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$faculty = $row["faculty"];

if (isset($_POST['save'])) {
	if($_FILES["fileToUpload"]["tmp_name"]=="") {
	$newfilename = "";
	$pass_update = "0";
	$sql = "INSERT INTO tblusers (fullname, username, useremail, faculty, position, 
	password, regdate, img, faculty_id, pass_update) VALUES 
	('$fullname', '$username', '$useremail', '$faculty', '$position', '$password', 
	current_timestamp(), '', '$fid', '$pass_update');";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="officerAdd.php"';
	echo '</script>';
	}
	// Check if image file is a actual image or fake image
	$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
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
	if ($_FILES["fileToUpload"]["size"] > 500000) {
		echo '<script language="javascript">';
		echo 'alert("Sorry, your file is too large."); location.href="officerAdd.php"';
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
	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	$pass_update = "0";
	$sql = "INSERT INTO tblusers (fullname, username, useremail, faculty, position, 
	password, regdate, img, faculty_id, pass_update) VALUES 
	('$fullname', '$username', '$useremail', '$faculty', '$position', '$password', 
	current_timestamp(), '$newfilename', '$fid', '$pass_update');";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="officerAdd.php"';
	echo '</script>';
	} else {
		echo "Sorry, there was an error uploading your file.";
		}
	}

} elseif (isset($_POST['update'])){
	if($_FILES["fileToUpload"]["tmp_name"]=="") {
	$offid = $_GET["offid"];
	$sql = "UPDATE tblusers SET fullname = '$fullname', 
	username = '$username', 
	useremail = '$useremail', 
	faculty = '$faculty', 
	position = '$position', 
	password = '$password', 
	faculty_id = '$fid'
	WHERE tblusers.id = $offid";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="officerAdd.php"';
	echo '</script>';
	}

	// Check if image file is a actual image or fake image
	$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
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
	if ($_FILES["fileToUpload"]["size"] > 500000) {
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
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		$offid = $_GET["offid"];

		$sql = "SELECT * FROM tblusers WHERE tblusers.id = $offid";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$img = $row["img"];
		$dir = "img_officer";
		unlink($dir.'/'.$img);

		$sql = "UPDATE tblusers SET fullname = '$fullname', 
		username = '$username', 
		useremail = '$useremail', 
		faculty = '$faculty', 
		position = '$position', 
		password = '$password', 
		img = '$newfilename', 
		faculty_id = '$fid'
		WHERE tblusers.id = $offid";
		mysqli_query($conn, $sql);
		echo '<script language="javascript">';
		echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="officerAdd.php"';
		echo '</script>';
		} else {
			echo "Sorry, there was an error uploading your file.";
			}
		}
}
?>