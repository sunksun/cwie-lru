<?php
session_start();
$user_img = $_SESSION['img'];
include_once('connect.php');
if ($_SESSION['fullname'] == '') {
    echo '<script language="javascript">';
    echo 'alert("กรุณา Login เข้าสู่ระบบ"); location.href="login.php"';
    echo '</script>';
    exit;
}
$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];

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
$check_column = mysqli_query($conn, "SHOW COLUMNS FROM num_tea_cwie LIKE 'year'");
$column_exists = mysqli_num_rows($check_column) > 0;

// สร้างคำสั่ง SQL เพื่อดึงข้อมูลจากตาราง
if ($column_exists) {
    // ถ้ามีคอลัมน์ year ให้กรองข้อมูลตามปีการศึกษาด้วย
    $sql = "SELECT * FROM num_tea_cwie WHERE faculty_id = '$faculty_id' AND year = '$year' ORDER BY id DESC";
} else {
    // ถ้าไม่มีคอลัมน์ year ให้ดึงข้อมูลตาม faculty_id อย่างเดียว
    $sql = "SELECT * FROM num_tea_cwie WHERE faculty_id = '$faculty_id' ORDER BY id DESC";
}

$result = $conn->query($sql);

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($result->num_rows > 0) {
    // สร้างอาร์เรย์สำหรับเก็บข้อมูล
    $data = [];
    $sequence = 1; // ตัวนับสำหรับลำดับ

    // วนลูปผ่านผลลัพธ์และเพิ่มข้อมูลในอาร์เรย์
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "ลำดับที่" => $sequence, // เพิ่มลำดับ
            "รายชื่ออาจารย์นิเทศหลักสูตรสหกิจศึกษา" => $row["name_tea_cwie"],
            "สาขาวิชา" => $row["course"],
            "หมายเลขประจำตัวผู้ขึ้นทะเบียน" => $row["num_tea_cwie"],
            "หมายเหตุ" => $row["note"]
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
