<?php
// สร้างการเชื่อมต่อฐานข้อมูล (คุณต้องปรับเปลี่ยนตามการตั้งค่าของคุณ)
include_once('connect.php');

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/// กำหนดค่า $faculty_id ที่คุณต้องการ
$faculty_id = '01'; // ตัวอย่างค่า

// สร้างคำสั่ง SQL
$sql = "SELECT id, faculty_id, major, 
        CASE 
            WHEN separate != '' THEN 'separate'
            WHEN parallel != '' THEN 'parallel'
            WHEN mix != '' THEN 'mix'
            ELSE 'none'
        END as type,
        note, date_regis, year
        FROM `cwie_course`
        WHERE faculty_id = '$faculty_id'
        ORDER BY `cwie_course`.`id` DESC";

$result = $conn->query($sql);

// ตรวจสอบและแสดงผลลัพธ์
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . " - Faculty ID: " . $row["faculty_id"] . " - Major: " . $row["major"] . " - Type: " . $row["type"] . " - Note: " . $row["note"] . " - Date Registered: " . $row["date_regis"] . " - Year: " . $row["year"] . "<br>";
    }
} else {
    echo "0 results";
}

$conn->close();
