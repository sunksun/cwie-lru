<?php
// เปิดการแสดงข้อผิดพลาดทั้งหมด
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

// รับค่าปีการศึกษาจากฟอร์ม
$year = isset($_POST["year"]) ? $_POST["year"] : "2/2566";

$target_dir = "img_teach/";
date_default_timezone_set("Asia/Bangkok");

$newfilename = date('dmYHis');
$numTeacid = isset($_GET["numTeacid"]) ? $_GET["numTeacid"] : "";
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
		$sql = "INSERT INTO num_tea_cwie (faculty_id, course, name_tea_cwie, num_tea_cwie, note, filename, `date_regis`, year)
    VALUES ('$faculty_id','$course', '$name_tea_cwie', '$num_tea_cwie', '$note', '$newfilename', current_timestamp(), '$year');";
		mysqli_query($conn, $sql);
		echo '<script language="javascript">';
		echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="numTeachCwieAdd.php?year=' . $year . '"';
		echo '</script>';
		exit;
	}

	$check = getimagesize($_FILES["filename"]["tmp_name"]);
	if ($check !== false) {
		$uploadOk = 1;
	} else {
		echo '<script language="javascript">';
		echo 'alert("ไฟล์ที่อัพโหลดไม่ใช่รูปภาพ"); location.href="numTeachCwieAdd.php?year=' . $year . '"';
		echo '</script>';
		$uploadOk = 0;
		exit;
	}

	if (file_exists($target_file)) {
		echo '<script language="javascript">';
		echo 'alert("ไฟล์นี้มีอยู่แล้วในระบบ"); location.href="numTeachCwieAdd.php?year=' . $year . '"';
		echo '</script>';
		$uploadOk = 0;
		exit;
	}

	// Check file size
	$maxFileSize = 2 * 1024 * 1024; // 2 MB
	if ($_FILES["filename"]["size"] > $maxFileSize) {
		echo '<script language="javascript">';
		echo 'alert("ไฟล์ขนาดใหญ่เกินไป (ไม่เกิน 2MB)"); location.href="numTeachCwieAdd.php?year=' . $year . '"';
		echo '</script>';
		$uploadOk = 0;
		exit;
	}

	if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
		echo '<script language="javascript">';
		echo 'alert("รองรับเฉพาะไฟล์ JPG, JPEG, PNG และ GIF เท่านั้น"); location.href="numTeachCwieAdd.php?year=' . $year . '"';
		echo '</script>';
		$uploadOk = 0;
		exit;
	}

	if ($uploadOk == 0) {
		echo '<script language="javascript">';
		echo 'alert("เกิดข้อผิดพลาดในการอัพโหลดไฟล์"); location.href="numTeachCwieAdd.php?year=' . $year . '"';
		echo '</script>';
		exit;
	} else {
		if (move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)) {
			$resizeFile1 = $target_dir . '220x220_' . basename($newfilename);

			resizeImage($target_file, $resizeFile1, 220, 220);

			$sql = "INSERT INTO num_tea_cwie (faculty_id, course, name_tea_cwie, num_tea_cwie, note, filename, `date_regis`, year)
    VALUES ('$faculty_id','$course', '$name_tea_cwie', '$num_tea_cwie', '$note', '$newfilename', current_timestamp(), '$year');";
			mysqli_query($conn, $sql);
			echo '<script language="javascript">';
			echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="numTeachCwieAdd.php?year=' . $year . '"';
			echo '</script>';
		} else {
			echo '<script language="javascript">';
			echo 'alert("เกิดข้อผิดพลาดในการอัพโหลดไฟล์"); location.href="numTeachCwieAdd.php?year=' . $year . '"';
			echo '</script>';
			exit;
		}
	}
} elseif (isset($_POST['update'])) {
	if (isset($_GET["numTeacid"]) && !empty($_GET["numTeacid"])) {
		$numTeacid = $_GET["numTeacid"];

		if ($_FILES["filename"]["tmp_name"] == "") {
			// ไม่มีไฟล์ใหม่อัปโหลด
			$sql = "UPDATE num_tea_cwie SET 
				course = '$course',
				name_tea_cwie = '$name_tea_cwie',
				num_tea_cwie = '$num_tea_cwie',
				note = '$note',
				year = '$year'
				WHERE id = $numTeacid";
			if (mysqli_query($conn, $sql)) {
				echo '<script language="javascript">';
				echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="numTeachCwieAdd.php?year=' . $year . '"';
				echo '</script>';
			} else {
				echo '<script language="javascript">';
				echo 'alert("เกิดข้อผิดพลาดในการอัปเดทข้อมูล: ' . mysqli_error($conn) . '"); location.href="numTeachCwieAdd.php?year=' . $year . '"';
				echo '</script>';
				exit;
			}
		} else {
			// ตรวจสอบรูปภาพใหม่
			$check = getimagesize($_FILES["filename"]["tmp_name"]);
			if ($check === false) {
				echo '<script language="javascript">';
				echo 'alert("ไฟล์ที่อัพโหลดไม่ใช่รูปภาพ"); location.href="numTeachCwieEdit.php?numTeacid=' . $numTeacid . '&year=' . $year . '"';
				echo '</script>';
				exit;
			}

			// ตรวจสอบขนาดไฟล์
			$maxFileSize = 2 * 1024 * 1024; // 2 MB
			if ($_FILES["filename"]["size"] > $maxFileSize) {
				echo '<script language="javascript">';
				echo 'alert("ไฟล์ขนาดใหญ่เกินไป (ไม่เกิน 2MB)"); location.href="numTeachCwieEdit.php?numTeacid=' . $numTeacid . '&year=' . $year . '"';
				echo '</script>';
				exit;
			}

			// ตรวจสอบชนิดไฟล์
			if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
				echo '<script language="javascript">';
				echo 'alert("รองรับเฉพาะไฟล์ JPG, JPEG, PNG และ GIF เท่านั้น"); location.href="numTeachCwieEdit.php?numTeacid=' . $numTeacid . '&year=' . $year . '"';
				echo '</script>';
				exit;
			}

			if (move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)) {
				$resizeFile1 = $target_dir . '220x220_' . basename($newfilename);

				resizeImage($target_file, $resizeFile1, 220, 220);

				$sql = "UPDATE num_tea_cwie SET 
					course = '$course',
					name_tea_cwie = '$name_tea_cwie',
					num_tea_cwie = '$num_tea_cwie',
					note = '$note',
					filename = '$newfilename',
					year = '$year'
					WHERE id = $numTeacid";
				if (mysqli_query($conn, $sql)) {
					echo '<script language="javascript">';
					echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="numTeachCwieAdd.php?year=' . $year . '"';
					echo '</script>';
				} else {
					echo '<script language="javascript">';
					echo 'alert("เกิดข้อผิดพลาดในการอัปเดทข้อมูล: ' . mysqli_error($conn) . '"); location.href="numTeachCwieEdit.php?numTeacid=' . $numTeacid . '&year=' . $year . '"';
					echo '</script>';
					exit;
				}
			} else {
				echo '<script language="javascript">';
				echo 'alert("เกิดข้อผิดพลาดในการอัพโหลดไฟล์"); location.href="numTeachCwieEdit.php?numTeacid=' . $numTeacid . '&year=' . $year . '"';
				echo '</script>';
				exit;
			}
		}
	} else {
		echo '<script language="javascript">';
		echo 'alert("ไม่พบข้อมูลที่ต้องการแก้ไข"); location.href="numTeachCwieAdd.php?year=' . $year . '"';
		echo '</script>';
		exit;
	}
}
