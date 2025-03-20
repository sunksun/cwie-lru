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
    <title>รายงานข้อมูลสถานประกอบการและข้อมูลนักศึกษา CWIE</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=TH+Sarabun+New&display=swap">
    <style>
        body {
            font-family: 'TH Sarabun New', sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h3,
        h4 {
            text-align: center;
            margin-bottom: 10px;
        }

        .header-text {
            text-align: center;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 6px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .major-name {
            padding-left: 20px;
            text-align: left;
        }

        .faculty-row {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .major-row {
            background-color: #ffffff;
        }

        .table-container {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .notes-section {
            margin-top: 20px;
            margin-bottom: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
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

        .page-break {
            page-break-before: always;
        }

        /* CSS สำหรับการพิมพ์ */
        @media print {
            @page {
                size: A4 portrait;
                margin: 0.5cm;
            }

            body {
                margin: 1cm;
            }

            .action-buttons {
                display: none !important;
            }

            button {
                display: none !important;
            }

            .table-container {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header-text">
            <h4>สหกิจศึกษาและการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE)</h4>
            <h4>มหาวิทยาลัยราชภัฏเลย</h4>
            <h4>ประจำภาคเรียนที่ <?php echo htmlspecialchars($year); ?></h4>
        </div>

        <!-- ตาราง MOU -->
        <div class="table-container">
            <h3 class="text-center mb-4">จำนวนการทำบันทึกข้อตกลงระหว่างองค์กรกับคณะ (MOU)</h3>

            <table>
                <thead>
                    <tr>
                        <th class="align-middle" style="width: 5%">ลำดับ</th>
                        <th class="align-middle" style="width: 40%; text-align: left;">คณะ</th>
                        <th class="align-middle" style="width: 10%">2564</th>
                        <th class="align-middle" style="width: 10%">2565</th>
                        <th class="align-middle" style="width: 10%">2566</th>
                        <th class="align-middle" style="width: 10%">2567</th>
                        <th class="align-middle" style="width: 10%">รวม</th>
                        <th class="align-middle" style="width: 5%">หมายเหตุ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // ตรวจสอบสิทธิ์การเข้าถึงข้อมูล
                    if ($username == 'admin') {
                        // ถ้าเป็น admin ดึงข้อมูลคณะทั้งหมด
                        $sql_faculty = "SELECT fid, faculty FROM faculty ORDER BY id ASC";
                        $result_faculty = $conn->query($sql_faculty);
                    } else {
                        // ถ้าไม่ใช่ admin ดึงเฉพาะคณะของผู้ใช้
                        $sql_faculty = "SELECT fid, faculty FROM faculty WHERE fid = '$faculty_id'";
                        $result_faculty = $conn->query($sql_faculty);
                    }

                    if ($result_faculty && $result_faculty->num_rows > 0) { // ถ้ามีข้อมูล
                        $counter = 1;
                        $total_2564 = 0;
                        $total_2565 = 0;
                        $total_2566 = 0;
                        $total_2567 = 0;
                        $total_all = 0;

                        while ($faculty_row = $result_faculty->fetch_assoc()) {
                            $faculty_id_current = $faculty_row['fid'];
                            $faculty_name = $faculty_row['faculty'];

                            // คำนวณจำนวน MOU ในแต่ละปีสำหรับแต่ละคณะโดยดูจากคอลัมน์ year
                            $sql = "SELECT 
                                COUNT(CASE WHEN year LIKE '%/2564' THEN 1 END) as count_2564,
                                COUNT(CASE WHEN year LIKE '%/2565' THEN 1 END) as count_2565,
                                COUNT(CASE WHEN year LIKE '%/2566' THEN 1 END) as count_2566,
                                COUNT(CASE WHEN year LIKE '%/2567' THEN 1 END) as count_2567
                                FROM organization_mou 
                                WHERE faculty_id = '$faculty_id_current'";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();

                            $count_2564 = $row['count_2564'] ?: 0;
                            $count_2565 = $row['count_2565'] ?: 0;
                            $count_2566 = $row['count_2566'] ?: 0;
                            $count_2567 = $row['count_2567'] ?: 0;

                            // คำนวณผลรวมแต่ละแถว
                            $row_total = $count_2564 + $count_2565 + $count_2566 + $count_2567;

                            // สะสมผลรวมทั้งหมด
                            $total_2564 += $count_2564;
                            $total_2565 += $count_2565;
                            $total_2566 += $count_2566;
                            $total_2567 += $count_2567;
                            $total_all += $row_total;

                            echo '<tr>
                                <td class="text-center">' . $counter . '</td>
                                <td class="text-left">' . $faculty_name . '</td>
                                <td class="text-center">' . ($count_2564 > 0 ? $count_2564 : '-') . '</td>
                                <td class="text-center">' . ($count_2565 > 0 ? $count_2565 : '-') . '</td>
                                <td class="text-center">' . ($count_2566 > 0 ? $count_2566 : '-') . '</td>
                                <td class="text-center">' . ($count_2567 > 0 ? $count_2567 : '-') . '</td>
                                <td class="text-center">' . ($row_total > 0 ? $row_total : '-') . '</td>
                                <td></td>
                            </tr>';

                            $counter++;
                        }

                        // แสดงแถวสรุปผลรวม
                        echo '<tr style="background-color: #e9ecef; font-weight: bold;">
                            <td colspan="2" class="text-center"><strong>รวม</strong></td>
                            <td class="text-center"><strong>' . ($total_2564 > 0 ? $total_2564 : '-') . '</strong></td>
                            <td class="text-center"><strong>' . ($total_2565 > 0 ? $total_2565 : '-') . '</strong></td>
                            <td class="text-center"><strong>' . ($total_2566 > 0 ? $total_2566 : '-') . '</strong></td>
                            <td class="text-center"><strong>' . ($total_2567 > 0 ? $total_2567 : '-') . '</strong></td>
                            <td class="text-center"><strong>' . ($total_all > 0 ? $total_all : '-') . '</strong></td>
                            <td></td>
                        </tr>';
                    } else { // ถ้าไม่มีข้อมูล
                        echo '<tr><td colspan="8" class="text-center">ไม่พบข้อมูลการทำบันทึกข้อตกลง</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- ตารางข้อมูลนักศึกษา CWIE และการได้งานทำ -->
        <div class="table-container page-break">
            <h3 class="text-center mb-4">นักศึกษาสหกิจศึกษา (CWIE)<br>และการได้งานทำกับสถานประกอบการ</h3>

            <table>
                <thead>
                    <tr>
                        <th rowspan="2" class="align-middle" style="width: 5%">ลำดับที่</th>
                        <th rowspan="2" class="align-middle" style="width: 35%; text-align: left;">คณะ</th>
                        <th rowspan="2" class="align-middle" style="width: 12%">จำนวนบัณฑิต CWIE<br>(ปีการศึกษา 2/2567)</th>
                        <th rowspan="2" class="align-middle" style="width: 12%">จำนวนบัณฑิต CWIE<br>ที่ได้งานทำ<br>(ปีการศึกษา2/2567)</th>
                        <th rowspan="2" class="align-middle" style="width: 12%">จำนวนบัณฑิต CWIE<br>ที่ได้งานทำในสถานประกอบการ<br>(ปีการศึกษา2/2567)</th>
                        <th rowspan="2" class="align-middle" style="width: 10%">คิดเป็น<br>ร้อยละ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // ตรวจสอบสิทธิ์การเข้าถึงข้อมูล
                    if ($username == 'admin') {
                        // ถ้าเป็น admin ดึงข้อมูลคณะทั้งหมด
                        $sql_faculty = "SELECT fid, faculty FROM faculty ORDER BY id ASC";
                        $result_faculty = $conn->query($sql_faculty);
                    } else {
                        // ถ้าไม่ใช่ admin ดึงเฉพาะคณะของผู้ใช้
                        $sql_faculty = "SELECT fid, faculty FROM faculty WHERE fid = '$faculty_id'";
                        $result_faculty = $conn->query($sql_faculty);
                    }

                    if ($result_faculty && $result_faculty->num_rows > 0) {
                        $faculty_counter = 1;
                        $total_num_pundit = 0;
                        $total_num_pundit_job = 0;
                        $total_num_pundit_job_work = 0;

                        while ($faculty_row = $result_faculty->fetch_assoc()) {
                            $faculty_id_current = $faculty_row['fid'];
                            $faculty_name = $faculty_row['faculty'];

                            // คำนวณยอดรวมสำหรับแต่ละคณะ
                            $sql_sum = "SELECT 
                                SUM(num_pundit) as total_pundit, 
                                SUM(num_pundit_job) as total_pundit_job, 
                                SUM(num_pundit_job_work) as total_pundit_job_work
                                FROM num_stu_cwie 
                                WHERE faculty_id = '$faculty_id_current' AND year = '2/2567'";
                            $result_sum = $conn->query($sql_sum);
                            $row_sum = $result_sum->fetch_assoc();

                            $faculty_num_pundit = $row_sum['total_pundit'] ?: 0;
                            $faculty_num_pundit_job = $row_sum['total_pundit_job'] ?: 0;
                            $faculty_num_pundit_job_work = $row_sum['total_pundit_job_work'] ?: 0;

                            // คำนวณร้อยละ
                            $faculty_percentage = ($faculty_num_pundit > 0) ? number_format(($faculty_num_pundit_job / $faculty_num_pundit) * 100, 2) : 0;

                            // เพิ่มยอดรวมทั้งหมด
                            $total_num_pundit += $faculty_num_pundit;
                            $total_num_pundit_job += $faculty_num_pundit_job;
                            $total_num_pundit_job_work += $faculty_num_pundit_job_work;

                            // แสดงแถวของคณะ
                            echo '<tr class="faculty-row">
                                <td class="text-center">' . $faculty_counter . '</td>
                                <td class="text-left"><strong>' . $faculty_name . '</strong></td>
                                <td class="text-center">' . ($faculty_num_pundit > 0 ? $faculty_num_pundit : '-') . '</td>
                                <td class="text-center">' . ($faculty_num_pundit_job > 0 ? $faculty_num_pundit_job : '-') . '</td>
                                <td class="text-center">' . ($faculty_num_pundit_job_work > 0 ? $faculty_num_pundit_job_work : '-') . '</td>
                                <td class="text-center">' . ($faculty_percentage > 0 ? $faculty_percentage : '-') . '</td>
                            </tr>';

                            // ดึงข้อมูลสาขาวิชาตามคณะ
                            $sql_major = "SELECT major, num_pundit, num_pundit_job, num_pundit_job_work 
                                FROM num_stu_cwie 
                                WHERE faculty_id = '$faculty_id_current' AND year = '2/2567'
                                ORDER BY id ASC";
                            $result_major = $conn->query($sql_major);

                            if ($result_major && $result_major->num_rows > 0) {
                                $major_counter = 1;

                                while ($major_row = $result_major->fetch_assoc()) {
                                    $major = $major_row['major'];
                                    $num_pundit = $major_row['num_pundit'] ?: 0;
                                    $num_pundit_job = $major_row['num_pundit_job'] ?: 0;
                                    $num_pundit_job_work = $major_row['num_pundit_job_work'] ?: 0;

                                    // คำนวณร้อยละสำหรับสาขาวิชา
                                    $major_percentage = ($num_pundit > 0) ? number_format(($num_pundit_job / $num_pundit) * 100, 2) : 0;

                                    // ดึงชื่อสาขาวิชาจากข้อความเต็ม (หลังคำว่า --)
                                    $major_name = $major;
                                    if (strpos($major, '--') !== false) {
                                        $parts = explode('--', $major);
                                        $major_name = isset($parts[1]) ? $parts[1] : $major;
                                    }

                                    echo '<tr class="major-row">
                                        <td></td>
                                        <td class="major-name">' . $major_counter . '. ' . htmlspecialchars($major_name) . '</td>
                                        <td class="text-center">' . ($num_pundit > 0 ? $num_pundit : '-') . '</td>
                                        <td class="text-center">' . ($num_pundit_job > 0 ? $num_pundit_job : '-') . '</td>
                                        <td class="text-center">' . ($num_pundit_job_work > 0 ? $num_pundit_job_work : '-') . '</td>
                                        <td class="text-center">' . ($major_percentage > 0 ? $major_percentage : '-') . '</td>
                                    </tr>';

                                    $major_counter++;
                                }
                            } else {
                                echo '<tr>
                                    <td></td>
                                    <td colspan="5" class="text-center">ไม่พบข้อมูลสาขาวิชา</td>
                                </tr>';
                            }

                            $faculty_counter++;
                        }

                        // คำนวณร้อยละสำหรับยอดรวมทั้งหมด
                        $total_percentage = ($total_num_pundit > 0) ? number_format(($total_num_pundit_job / $total_num_pundit) * 100, 2) : 0;

                        // รวมยอดทั้งหมด
                        echo '<tr style="background-color: #e9ecef; font-weight: bold;">
                            <td colspan="2" class="text-center"><strong>รวมทั้งหมด</strong></td>
                            <td class="text-center"><strong>' . ($total_num_pundit > 0 ? $total_num_pundit : '-') . '</strong></td>
                            <td class="text-center"><strong>' . ($total_num_pundit_job > 0 ? $total_num_pundit_job : '-') . '</strong></td>
                            <td class="text-center"><strong>' . ($total_num_pundit_job_work > 0 ? $total_num_pundit_job_work : '-') . '</strong></td>
                            <td class="text-center"><strong>' . ($total_percentage > 0 ? $total_percentage : '-') . '</strong></td>
                        </tr>';
                    } else {
                        echo '<tr><td colspan="6" class="text-center">ไม่พบข้อมูลคณะ</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="notes-section">
            <h5>หมายเหตุ:</h5>
            <p>
                1. ข้อมูลแสดงจำนวนการทำบันทึกข้อตกลงระหว่างองค์กรกับคณะ (MOU) แยกตามปี พ.ศ.<br>
                2. ข้อมูลแสดงจำนวนบัณฑิต CWIE และการได้งานทำหลังสำเร็จการศึกษา<br>
                3. ร้อยละ คำนวณจาก (จำนวนบัณฑิต CWIE ที่ได้งานทำ / จำนวนบัณฑิต CWIE) × 100<br>
                4. ข้อมูล ณ วันที่ <?php echo date('d/m/Y'); ?>
            </p>
        </div>

        <div class="action-buttons">
            <button onclick="window.location.href='orgMouAdd.php'">กลับไปหน้าจัดการ</button>
            <button onclick="window.print()">พิมพ์รายงาน</button>
        </div>
    </div>
</body>

</html>