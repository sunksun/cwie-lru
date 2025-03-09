<?php
// เปิดการแสดงข้อผิดพลาดทั้งหมด
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include_once('connect.php');

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
	exit();
}

$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];

// ตั้งค่าไดเรกทอรีสำหรับเก็บรูปภาพ
$target_dir = "img_news/";
date_default_timezone_set("Asia/Bangkok");
$newfilename = date('dmYHis');

// รับค่าจากฟอร์ม
$title = mysqli_real_escape_string($conn, $_POST["title"]);
$date1 = mysqli_real_escape_string($conn, $_POST["date1"]);
$date2 = mysqli_real_escape_string($conn, $_POST["date2"]);
$date3 = mysqli_real_escape_string($conn, $_POST["date3"]);
$mou_year = "$date2 $date3";
$detail = mysqli_real_escape_string($conn, $_POST["detail"]);
$detail2 = mysqli_real_escape_string($conn, $_POST["detail2"]);
$post_by = $fullname;

// ฟังก์ชันปรับขนาดรูปภาพที่รองรับหลายประเภทไฟล์
function resizeImage($file, $width, $height, $output)
{
	// ตรวจสอบประเภทของไฟล์
	$imageInfo = getimagesize($file);
	if ($imageInfo === false) {
		throw new Exception("ไม่สามารถอ่านข้อมูลรูปภาพได้");
	}

	list($originalWidth, $originalHeight) = $imageInfo;
	$ratio = $originalWidth / $originalHeight;

	// คำนวณขนาดใหม่โดยรักษาอัตราส่วน
	if ($width / $height > $ratio) {
		$newWidth = intval($height * $ratio);
		$newHeight = $height;
	} else {
		$newHeight = intval($width / $ratio);
		$newWidth = $width;
	}

	// สร้างรูปภาพตามประเภทของไฟล์
	$mimeType = $imageInfo['mime'];
	switch ($mimeType) {
		case 'image/jpeg':
			$src = imagecreatefromjpeg($file);
			break;
		case 'image/png':
			$src = imagecreatefrompng($file);
			break;
		case 'image/gif':
			$src = imagecreatefromgif($file);
			break;
		default:
			throw new Exception("ไม่รองรับประเภทไฟล์ $mimeType");
	}

	if (!$src) {
		throw new Exception("ไม่สามารถสร้างรูปภาพจากไฟล์ต้นฉบับได้");
	}

	// สร้างรูปภาพปลายทาง
	$dst = imagecreatetruecolor($newWidth, $newHeight);

	// รักษาความโปร่งใสสำหรับ PNG และ GIF
	if ($mimeType == 'image/png' || $mimeType == 'image/gif') {
		imagealphablending($dst, false);
		imagesavealpha($dst, true);
		$transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
		imagefilledrectangle($dst, 0, 0, $newWidth, $newHeight, $transparent);
	}

	// ปรับขนาดรูปภาพ
	imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

	// บันทึกรูปภาพตามนามสกุลของไฟล์เป้าหมาย
	$outputExt = strtolower(pathinfo($output, PATHINFO_EXTENSION));
	switch ($outputExt) {
		case 'jpg':
		case 'jpeg':
			imagejpeg($dst, $output, 90);
			break;
		case 'png':
			imagepng($dst, $output, 9);
			break;
		case 'gif':
			imagegif($dst, $output);
			break;
		default:
			// ถ้าไม่ระบุนามสกุลหรือไม่รองรับ ให้บันทึกเป็น JPEG
			imagejpeg($dst, $output, 90);
	}

	// คืนหน่วยความจำ
	imagedestroy($src);
	imagedestroy($dst);
}

