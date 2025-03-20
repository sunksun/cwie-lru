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
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานหลักสูตรที่มีการเรียนการสอนแบบ CWIE</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=TH+Sarabun+New&display=swap">
    <style>
        body {
            font-family: 'TH Sarabun New', sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        h4 {
            text-align: center;
            line-height: 0.5;
        }

        th,
        td {
            padding: 2px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
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

        /* CSS สำหรับการพิมพ์ */
        @media print {
            @page {
                size: auto;
                margin: 0;
                margin-header: 0;
                margin-footer: 0;
            }

            html,
            body {
                margin: 1cm;
                padding: 0;
            }

            button,
            .no-print {
                display: none !important;
            }

            /* ซ่อนส่วนหัวและท้ายที่บราว์เซอร์สร้างอัตโนมัติ */
            body::before,
            body::after {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header-text">
            <h4>สหกิจศึกษาและการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE)</h4>
            <h4><?php echo htmlspecialchars($faculty); ?></h4>
            <h4>มหาวิทยาลัยราชภัฏเลย</h4>
            <h4>ประจำภาคเรียนที่ <?php echo htmlspecialchars($year); ?></h4>
        </div>

        <h4>หลักสูตรที่มีการเรียนการสอนแบบสหกิจศึกษาและการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE)</h4>

        <?php
        // ถ้าเป็น admin ดึงข้อมูลคณะทั้งหมด
        if ($username == 'admin') {
            $sql_faculty = "SELECT fid, faculty FROM faculty ORDER BY id ASC";
            $result_faculty = $conn->query($sql_faculty);

            if ($result_faculty && $result_faculty->num_rows > 0) {
                $faculty_counter = 1;

                while ($faculty_row = $result_faculty->fetch_assoc()) {
                    $faculty_id = $faculty_row['fid'];
                    $faculty_name = $faculty_row['faculty'];

                    // สร้างตารางสำหรับแต่ละคณะ โดยจัดให้ชิดซ้าย
                    echo '<h4 class="faculty-header" style="text-align: left;">' . $faculty_counter . '. ' . $faculty_name . '</h4>';


                    // เพิ่มตาราง
                    echo '<table>
                        <thead>
                            <tr>
                                <th width="60%">สาขาวิชา</th>
                                <th width="10%">แบบแยก<br>(Separate)</th>
                                <th width="10%">แบบคู่ขนาน<br>(Parallel)</th>
                                <th width="10%">แบบผสม<br>(Mix)</th>
                                <th width="10%">หมายเหตุ</th>
                            </tr>
                        </thead>
                        <tbody>';

                    // ดึงข้อมูลหลักสูตรตามคณะ
                    $sql_course = "SELECT * FROM cwie_course WHERE faculty_id = '$faculty_id' ORDER BY id ASC";
                    $result_course = $conn->query($sql_course);

                    if ($result_course && $result_course->num_rows > 0) {
                        $course_counter = 1;

                        while ($course_row = $result_course->fetch_assoc()) {
                            $major = $course_row['major'];
                            $separate = $course_row['separate'];
                            $parallel = $course_row['parallel'];
                            $mix = $course_row['mix'];
                            $note = $course_row['note'];

                            echo '<tr>
                                <td style="text-align: left;">' . $course_counter . '. ' . htmlspecialchars($major) . '</td>
                                <td>' . ($separate ? '<span class="check-mark">✓</span>' : '') . '</td>
                                <td>' . ($parallel ? '<span class="check-mark">✓</span>' : '') . '</td>
                                <td>' . ($mix ? '<span class="check-mark">✓</span>' : '') . '</td>
                                <td class="note-cell">' . htmlspecialchars($note) . '</td>
                            </tr>';

                            $course_counter++;
                        }
                    } else {
                        echo '<tr><td colspan="5" style="text-align: center;">ไม่พบข้อมูลหลักสูตร</td></tr>';
                    }

                    echo '</tbody></table>';
                    $faculty_counter++;
                }
            } else {
                echo '<div style="text-align: center; margin: 20px;">ไม่พบข้อมูลคณะ</div>';
            }
        } else {
            // ถ้าไม่ใช่ admin ให้แสดงเฉพาะคณะของผู้ใช้
            echo '<table>
                <thead>
                    <tr>
                        <th width="60%">สาขาวิชา</th>
                        <th width="10%">แบบแยก<br>(Separate)</th>
                        <th width="10%">แบบคู่ขนาน<br>(Parallel)</th>
                        <th width="10%">แบบผสม<br>(Mix)</th>
                        <th width="10%">หมายเหตุ</th>
                    </tr>
                </thead>
                <tbody>';

            // ดึงข้อมูลหลักสูตรตามคณะของผู้ใช้
            $sql_course = "SELECT * FROM cwie_course WHERE faculty_id = '$faculty_id' ORDER BY id ASC";
            $result_course = $conn->query($sql_course);

            if ($result_course && $result_course->num_rows > 0) {
                $course_counter = 1;

                while ($course_row = $result_course->fetch_assoc()) {
                    $major = $course_row['major'];
                    $separate = $course_row['separate'];
                    $parallel = $course_row['parallel'];
                    $mix = $course_row['mix'];
                    $note = $course_row['note'];

                    echo '<tr>
                        <td style="text-align: left;">' . $course_counter . '. ' . htmlspecialchars($major) . '</td>
                        <td>' . ($separate ? '<span class="check-mark">✓</span>' : '') . '</td>
                        <td>' . ($parallel ? '<span class="check-mark">✓</span>' : '') . '</td>
                        <td>' . ($mix ? '<span class="check-mark">✓</span>' : '') . '</td>
                        <td class="note-cell">' . htmlspecialchars($note) . '</td>
                    </tr>';

                    $course_counter++;
                }
            } else {
                echo '<tr><td colspan="5" style="text-align: center;">ไม่พบข้อมูลหลักสูตร</td></tr>';
            }

            echo '</tbody></table>';
        }
        ?>

        <div class="notes-section">
            <h5>หมายเหตุ:</h5>
            <p>
                <strong>แบบแยก (Separate)</strong> - จัดการเรียนการสอนในห้องเรียนสลับกับการฝึกปฏิบัติในสถานประกอบการ<br>
                <strong>แบบคู่ขนาน (Parallel)</strong> - จัดการเรียนการสอนในห้องเรียนควบคู่กับการฝึกปฏิบัติในสถานประกอบการ<br>
                <strong>แบบผสม (Mix)</strong> - จัดการเรียนการสอนแบบผสมผสานระหว่างรูปแบบแยกและรูปแบบคู่ขนาน
            </p>
        </div>

        <div class="action-buttons">
            <button onclick="window.location.href='cwieCourseAdd.php'">กลับไปหน้าจัดการ</button>
            <button onclick="window.print()">พิมพ์รายงาน</button>
        </div>
    </div>
</body>

</html>