<?php
session_start();
include_once('connect.php');

// ตรวจสอบว่ามีการส่งค่าจากฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $id = $_POST['id'];
    $year = $_POST['year'];

    // ตรวจสอบว่าปีการศึกษานี้มีอยู่แล้วหรือไม่ (ยกเว้นรายการที่กำลังแก้ไข)
    $check_sql = "SELECT * FROM year WHERE year = '$year' AND id != '$id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // ถ้ามีข้อมูลอยู่แล้ว
        echo "<script>
                alert('ปีการศึกษานี้มีอยู่ในระบบแล้ว');
                window.location.href='yearManage.php';
              </script>";
    } else {
        // ทำการอัปเดตข้อมูล
        $sql = "UPDATE year SET year = '$year' WHERE id = '$id'";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    alert('อัปเดตข้อมูลเรียบร้อยแล้ว');
                    window.location.href='yearManage.php';
                  </script>";
        } else {
            echo "<script>
                    alert('เกิดข้อผิดพลาด: " . $conn->error . "');
                    window.location.href='yearManage.php';
                  </script>";
        }
    }
} else {
    // ถ้าไม่มีการส่งค่าจากฟอร์ม ให้กลับไปยังหน้าหลัก
    header("Location: yearManage.php");
    exit();
}

$conn->close();
