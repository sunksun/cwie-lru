<?php
session_start();
include_once('connect.php');

// ตรวจสอบว่ามีการส่งค่า id หรือไม่
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ทำการลบข้อมูล
    $sql = "DELETE FROM year WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('ลบข้อมูลเรียบร้อยแล้ว');
                window.location.href='yearManage.php';
              </script>";
    } else {
        echo "<script>
                alert('เกิดข้อผิดพลาด: " . $conn->error . "');
                window.location.href='yearManage.php';
              </script>";
    }
} else {
    // ถ้าไม่มีการส่งค่า id ให้กลับไปยังหน้าหลัก
    header("Location: yearManage.php");
    exit();
}

$conn->close();
