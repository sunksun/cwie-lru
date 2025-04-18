<?php
session_start();
include_once('connect.php');

// ตรวจสอบว่ามีการส่งข้อมูลมาหรือไม่
if (isset($_POST['id'])) {
    // รับค่าจากฟอร์ม
    $id = $_POST['id'];
    $faculty_id = $_POST['faculty_id'];
    $year = $_POST['year'];
    $teacher_count = $_POST['teacher_count'];

    // หา year_id จากตาราง year
    $year_query = "SELECT id FROM year WHERE year = '$year'";
    $year_result = mysqli_query($conn, $year_query);
    $year_row = mysqli_fetch_assoc($year_result);
    $year_id = $year_row['id'];

    // ตรวจสอบว่ามีข้อมูลซ้ำหรือไม่ (ยกเว้นตัวเอง)
    $check_query = "SELECT * FROM faculty_teachers WHERE faculty_id = '$faculty_id' AND year_id = '$year_id' AND id != '$id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // ถ้ามีข้อมูลซ้ำให้แจ้งเตือน
        echo '<script language="javascript">';
        echo 'alert("มีข้อมูลคณะและปีการศึกษานี้ในระบบแล้ว กรุณาตรวจสอบข้อมูล!"); location.href="facultyTeacherManage.php";';
        echo '</script>';
    } else {
        // อัปเดตข้อมูลในฐานข้อมูล
        $sql = "UPDATE faculty_teachers SET 
                faculty_id = '$faculty_id', 
                year_id = '$year_id', 
                teacher_count = '$teacher_count'
                WHERE id = '$id'";

        if (mysqli_query($conn, $sql)) {
            echo '<script language="javascript">';
            echo 'alert("อัปเดตข้อมูลสำเร็จ"); location.href="facultyTeacherManage.php";';
            echo '</script>';
        } else {
            echo '<script language="javascript">';
            echo 'alert("เกิดข้อผิดพลาด! ไม่สามารถอัปเดตข้อมูลได้"); location.href="facultyTeacherManage.php";';
            echo '</script>';
        }
    }
} else {
    // หากไม่มีการส่งข้อมูลมา ให้กลับไปยังหน้าจัดการข้อมูล
    header("Location: facultyTeacherManage.php");
    exit();
}
