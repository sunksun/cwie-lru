<?php
session_start();
include_once('connect.php');
if (!isset($_SESSION['fullname']) || $_SESSION['fullname'] == '') {
    echo '<script language="javascript">';
    echo 'alert("กรุณา Login เข้าสู่ระบบ"); location.href="login.php"';
    echo '</script>';
    exit; // เพิ่ม exit เพื่อหยุดการทำงานหากไม่ได้เข้าสู่ระบบ
}
$user_img = $_SESSION['img'];
$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];

// ดึงปีการศึกษาจาก URL หรือใช้ค่าปีล่าสุด
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

// ดึงชื่อคณะครุศาสตร์จากฐานข้อมูล
$faculty_query = "SELECT faculty FROM faculty WHERE fid = '05'";
$faculty_result = mysqli_query($conn, $faculty_query);
$faculty_name = "คณะครุศาสตร์"; // ค่าเริ่มต้น

if ($faculty_result && mysqli_num_rows($faculty_result) > 0) {
    $faculty_row = mysqli_fetch_assoc($faculty_result);
    $faculty_name = $faculty_row['faculty'];
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานหลักสูตรที่มีการจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL)</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=TH+Sarabun+New&display=swap">
    <style>
        body {
            font-family: 'TH Sarabun New', sans-serif;
            margin: 1cm;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        h4 {
            text-align: center;
            line-height: 0.5;
        }

        th,
        td {
            padding: 2px;
            border: 1px solid #000;
            text-align: center;
            vertical-align: middle;
            font-size: 18px;
        }

        th {
            background-color: #f2f2f2;
        }

        .faculty-header {
            background-color: #e9ecef;
            font-weight: bold;
            padding: 10px;
            margin-top: 20px;
            margin-bottom: 10px;
            text-align: left;
        }

        .text-left {
            text-align: left;
        }

        .faculty-section {
            margin-bottom: 20px;
        }

        .action-buttons {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        button {
            padding: 8px 15px;
            margin-right: 10px;
            cursor: pointer;
        }

        .notes-section {
            margin-top: 20px;
        }

        /* เพิ่มคลาสสำหรับเครื่องหมายถูก */
        .check-mark {
            font-family: Arial, sans-serif;
            /* ใช้ฟอนต์ที่รองรับเครื่องหมายถูก */
            font-size: 20px;
            font-weight: bold;
        }

        /* ปรับแต่งส่วนหัวรายงาน */
        .header-container {
            text-align: center;
            width: 100%;
            margin: 0 auto 20px auto;
        }

        .header-text {
            text-align: center;
            width: 100%;
        }

        .header-text h4 {
            text-align: center;
            width: 100%;
            margin: 10px auto;
            line-height: 1.5;
            font-size: 20px;
        }

        /* CSS สำหรับการพิมพ์ */
        @media print {
            @page {
                size: A4 portrait;
                margin: 0.5cm;
                /* ปิดการแสดงส่วนหัวและส่วนท้ายของกระดาษเมื่อพิมพ์ */
                margin-header: 0;
                margin-footer: 0;
                marks: none;
            }

            html,
            body {
                margin: 0;
                padding: 0;
            }

            body {
                margin: 1cm;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* จัดหัวข้อให้อยู่ตรงกลางเมื่อพิมพ์ */
            .header-container {
                text-align: center;
                width: 100%;
                margin: 0 auto 20px auto;
            }

            /* ทำให้ตารางขึ้นหน้าใหม่อย่างเหมาะสม */
            .faculty-section {
                page-break-inside: avoid;
            }

            /* ป้องกันการตัดแถวของตารางระหว่างหน้า */
            tr {
                page-break-inside: avoid;
            }

            /* ทำให้ส่วนหัวของตารางแสดงซ้ำเมื่อขึ้นหน้าใหม่ */
            thead {
                display: table-header-group;
            }

            /* ซ่อนปุ่ม */
            .action-buttons,
            button {
                display: none !important;
            }

            /* ให้แน่ใจว่าเครื่องหมายถูกแสดงผล */
            .check-mark {
                visibility: visible !important;
                display: inline !important;
                font-family: Arial, sans-serif;
                font-size: 20px;
                font-weight: bold;
            }

            /* เพิ่มความหนาของเส้นขอบตาราง */
            th,
            td {
                border: 1px solid #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header-container">
            <div class="header-text">
                <h4>การจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL)</h4>
                <h4>คณะครุศาสตร์ มหาวิทยาลัยราชภัฏเลย</h4>
                <h4>ประจำภาคเรียนที่ <?php echo htmlspecialchars($year); ?></h4>
            </div>
        </div>

        <h4>1. หลักสูตรที่มีการจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (School-Integrated Learning : SIL)</h4>

        <div class="faculty-section">
            <table>
                <thead>
                    <tr>
                        <th width="70%">สาขาวิชา</th>
                        <th width="10%">มีการจัดการเรียนรู้แบบ SIL</th>
                        <th width="20%">หมายเหตุ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // ดึงข้อมูลหลักสูตรเฉพาะคณะครุศาสตร์ (faculty_id = '05')
                    $sql_course = "SELECT * FROM cwie_course WHERE faculty_id = '05' ORDER BY id ASC";
                    $result_course = $conn->query($sql_course);

                    if ($result_course && $result_course->num_rows > 0) {
                        $course_counter = 1;

                        while ($course_row = $result_course->fetch_assoc()) {
                            $major = $course_row['major'];
                            $sil = $course_row['sil'] ?? '/'; // หากไม่มีข้อมูล SIL ให้ใส่เครื่องหมาย / เป็นค่าเริ่มต้น
                            $note = $course_row['note'];

                            echo '<tr>
                                <td style="text-align: left;">' . $course_counter . '. ' . htmlspecialchars($major) . '</td>
                                <td>' . ($sil ? '<span class="check-mark">&radic;</span>' : '') . '</td>
                                <td class="note-cell">' . htmlspecialchars($note) . '</td>
                            </tr>';

                            $course_counter++;
                        }
                    } else {
                        echo '<tr><td colspan="3" style="text-align: center;">ไม่พบข้อมูลหลักสูตร</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="notes-section">
            <h5>หมายเหตุ:</h5>
            <p>
                <strong>School-Integrated Learning (SIL)</strong> - การจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา คือรูปแบบการจัดการเรียนการสอนที่เน้นการเรียนรู้ผ่านการปฏิบัติงานจริงในสถานศึกษา เป็นส่วนหนึ่งของหลักสูตรครุศาสตร์
            </p>
        </div>

        <div class="action-buttons">
            <button onclick="window.location.href='cwieCourseAdd.php'">กลับไปหน้าจัดการ</button>
            <button onclick="window.print()">พิมพ์รายงาน</button>
        </div>
    </div>
</body>

</html>