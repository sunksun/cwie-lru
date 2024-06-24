<?php
session_start();
include_once('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
	$activity_type = $_POST['activity_type'];
	$activity_name = $_POST['activity_name'];
	$activity_date = $_POST['activity_date'];
	$amount = $_POST['amount'];
	$note = $_POST['note'];
	$faculty_id = $_SESSION['faculty_id'];
	$activity_id = $_SESSION['activity_id'];

	// จัดการกับสาขาวิชา
	if (isset($_POST['major']) && in_array('all', $_POST['major'])) {
		$course = "ทุกหลักสูตร";
	} elseif (isset($_POST['major'])) {
		$majorIds = implode(',', $_POST['major']);
		$sql = "SELECT GROUP_CONCAT(major SEPARATOR ', ') as majors FROM major WHERE id IN ($majorIds)";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$course = $row['majors'];
		}
	} else {
		$course = "";
	}

	$filename = ""; // กำหนดค่าเริ่มต้นเป็นสตริงว่าง

	// ตรวจสอบว่ามีการอัพโหลดไฟล์หรือไม่
	if (isset($_FILES["filename"]) && $_FILES["filename"]["error"] != UPLOAD_ERR_NO_FILE) {
		$target_dir = "img_act_cwie/";
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

	// กำหนดค่า act_id ตามประเภทกิจกรรม
	if ($activity_type == "กิจกรรมนักศึกษา") {
		$act_id = "1";
	} elseif ($activity_type == "กิจกรรมอาจารย์") {
		$act_id = "2";
	} elseif ($activity_type == "กิจกรรมร่วมกับสถานประกอบการ") {
		$act_id = "3";
	}

	// เพิ่มข้อมูลลงในฐานข้อมูล
	$sql = "INSERT INTO activity_cwie (activity_type, activity_name, course, activity_date, amount, note, filename, faculty_id, activity_id) 
            VALUES ('$activity_type', '$activity_name', '$course', '$activity_date', '$amount', '$note', '$filename', '$faculty_id', '$act_id')";

	if ($conn->query($sql) === TRUE) {
		echo "<script>alert('บันทึกข้อมูลเรียบร้อยแล้ว'); window.location.href='activityCwieAdd.php';</script>";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

$conn->close();
