<?php
session_start();
include_once('connect.php');

$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];

$target_dir = "img_news/";
date_default_timezone_set("Asia/Bangkok");
$newfilename = date('dmYHis');

$temp = explode(".", $_FILES["fileToUpload"]["name"]);
$newfilename = $newfilename . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

$title = $_POST["title"];
$date1 = $_POST["date1"];
$date2 = $_POST["date2"];
$date3 = $_POST["date3"];
$mou_year = "$date2 $date3";

$detail = $_POST["detail"];
$detail2 = $_POST["detail2"];
$post_by = $fullname;

function resizeImage($file, $width, $height, $output)
{
	list($originalWidth, $originalHeight) = getimagesize($file);
	$ratio = $originalWidth / $originalHeight;

	if ($width / $height > $ratio) {
		$newWidth = $height * $ratio;
		$newHeight = $height;
	} else {
		$newHeight = $width / $ratio;
		$newWidth = $width;
	}

	$src = imagecreatefromjpeg($file);
	$dst = imagecreatetruecolor($newWidth, $newHeight);
	imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
	imagejpeg($dst, $output);
	imagedestroy($src);
	imagedestroy($dst);
}

if (isset($_POST['save'])) {
	// Check if image file is a actual image or fake image
	$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	if ($check !== false) {
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
	$maxFileSize = 2 * 1024 * 1024; // 2 MB
	if ($_FILES["fileToUpload"]["size"] > $maxFileSize) {
		echo '<script language="javascript">';
		echo 'alert("Sorry, your file is too large."); location.href="newsAdd.php"';
		echo '</script>';
		$uploadOk = 0;
	}


	// Allow certain file formats
	if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
		$uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			// Resize and save the images
			resizeImage($target_file, 370, 360, $target_dir . '370x360_' . $newfilename);
			resizeImage($target_file, 1024, 683, $target_dir . '1024x683_' . $newfilename);

			$sql = "INSERT INTO news (title, date1, mou_year, detail, detail2, img, post_by) 
            VALUES ('$title', '$date1', '$mou_year', '$detail', '$detail2', '$newfilename', '$post_by')";
			mysqli_query($conn, $sql);
			echo '<script language="javascript">';
			echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="newsAdd.php"';
			echo '</script>';
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}
} elseif (isset($_POST['update'])) {
	if ($_FILES["fileToUpload"]["tmp_name"] == "") {
		$newid = $_GET["newid"];
		$sql = "UPDATE news SET title = '$title', detail = '$detail', post_by = '$post_by' WHERE news.id = $newid";
		mysqli_query($conn, $sql);
		echo '<script language="javascript">';
		echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="newsAdd.php"';
		echo '</script>';
	}

	// Check if image file is a actual image or fake image
	$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	if ($check !== false) {
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
	$maxFileSize = 2 * 1024 * 1024; // 2 MB
	if ($_FILES["fileToUpload"]["size"] > $maxFileSize) {
		echo '<script language="javascript">';
		echo 'alert("Sorry, your file is too large."); location.href="newsAdd.php"';
		echo '</script>';
		$uploadOk = 0;
	}

	// Allow certain file formats
	if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
		$uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			$newid = $_GET["newid"];

			$sql = "SELECT * FROM news WHERE news.id = $newid";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			$img = $row["img"];
			$dir = "img_news";
			unlink($dir . '/' . $img);

			// Resize and save the images
			resizeImage($target_file, 370, 360, $target_dir . '370x360_' . $newfilename);
			resizeImage($target_file, 1024, 683, $target_dir . '1024x683_' . $newfilename);

			$sql = "UPDATE news SET title = '$title', detail = '$detail', img = '$newfilename', post_by = '$post_by' WHERE news.id = $newid";
			mysqli_query($conn, $sql);
			echo '<script language="javascript">';
			echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="newsAdd.php"';
			echo '</script>';
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}
}
