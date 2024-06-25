<?php
// example.php

// สร้างข้อมูลตัวอย่าง
//$data = [
//    ["id" => "1", "major" => "ชีววิทยา", "separate" => "/", "parallel" => "", "mix" => "", "note" => ""],
//    ["id" => "2", "major" => "คณิตศาสตร์", "separate" => "/", "parallel" => "", "mix" => "", "note" => ""],
//    ["id" => "3", "major" => "ฟิสิกส์", "separate" => "/", "parallel" => "", "mix" => "", "note" => ""]
//];

// ส่งออกข้อมูลในรูปแบบ JSON
//header('Content-Type: application/json');
//echo json_encode($data);


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

// สร้างคำสั่ง SQL เพื่อดึงข้อมูลจากตาราง
$sql = "SELECT id, faculty_id, major, separate, parallel, mix, note, year FROM cwie_course WHERE faculty_id = '$faculty_id'";
$result = $conn->query($sql);

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($result->num_rows > 0) {
    // สร้างอาร์เรย์สำหรับเก็บข้อมูล
    $data = [];
    $sequence = 1; // ตัวนับสำหรับลำดับ

    // วนลูปผ่านผลลัพธ์และเพิ่มข้อมูลในอาร์เรย์
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "sequence" => $sequence, // เพิ่มลำดับ
            "major" => $row["major"],
            "separate" => $row["separate"],
            "parallel" => $row["parallel"],
            "mix" => $row["mix"],
            "note" => $row["note"]
        ];
        $sequence++; // เพิ่มลำดับ
    }
} else {
    $data = [];
}

// ปิดการเชื่อมต่อ
$conn->close();

// ส่งออกข้อมูลในรูปแบบ JSON
header('Content-Type: application/json');
echo json_encode($data);
