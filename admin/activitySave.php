<?php
session_start();
include_once('connect.php');
$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];

$target_dir = "img_act/";
date_default_timezone_set("Asia/Bangkok");
$newfilename = date('dmYHis');
$actid = $_GET["actid"];
$temp = explode(".", $_FILES["filename"]["name"]);
$newfilename = $newfilename . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

$activity_type = $_POST["activity_type"];
$activity_name = $_POST["activity_name"];
$date1 = $_POST["date1"];
$date2 = $_POST["date2"];
$date3 = $_POST["date3"];
$date = "$date1 $date2 $date3";

$details = $_POST["details"];

$sql = "SELECT * FROM activity_type WHERE activity_type = '$activity_type'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	$optionData = $result->fetch_assoc();
	$id = $optionData['id'];
}
$sql = "SELECT * FROM activity WHERE activity_type = '$activity_type'";
$result = $conn->query($sql);
if ($result) {
	$row = mysqli_num_rows($result);
	mysqli_free_result($result);
}
function getSequence($num)
{
	return sprintf("%'.05d\n", $num);
}
$num = $row + 1;
$gnum = getSequence($num);
$activity_id = "$id$gnum";

function resizeImage($source, $destination, $width, $height)
{
	list($origWidth, $origHeight) = getimagesize($source);
	$aspectRatio = $origWidth / $origHeight;
	if ($width / $height > $aspectRatio) {
		$newWidth = $height * $aspectRatio;
		$newHeight = $height;
	} else {
		$newHeight = $width / $aspectRatio;
		$newWidth = $width;
	}
	$image_p = imagecreatetruecolor($newWidth, $newHeight);
	$image = imagecreatefromjpeg($source);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
	imagejpeg($image_p, $destination, 100);
}

if (isset($_POST['save'])) {
	if ($_FILES["filename"]["tmp_name"] == "") {
		$newfilename = "";
		$sql = "INSERT INTO activity (activity_type, activity_id, activity_name, activity_date, 
        details, filename, date_regis) VALUES ('$activity_type', '$activity_id', '$activity_name', 
        '$date', '$details', '$newfilename', current_timestamp());";
		mysqli_query($conn, $sql);
		echo '<script language="javascript">';
		echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="activityAdd.php"';
		echo '</script>';
	}

	$check = getimagesize($_FILES["filename"]["tmp_name"]);
	if ($check !== false) {
		$uploadOk = 1;
	} else {
		echo "File is not an image.";
		$uploadOk = 0;
	}

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

	if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
		$uploadOk = 0;
	}

	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
	} else {
		if (move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)) {
			$resizeFile1 = $target_dir . '270x270_' . basename($newfilename);
			$resizeFile2 = $target_dir . '1024x683_' . basename($newfilename);

			resizeImage($target_file, $resizeFile1, 270, 270);
			resizeImage($target_file, $resizeFile2, 1024, 683);

			$sql = "INSERT INTO activity (activity_type, activity_id, activity_name, activity_date, 
            details, filename, date_regis) VALUES ('$activity_type', '$activity_id', '$activity_name', 
            '$date', '$details', '$newfilename', current_timestamp());";
			mysqli_query($conn, $sql);
			echo '<script language="javascript">';
			echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="activityAdd.php"';
			echo '</script>';
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}
} elseif (isset($_POST['update'])) {
	if (isset($_GET["actid"]) && !empty($_GET["actid"])) {
		$actid = $_GET["actid"];

		if ($_FILES["filename"]["tmp_name"] == "") {
			// ไม่มีไฟล์ใหม่อัปโหลด
			$sql = "UPDATE activity SET activity_type = '$activity_type',
                activity_name = '$activity_name',
                activity_date = '$date',
                details = '$details'
                WHERE id = $actid";
			if (mysqli_query($conn, $sql)) {
				echo '<script language="javascript">';
				echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="activityAdd.php"';
				echo '</script>';
			} else {
				echo "Error updating record: " . mysqli_error($conn);
			}
		} else {
			// มีการอัปโหลดไฟล์ใหม่
			if (move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)) {
				$resizeFile1 = $target_dir . '270x270_' . basename($newfilename);
				$resizeFile2 = $target_dir . '1024x683_' . basename($newfilename);

				resizeImage($target_file, $resizeFile1, 270, 270);
				resizeImage($target_file, $resizeFile2, 1024, 683);

				$sql = "UPDATE activity SET activity_type = '$activity_type',
                    activity_name = '$activity_name',
                    activity_date = '$date',
                    details = '$details',
                    filename = '$newfilename'
                    WHERE id = $actid";
				if (mysqli_query($conn, $sql)) {
					echo '<script language="javascript">';
					echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="activityAdd.php"';
					echo '</script>';
				} else {
					echo "Error updating record: " . mysqli_error($conn);
				}
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
		}
	} else {
		echo "Error: No activity ID provided.";
	}
}
