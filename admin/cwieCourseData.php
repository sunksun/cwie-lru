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

include_once('connect.php');

// สร้างคำสั่ง SQL เพื่อดึงข้อมูลจากตาราง
$sql = "SELECT id, major, separate, parallel, mix, note FROM cwie_course";
$result = $conn->query($sql);

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($result->num_rows > 0) {
    // สร้างอาร์เรย์สำหรับเก็บข้อมูล
    $data = [];

    // วนลูปผ่านผลลัพธ์และเพิ่มข้อมูลในอาร์เรย์
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "id" => $row["id"],
            "major" => $row["major"],
            "separate" => $row["separate"],
            "parallel" => $row["parallel"],
            "mix" => $row["mix"],
            "note" => $row["note"]
        ];
    }
} else {
    $data = [];
}

// ปิดการเชื่อมต่อ
$conn->close();

// ส่งออกข้อมูลในรูปแบบ JSON
header('Content-Type: application/json');
echo json_encode($data);