// การบันทึกข้อมูลใหม่
if (isset($_POST['save'])) {
	if (!isset($_FILES["fileToUpload"]) || $_FILES["fileToUpload"]["error"] != 0) {
		echo '<script language="javascript">';
		echo 'alert("กรุณาเลือกไฟล์รูปภาพ"); window.location="newsAdd.php";';
		echo '</script>';
		exit();
	}

	$temp = explode(".", $_FILES["fileToUpload"]["name"]);
	$newfilename = $newfilename . '.' . end($temp);
	$target_file = $target_dir . basename($newfilename);
	$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
	$uploadOk = 1;

	// ตรวจสอบว่าเป็นไฟล์รูปภาพจริงหรือไม่
	$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	if ($check === false) {
		echo '<script language="javascript">';
		echo 'alert("ไฟล์ที่อัปโหลดไม่ใช่รูปภาพ"); window.location="newsAdd.php";';
		echo '</script>';
		exit();
	}

	// ตรวจสอบขนาดไฟล์
	$maxFileSize = 2 * 1024 * 1024; // 2 MB
	if ($_FILES["fileToUpload"]["size"] > $maxFileSize) {
		echo '<script language="javascript">';
		echo 'alert("ขนาดไฟล์ใหญ่เกินไป กรุณาอัปโหลดไฟล์ขนาดไม่เกิน 2MB"); window.location="newsAdd.php";';
		echo '</script>';
		exit();
	}

	// ตรวจสอบประเภทไฟล์
	$allowedTypes = ["jpg", "jpeg", "png", "gif"];
	if (!in_array($imageFileType, $allowedTypes)) {
		echo '<script language="javascript">';
		echo 'alert("รองรับเฉพาะไฟล์ JPG, JPEG, PNG และ GIF เท่านั้น"); window.location="newsAdd.php";';
		echo '</script>';
		exit();
	}

	// อัปโหลดไฟล์
	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		// ปรับขนาดรูปภาพ
		try {
			resizeImage($target_file, 370, 360, $target_dir . '370x360_' . $newfilename);
			resizeImage($target_file, 1024, 683, $target_dir . '1024x683_' . $newfilename);
		} catch (Exception $e) {
			echo '<script language="javascript">';
			echo 'alert("เกิดข้อผิดพลาดในการปรับขนาดรูปภาพ: ' . $e->getMessage() . '"); window.location="newsAdd.php";';
			echo '</script>';
			exit();
		}

		// บันทึกข้อมูลลงฐานข้อมูล
		$sql = "INSERT INTO news (title, date1, mou_year, detail, detail2, img, post_by, faculty_id) 
                VALUES ('$title', '$date1', '$mou_year', '$detail', '$detail2', '$newfilename', '$post_by', '$faculty_id')";

		if (mysqli_query($conn, $sql)) {
			echo '<script language="javascript">';
			echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); window.location="newsAdd.php";';
			echo '</script>';
		} else {
			echo '<script language="javascript">';
			echo 'alert("เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . mysqli_error($conn) . '"); window.location="newsAdd.php";';
			echo '</script>';
		}
	} else {
		echo '<script language="javascript">';
		echo 'alert("เกิดข้อผิดพลาดในการอัปโหลดไฟล์"); window.location="newsAdd.php";';
		echo '</script>';
	}
}
// การอัปเดตข้อมูล
elseif (isset($_POST['update'])) {
	$newid = isset($_GET["newid"]) ? intval($_GET["newid"]) : 0;

	if ($newid <= 0) {
		echo '<script language="javascript">';
		echo 'alert("รหัสข่าวไม่ถูกต้อง"); window.location="newsAdd.php";';
		echo '</script>';
		exit();
	}

	// ตรวจสอบว่ามีการอัปโหลดไฟล์ใหม่หรือไม่
	if (!isset($_FILES["fileToUpload"]) || $_FILES["fileToUpload"]["error"] == 4) {
		// ไม่มีการอัปโหลดไฟล์ใหม่ อัปเดตเฉพาะข้อมูลข้อความ
		$sql = "UPDATE news SET 
                title = '$title', 
                date1 = '$date1', 
                mou_year = '$mou_year', 
                detail = '$detail', 
                detail2 = '$detail2', 
                post_by = '$post_by', 
                faculty_id = '$faculty_id' 
                WHERE id = $newid";

		if (mysqli_query($conn, $sql)) {
			echo '<script language="javascript">';
			echo 'alert("อัปเดตข้อมูลเรียบร้อยแล้ว"); window.location="newsAdd.php";';
			echo '</script>';
		} else {
			echo '<script language="javascript">';
			echo 'alert("เกิดข้อผิดพลาดในการอัปเดตข้อมูล: ' . mysqli_error($conn) . '"); window.location="newsAdd.php";';
			echo '</script>';
		}
		exit();
	}

	// มีการอัปโหลดไฟล์ใหม่
	$temp = explode(".", $_FILES["fileToUpload"]["name"]);
	$newfilename = $newfilename . '.' . end($temp);
	$target_file = $target_dir . basename($newfilename);
	$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
	$uploadOk = 1;

	// ตรวจสอบว่าเป็นไฟล์รูปภาพจริงหรือไม่
	$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	if ($check === false) {
		echo '<script language="javascript">';
		echo 'alert("ไฟล์ที่อัปโหลดไม่ใช่รูปภาพ"); window.location="newsAdd.php";';
		echo '</script>';
		exit();
	}

	// ตรวจสอบขนาดไฟล์
	$maxFileSize = 2 * 1024 * 1024; // 2 MB
	if ($_FILES["fileToUpload"]["size"] > $maxFileSize) {
		echo '<script language="javascript">';
		echo 'alert("ขนาดไฟล์ใหญ่เกินไป กรุณาอัปโหลดไฟล์ขนาดไม่เกิน 2MB"); window.location="newsAdd.php";';
		echo '</script>';
		exit();
	}

	// ตรวจสอบประเภทไฟล์
	$allowedTypes = ["jpg", "jpeg", "png", "gif"];
	if (!in_array($imageFileType, $allowedTypes)) {
		echo '<script language="javascript">';
		echo 'alert("รองรับเฉพาะไฟล์ JPG, JPEG, PNG และ GIF เท่านั้น"); window.location="newsAdd.php";';
		echo '</script>';
		exit();
	}

	// ดึงข้อมูลรูปภาพเดิม
	$sql = "SELECT img FROM news WHERE id = $newid";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$oldImage = $row["img"];

		// ลบไฟล์รูปภาพเดิม
		if (!empty($oldImage)) {
			$oldImagePath = $target_dir . $oldImage;
			$oldImagePath370 = $target_dir . '370x360_' . $oldImage;
			$oldImagePath1024 = $target_dir . '1024x683_' . $oldImage;

			if (file_exists($oldImagePath)) {
				unlink($oldImagePath);
			}
			if (file_exists($oldImagePath370)) {
				unlink($oldImagePath370);
			}
			if (file_exists($oldImagePath1024)) {
				unlink($oldImagePath1024);
			}
		}
	}

	// อัปโหลดไฟล์ใหม่
	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		// ปรับขนาดรูปภาพ
		try {
			resizeImage($target_file, 370, 360, $target_dir . '370x360_' . $newfilename);
			resizeImage($target_file, 1024, 683, $target_dir . '1024x683_' . $newfilename);
		} catch (Exception $e) {
			echo '<script language="javascript">';
			echo 'alert("เกิดข้อผิดพลาดในการปรับขนาดรูปภาพ: ' . $e->getMessage() . '"); window.location="newsAdd.php";';
			echo '</script>';
			exit();
		}

		// อัปเดตข้อมูลในฐานข้อมูล
		$sql = "UPDATE news SET 
                title = '$title', 
                date1 = '$date1', 
                mou_year = '$mou_year',  
                detail = '$detail', 
                detail2 = '$detail2', 
                img = '$newfilename', 
                post_by = '$post_by', 
                faculty_id = '$faculty_id' 
                WHERE id = $newid";

		if (mysqli_query($conn, $sql)) {
			echo '<script language="javascript">';
			echo 'alert("อัปเดตข้อมูลเรียบร้อยแล้ว"); window.location="newsAdd.php";';
			echo '</script>';
		} else {
			echo '<script language="javascript">';
			echo 'alert("เกิดข้อผิดพลาดในการอัปเดตข้อมูล: ' . mysqli_error($conn) . '"); window.location="newsAdd.php";';
			echo '</script>';
		}
	} else {
		echo '<script language="javascript">';
		echo 'alert("เกิดข้อผิดพลาดในการอัปโหลดไฟล์"); window.location="newsAdd.php";';
		echo '</script>';
	}
} else {
	// กรณีไม่มีการส่งข้อมูลจากฟอร์ม
	header("Location: newsAdd.php");
	exit();
}
