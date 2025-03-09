<?php
// ไฟล์สำหรับดึงข้อมูลนักศึกษาจากฐานข้อมูล
header('Content-Type: application/json');
include_once('connect.php');

// กำหนดปีการศึกษาที่ต้องการดึงข้อมูล (รับจาก GET หรือใช้ค่าเริ่มต้น)
$year = isset($_GET['year']) ? $_GET['year'] : "2/2566";

try {
    // เตรียมข้อมูลสำหรับส่งกลับ
    $data = [
        'labels' => [], // ชื่อคณะ
        'cwie' => [],   // จำนวนนักศึกษาสหกิจ
        'practice' => [] // จำนวนนักศึกษาฝึกประสบการณ์
    ];

    // 1. ดึงข้อมูลจำนวนนักศึกษาแยกตามคณะ
    $faculty_query = "
        SELECT f.faculty, 
               SUM(CAST(n.num_cwie AS UNSIGNED)) AS total_cwie, 
               SUM(CAST(n.num_practice AS UNSIGNED)) AS total_practice
        FROM num_stu_cwie n
        JOIN faculty f ON n.faculty_id = f.fid
        WHERE n.year = ? AND n.major != ''
        GROUP BY f.faculty
        ORDER BY f.faculty ASC
    ";

    $stmt = $conn->prepare($faculty_query);
    $stmt->bind_param('s', $year);
    $stmt->execute();
    $result = $stmt->get_result();

    // ตรวจสอบจำนวนผลลัพธ์
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // เพิ่มข้อมูลลงในอาร์เรย์ที่จะส่งกลับ
            $data['labels'][] = $row['faculty'];
            $data['cwie'][] = (int)$row['total_cwie'];
            $data['practice'][] = (int)$row['total_practice'];
        }
    } else {
        // ถ้าไม่มีข้อมูล ให้ส่งข้อมูลตัวอย่าง
        $data['labels'] = ['ไม่มีข้อมูล'];
        $data['cwie'] = [0];
        $data['practice'] = [0];
    }

    // ส่งข้อมูลกลับเป็น JSON
    echo json_encode($data);
} catch (Exception $e) {
    // ส่งข้อความผิดพลาดกลับ
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
