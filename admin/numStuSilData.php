<?php
session_start();
include_once('connect.php');

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['fullname']) || $_SESSION['fullname'] == '') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// ดึงข้อมูลจาก Session
$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];

// ดึงค่าปีการศึกษาจาก GET parameter หรือใช้ค่าเริ่มต้น
if (isset($_GET['year']) && !empty($_GET['year'])) {
    $year = $_GET['year'];
} else {
    // ดึงปีการศึกษาล่าสุดจากตาราง year
    $latest_year_query = "SELECT year FROM year ORDER BY id DESC LIMIT 1";
    $latest_year_result = mysqli_query($conn, $latest_year_query);

    if ($latest_year_result && mysqli_num_rows($latest_year_result) > 0) {
        $latest_year_row = mysqli_fetch_assoc($latest_year_result);
        $year = $latest_year_row['year'];
    } else {
        $year = "2/2566"; // ค่าเริ่มต้นกรณีไม่พบข้อมูล
    }
}

// ป้องกัน SQL Injection
$year = mysqli_real_escape_string($conn, $year);
$faculty_id = mysqli_real_escape_string($conn, $faculty_id);

// สร้างคำสั่ง SQL เพื่อดึงข้อมูลจากตารางตามปีการศึกษา
$sql = "SELECT * FROM `num_stu_cwie` WHERE faculty_id = '$faculty_id' AND year = '$year' ORDER BY major ASC";
$result = $conn->query($sql);

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($result && $result->num_rows > 0) {
    // สร้างอาร์เรย์สำหรับเก็บข้อมูล
    $data = [];
    $sequence = 1; // ตัวนับสำหรับลำดับ

    // วนลูปผ่านผลลัพธ์และเพิ่มข้อมูลในอาร์เรย์
    while ($row = $result->fetch_assoc()) {
        // แปลงค่าจำนวนให้เป็นตัวเลข
        $num_practice = is_numeric($row["num_practice"]) ? (int)$row["num_practice"] : 0;
        $num_cwie = is_numeric($row["num_cwie"]) ? (int)$row["num_cwie"] : 0;
        $num_pundit = is_numeric($row["num_pundit"]) ? (int)$row["num_pundit"] : 0;
        $num_pundit_job = is_numeric($row["num_pundit_job"]) ? (int)$row["num_pundit_job"] : 0;
        $num_pundit_job_work = is_numeric($row["num_pundit_job_work"]) ? (int)$row["num_pundit_job_work"] : 0;

        $data[] = [
            "sequence" => $sequence, // เพิ่มลำดับ
            "major" => $row["major"],
            "num_practice" => $num_practice,
            "num_cwie" => $num_cwie,
            "num_pundit" => $num_pundit,
            "num_pundit_job" => $num_pundit_job,
            "num_pundit_job_work" => $num_pundit_job_work,
            "note" => $row["note"]
        ];
        $sequence++; // เพิ่มลำดับ
    }
} else {
    // ถ้าไม่พบข้อมูล ส่งอาร์เรย์ว่าง
    $data = [];
}

// ปิดการเชื่อมต่อ
$conn->close();

// ส่งออกข้อมูลในรูปแบบ JSON
header('Content-Type: application/json');
// เพิ่ม Cache-Control เพื่อป้องกันการแคชข้อมูล
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
echo json_encode($data);
