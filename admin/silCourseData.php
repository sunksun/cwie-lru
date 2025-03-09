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

// รับค่าปีการศึกษาจาก URL
if (isset($_GET['year']) && !empty($_GET['year'])) {
    $year = $_GET['year'];
} else {
    // ถ้าไม่มีการส่งค่าปีการศึกษามา ให้ดึงปีการศึกษาล่าสุดจากฐานข้อมูล
    $latest_year_query = "SELECT year FROM year ORDER BY id DESC LIMIT 1";
    $latest_year_result = mysqli_query($conn, $latest_year_query);

    if ($latest_year_result && mysqli_num_rows($latest_year_result) > 0) {
        $latest_year_row = mysqli_fetch_assoc($latest_year_result);
        $year = $latest_year_row['year'];
    } else {
        $year = "2/2566"; // ค่าเริ่มต้นกรณีไม่พบข้อมูล
    }
}

// สร้างคำสั่ง SQL เพื่อดึงข้อมูลจากตาราง - เฉพาะรายการที่มี sil เป็น / และตรงกับปีการศึกษาที่ระบุ
$sql = "SELECT id, faculty_id, major, sil, note, year FROM cwie_course 
        WHERE faculty_id = '$faculty_id' AND sil = '/' AND year = '$year'";
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
            "sil" => $row["sil"],  // SIL จะแสดงเป็น /
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
