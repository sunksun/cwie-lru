<?php
session_start();
include_once('connect.php');

// ตรวจสอบการเชื่อมต่อกับฐานข้อมูล
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ตรวจสอบว่ามีการส่งค่าจากฟอร์มหรือไม่
if (isset($_POST['save'])) {
    // รับค่าจากฟอร์ม
    $year = mysqli_real_escape_string($conn, $_POST['year']);

    // ตรวจสอบว่าค่าไม่เป็นค่าว่าง
    if (empty($year)) {
        echo "<script>
                alert('กรุณากรอกปีการศึกษา');
                window.location.href='yearManage.php';
              </script>";
        exit();
    }

    // ตรวจสอบว่าปีการศึกษานี้มีอยู่แล้วหรือไม่
    $check_sql = "SELECT * FROM year WHERE year = '$year'";
    $check_result = $conn->query($check_sql);

    if ($check_result === false) {
        // ถ้าการ query ผิดพลาด
        echo "<script>
                alert('เกิดข้อผิดพลาดในการตรวจสอบข้อมูล: " . $conn->error . "');
                window.location.href='yearManage.php';
              </script>";
        exit();
    }

    if ($check_result->num_rows > 0) {
        // ถ้ามีข้อมูลอยู่แล้ว
        echo "<script>
                alert('ปีการศึกษานี้มีอยู่ในระบบแล้ว');
                window.location.href='yearManage.php';
              </script>";
    } else {
        // ถ้ายังไม่มีข้อมูล ให้ทำการบันทึก
        $sql = "INSERT INTO year (year) VALUES ('$year')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    alert('บันทึกข้อมูลเรียบร้อยแล้ว');
                    window.location.href='yearManage.php';
                  </script>";
        } else {
            // แสดงข้อผิดพลาดที่เกิดขึ้นอย่างละเอียด
            echo "<script>
                    alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $conn->error . "');
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
