<?php
session_start();
$user_img = $_SESSION['img'];
include_once('connect.php');
if ($_SESSION['fullname'] == '') {
	echo '<script language="javascript">';
	echo 'alert("กรุณา Login เข้าสู่ระบบ"); location.href="login.php"';
	echo '</script>';
}
$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];
$year = "2/2566";
date_default_timezone_set("Asia/Bangkok");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
	$course = $_POST["course"];
	$name_tea_cwie = $_POST["name_tea_cwie"];
	$num_tea_cwie = $_POST["num_tea_cwie"];
	$note = $_POST["note"];
	$filename = ""; // กำหนดค่าเริ่มต้นเป็นสตริงว่าง
	// ตรวจสอบว่ามีการอัพโหลดไฟล์หรือไม่
	if (isset($_FILES["filename"]) && $_FILES["filename"]["error"] != UPLOAD_ERR_NO_FILE) {
		$target_dir = "img_teach/";
		$target_file = $target_dir . basename($_FILES["filename"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

		// เช็คว่าไฟล์รูปภาพจริงหรือไม่
		$check = getimagesize($_FILES["filename"]["tmp_name"]);
		if ($check === false) {
			echo "ไฟล์ไม่ใช่รูปภาพ";
			$uploadOk = 0;
		}

		// เช็คขนาดไฟล์
		if ($_FILES["filename"]["size"] > 500000) {
			echo "ขนาดไฟล์ใหญ่เกินไป";
			$uploadOk = 0;
		}

		// อนุญาตเฉพาะไฟล์บางประเภท
		if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
			echo "อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG & GIF เท่านั้น";
			$uploadOk = 0;
		}

		// ถ้าไม่มีข้อผิดพลาด ทำการอัพโหลดไฟล์
		if ($uploadOk == 1 && move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)) {
			$filename = basename($_FILES["filename"]["name"]);
		} else {
			echo "ขออภัย, เกิดข้อผิดพลาดในการอัพโหลดไฟล์ของคุณ";
			exit;
		}
	}
	$sql = "INSERT INTO num_tea_cwie (faculty_id, course, name_tea_cwie, num_tea_cwie, note, filename, `date_regis`)
	VALUES ('$faculty_id','$course', '$name_tea_cwie', '$num_tea_cwie', '$note', '$filename', current_timestamp());";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="numTeachCwieAdd.php"';
	echo '</script>';


	/*
	if (isset($_POST['save'])) {
		$sql = "INSERT INTO num_tea_cwie (faculty_id, course, name_tea_cwie, num_tea_cwie, note, filename, `date_regis`)
	VALUES ('$faculty_id','$course', '$name_tea_cwie', '$num_tea_cwie', '$note', '$filename', current_timestamp());";
		mysqli_query($conn, $sql);
		echo '<script language="javascript">';
		echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="numTeachCwieAdd.php"';
		echo '</script>';
	} elseif (isset($_POST['update'])) {
		$numTeacid = $_GET["numTeacid"];
		$sql = "UPDATE num_tea_cwie SET course = '$course',
	name_tea_cwie = '$name_tea_cwie',
	num_tea_cwie = '$num_tea_cwie',
	note = '$note'
	WHERE num_tea_cwie.id = $numTeacid";
		mysqli_query($conn, $sql);
		echo '<script language="javascript">';
		echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="numTeachCwieAdd.php"';
		echo '</script>';
	} */
}
