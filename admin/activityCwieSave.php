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
	$activity_id = isset($_SESSION['activity_id']) ? $_SESSION['activity_id'] : '';
	$year = isset($_POST['year']) ? $_POST['year'] : '2/2566';

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

	// ตรวจสอบว่าฟิลด์ที่จำเป็นได้รับการกรอกหรือไม่
	if (empty($activity_type) || $activity_type == "--- เลือกประเภทกิจกรรม ---" || empty($activity_name) || empty($activity_date)) {
		echo "<script>alert('กรุณากรอกข้อมูลที่จำเป็น (ประเภทกิจกรรม, ชื่อกิจกรรม, วันที่ดำเนินการ)'); window.history.back();</script>";
		exit;
	}

	$filename = ""; // กำหนดค่าเริ่มต้นเป็นสตริงว่าง

	// ตรวจสอบว่ามีการอัพโหลดไฟล์หรือไม่
	if (isset($_FILES["filename"]) && $_FILES["filename"]["error"] != UPLOAD_ERR_NO_FILE) {
		$target_dir = "img_act_cwie/";

		// สร้างชื่อไฟล์ใหม่ที่ไม่ซ้ำกัน
		$temp = explode(".", $_FILES["filename"]["name"]);
		$newfilename = date('dmYHis') . '.' . end($temp);
		$target_file = $target_dir . $newfilename;

		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

		// เช็คว่าไฟล์รูปภาพจริงหรือไม่
		$check = getimagesize($_FILES["filename"]["tmp_name"]);
		if ($check === false) {
			echo "<script>alert('ไฟล์ไม่ใช่รูปภาพ'); window.history.back();</script>";
			$uploadOk = 0;
			exit;
		}

		// เช็คขนาดไฟล์
		if ($_FILES["filename"]["size"] > 2000000) { // 2MB
			echo "<script>alert('ขนาดไฟล์ใหญ่เกินไป (ไม่เกิน 2MB)'); window.history.back();</script>";
			$uploadOk = 0;
			exit;
		}

		// อนุญาตเฉพาะไฟล์บางประเภท
		if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
			echo "<script>alert('อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG & GIF เท่านั้น'); window.history.back();</script>";
			$uploadOk = 0;
			exit;
		}

		// ถ้าไม่มีข้อผิดพลาด ทำการอัพโหลดไฟล์
		if ($uploadOk == 1 && move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)) {
			$filename = $newfilename;
		} else {
			echo "<script>alert('ขออภัย, เกิดข้อผิดพลาดในการอัพโหลดไฟล์'); window.history.back();</script>";
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

	// ตรวจสอบว่ามีคอลัมน์ year ในตารางหรือไม่
	$check_column = mysqli_query($conn, "SHOW COLUMNS FROM activity_cwie LIKE 'year'");
	$column_exists = mysqli_num_rows($check_column) > 0;

	// เพิ่มข้อมูลลงในฐานข้อมูล
	if ($column_exists) {
		// ถ้ามีคอลัมน์ year ให้บันทึกค่า year ด้วย
		$sql = "INSERT INTO activity_cwie (activity_type, activity_name, course, activity_date, amount, note, filename, faculty_id, activity_id, year) 
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ssssssssss", $activity_type, $activity_name, $course, $activity_date, $amount, $note, $filename, $faculty_id, $act_id, $year);
	} else {
		// ถ้าไม่มีคอลัมน์ year ให้บันทึกข้อมูลโดยไม่มี year
		$sql = "INSERT INTO activity_cwie (activity_type, activity_name, course, activity_date, amount, note, filename, faculty_id, activity_id) 
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("sssssssss", $activity_type, $activity_name, $course, $activity_date, $amount, $note, $filename, $faculty_id, $act_id);
	}

	if ($stmt->execute()) {
		echo "<script>alert('บันทึกข้อมูลเรียบร้อยแล้ว'); window.location.href='activityCwieAdd.php?year=" . $year . "';</script>";
	} else {
		echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error . "'); window.history.back();</script>";
	}

	$stmt->close();
}

$conn->close();
