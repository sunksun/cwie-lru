<?php
session_start();
$user_img = $_SESSION['img'];
include_once('connect.php');
// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['fullname']) || $_SESSION['fullname'] == '') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];
//$year = "2/2566";

// รับค่าปีการศึกษาจาก URL หรือใช้ค่าเริ่มต้น
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

// ตรวจสอบว่ามีคอลัมน์ year ในตารางหรือไม่
$check_column = mysqli_query($conn, "SHOW COLUMNS FROM activity_cwie LIKE 'year'");
$column_exists = mysqli_num_rows($check_column) > 0;

// สร้างคำสั่ง SQL เพื่อดึงข้อมูลจากตาราง
$sql = "SELECT * FROM `num_stu_cwie` WHERE faculty_id = ? AND year = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $faculty_id, $year);
$stmt->execute();
$result = $stmt->get_result();

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
            "num_practice" => $row["num_practice"],
            "num_cwie" => $row["num_cwie"],
            "num_pundit" => $row["num_pundit"],
            "num_pundit_job" => $row["num_pundit_job"],
            "num_pundit_job_work" => $row["num_pundit_job_work"],
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
