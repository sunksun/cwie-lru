<?php
session_start();
$user_img = $_SESSION['img'];
include_once('connect.php');
if ($_SESSION['fullname'] == '') {
	echo '<script language="javascript">';
	echo 'alert("กรุณา Login เข้าสู่ระบบ"); location.href="login.php"';
	echo '</script>';
	exit;
}
$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];
$year = "2/2566";

$target_dir = "img_teach/";
date_default_timezone_set("Asia/Bangkok");

$newfilename = date('dmYHis');
$numTeacid = $_GET["numTeacid"];
$temp = explode(".", $_FILES["filename"]["name"]);
$newfilename = $newfilename . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

$course = $_POST["course"];
$name_tea_cwie = $_POST["name_tea_cwie"];
$num_tea_cwie = $_POST["num_tea_cwie"];
$note = $_POST["note"];

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
		$sql = "INSERT INTO num_tea_cwie (faculty_id, course, name_tea_cwie, num_tea_cwie, note, filename, `date_regis`)
    VALUES ('$faculty_id','$course', '$name_tea_cwie', '$num_tea_cwie', '$note', '$newfilename', current_timestamp());";
		mysqli_query($conn, $sql);
		echo '<script language="javascript">';
		echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="numTeachCwieAdd.php"';
		echo '</script>';
		exit;
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
	if ($_FILES["filename"]["size"] > $maxFileSize) {
		echo '<script language="javascript">';
		echo 'alert("Sorry, your file is too large."); location.href="numTeachCwieAdd.php"';
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
			$resizeFile1 = $target_dir . '220x220_' . basename($newfilename);

			resizeImage($target_file, $resizeFile1, 220, 220);

			$sql = "INSERT INTO num_tea_cwie (faculty_id, course, name_tea_cwie, num_tea_cwie, note, filename, `date_regis`)
    VALUES ('$faculty_id','$course', '$name_tea_cwie', '$num_tea_cwie', '$note', '$newfilename', current_timestamp());";
			mysqli_query($conn, $sql);
			echo '<script language="javascript">';
			echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="numTeachCwieAdd.php"';
			echo '</script>';
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}
} elseif (isset($_POST['update'])) {
	if (isset($_GET["numTeacid"]) && !empty($_GET["numTeacid"])) {
		$numTeacid = $_GET["numTeacid"];

		if ($_FILES["filename"]["tmp_name"] == "") {
			// ไม่มีไฟล์ใหม่อัปโหลด
			$sql = "UPDATE num_tea_cwie SET course = '$course',
				name_tea_cwie = '$name_tea_cwie',
				num_tea_cwie = '$num_tea_cwie',
				note = '$note'
				WHERE id = $numTeacid";
			if (mysqli_query($conn, $sql)) {
				echo '<script language="javascript">';
				echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="numTeachCwieAdd.php"';
				echo '</script>';
			} else {
				echo "Error updating record: " . mysqli_error($conn);
			}
		} else {
			if (move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)) {
				$resizeFile1 = $target_dir . '220x220_' . basename($newfilename);

				resizeImage($target_file, $resizeFile1, 220, 220);

				$sql = "UPDATE num_tea_cwie SET course = '$course',
					name_tea_cwie = '$name_tea_cwie',
					num_tea_cwie = '$num_tea_cwie',
					note = '$note',
					filename = '$newfilename'
					WHERE id = $numTeacid";
				if (mysqli_query($conn, $sql)) {
					echo '<script language="javascript">';
					echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="numTeachCwieAdd.php"';
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
