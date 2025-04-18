<?php
session_start();
include_once('connect.php');

// ตรวจสอบว่ามีการส่ง ID มาหรือไม่
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ลบข้อมูลจากฐานข้อมูล
    $sql = "DELETE FROM faculty_teachers WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        echo '<script language="javascript">';
        echo 'alert("ลบข้อมูลสำเร็จ"); location.href="facultyTeacherManage.php";';
        echo '</script>';
    } else {
        echo '<script language="javascript">';
        echo 'alert("เกิดข้อผิดพลาด! ไม่สามารถลบข้อมูลได้"); location.href="facultyTeacherManage.php";';
        echo '</script>';
    }
} else {
    // หากไม่มีการส่ง ID มา ให้กลับไปยังหน้าจัดการข้อมูล
    header("Location: facultyTeacherManage.php");
    exit();
}
