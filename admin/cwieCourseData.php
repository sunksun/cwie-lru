<?php
session_start();
include_once('connect.php');

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['fullname']) || $_SESSION['fullname'] == '') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// ดึงข้อมูลจาก session และ request
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
$check_column = mysqli_query($conn, "SHOW COLUMNS FROM activity_cwie LIKE 'year'");
$column_exists = mysqli_num_rows($check_column) > 0;

try {
    // เตรียมคำสั่ง SQL สำหรับดึงข้อมูล
    $sql = "SELECT id, faculty_id, major, separate, parallel, mix, note, year 
            FROM cwie_course 
            WHERE faculty_id = ? AND year = ?
            ORDER BY id";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("SQL preparation failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $faculty_id, $year);
    $stmt->execute();
    $result = $stmt->get_result();

    // สร้างอาร์เรย์สำหรับเก็บข้อมูล
    $data = [];
    $sequence = 1;

    // วนลูปผ่านผลลัพธ์และเพิ่มข้อมูลในอาร์เรย์
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "sequence" => $sequence,
            "major" => $row["major"],
            "separate" => !empty($row["separate"]) ? "/" : "",
            "parallel" => !empty($row["parallel"]) ? "/" : "",
            "mix" => !empty($row["mix"]) ? "/" : "",
            "note" => $row["note"]
        ];
        $sequence++;
    }

    // ปิดการเชื่อมต่อ
    $stmt->close();
    $conn->close();

    // ส่งออกข้อมูลในรูปแบบ JSON
    header('Content-Type: application/json');
    echo json_encode($data);
} catch (Exception $e) {
    // จัดการข้อผิดพลาด
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
