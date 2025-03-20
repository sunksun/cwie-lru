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
    <title>รายงานจำนวนสาขาวิชาและจำนวนนักศึกษาที่ออกฝึกในรูปแบบสหกิจศึกษา</title>
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

        h4,
        h3 {
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

        <h3 class="text-center mb-4">จำนวนสาขาวิชาและจำนวนนักศึกษาที่ออกฝึกในรูปแบบสหกิจศึกษา</h3>

        <table>
            <thead>
                <tr>
                    <th rowspan="2" class="align-middle" style="width: 5%">ลำดับที่</th>
                    <th rowspan="2" class="align-middle" style="width: 40%; text-align: left;">คณะ</th>
                    <th rowspan="2" class="align-middle" style="width: 10%">จำนวนนักศึกษาที่ออกฝึกทั้งหมด</th>
                    <th rowspan="2" class="align-middle" style="width: 12%">จำนวนนักศึกษาที่ออกฝึกภาคปกติ</th>
                    <th rowspan="2" class="align-middle" style="width: 13%">จำนวนนักศึกษาที่ออกฝึกในรูปแบบสหกิจฯ (CWIE)</th>
                    <th rowspan="2" class="align-middle" style="width: 10%">คิดเป็นร้อยละ</th>
                    <th rowspan="2" class="align-middle" style="width: 10%">หมายเหตุ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // ดึงข้อมูลตามสิทธิ์การเข้าถึง
                if ($username == 'admin') {
                    // ถ้าเป็น admin ดึงข้อมูลคณะทั้งหมด
                    $sql_faculty = "SELECT fid, faculty FROM faculty ORDER BY id ASC";
                    $result_faculty = $conn->query($sql_faculty);
                } else {
                    // ถ้าไม่ใช่ admin ดึงเฉพาะคณะของผู้ใช้
                    $sql_faculty = "SELECT fid, faculty FROM faculty WHERE fid = '$faculty_id'";
                    $result_faculty = $conn->query($sql_faculty);
                }

                // ตัวแปรสำหรับเก็บผลรวมทั้งหมด
                $grand_practice = 0;
                $grand_normal = 0;
                $grand_cwie = 0;

                if ($result_faculty && $result_faculty->num_rows > 0) {
                    $faculty_counter = 1;

                    while ($faculty_row = $result_faculty->fetch_assoc()) {
                        $faculty_id_current = $faculty_row['fid'];
                        $faculty_name = $faculty_row['faculty'];

                        // คำนวณยอดรวมสำหรับแต่ละคณะ
                        $sql_sum = "SELECT 
                            SUM(CASE WHEN num_practice = '' THEN 0 ELSE num_practice END) as total_practice, 
                            SUM(CASE WHEN num_cwie = '' THEN 0 ELSE num_cwie END) as total_cwie 
                            FROM num_stu_cwie 
                            WHERE faculty_id = '$faculty_id_current'";
                        $result_sum = $conn->query($sql_sum);
                        $row_sum = $result_sum->fetch_assoc();

                        $total_practice = $row_sum['total_practice'] ?: 0;
                        $total_cwie = $row_sum['total_cwie'] ?: 0;

                        // ตรวจสอบว่า CWIE ไม่ควรมากกว่าจำนวนทั้งหมด
                        $total_normal = max(0, $total_practice - $total_cwie);

                        // ถ้า CWIE มากกว่าจำนวนทั้งหมด ให้ใช้ CWIE เป็นจำนวนทั้งหมด
                        $total_practice = max($total_practice, $total_cwie);

                        // คำนวณร้อยละ
                        $percentage = ($total_practice > 0) ? min(100, number_format(($total_cwie / $total_practice) * 100, 2)) : 0;

                        // สะสมผลรวมทั้งหมด
                        $grand_practice += $total_practice;
                        $grand_normal += $total_normal;
                        $grand_cwie += $total_cwie;

                        // แสดงแถวของคณะ
                        echo '<tr class="faculty-row">
                            <td class="text-center">' . $faculty_counter . '</td>
                            <td class="text-left"><strong>' . $faculty_name . '</strong></td>
                            <td class="text-center">' . ($total_practice > 0 ? $total_practice : '-') . '</td>
                            <td class="text-center">' . ($total_normal > 0 ? $total_normal : '-') . '</td>
                            <td class="text-center">' . ($total_cwie > 0 ? $total_cwie : '-') . '</td>
                            <td class="text-center">' . ($percentage > 0 ? $percentage : '-') . '</td>
                            <td></td>
                        </tr>';

                        // ดึงข้อมูลสาขาวิชาตามคณะ
                        $sql_major = "SELECT major, num_practice, num_cwie, num_pundit, note 
                            FROM num_stu_cwie 
                            WHERE faculty_id = '$faculty_id_current' 
                            ORDER BY id ASC";
                        $result_major = $conn->query($sql_major);

                        if ($result_major && $result_major->num_rows > 0) {
                            $major_counter = 1;

                            while ($major_row = $result_major->fetch_assoc()) {
                                $major = $major_row['major'];
                                $num_practice = $major_row['num_practice'] ?: 0;
                                $num_cwie = $major_row['num_cwie'] ?: 0;
                                $note = $major_row['note'];

                                // ตรวจสอบค่าความถูกต้อง
                                $normal_practice = max(0, $num_practice - $num_cwie);

                                // ถ้า CWIE มากกว่าจำนวนทั้งหมด ให้ใช้ CWIE เป็นจำนวนทั้งหมด
                                if ($num_cwie > $num_practice) {
                                    $num_practice = $num_cwie;
                                    $normal_practice = 0;
                                }

                                // คำนวณร้อยละสำหรับสาขาวิชา
                                $major_percentage = ($num_practice > 0) ? min(100, number_format(($num_cwie / $num_practice) * 100, 2)) : 0;

                                // ดึงชื่อสาขาวิชาจากข้อความเต็ม (หลังคำว่า --)
                                $major_name = $major;
                                if (strpos($major, '--') !== false) {
                                    $parts = explode('--', $major);
                                    $major_name = isset($parts[1]) ? $parts[1] : $major;
                                }

                                echo '<tr class="major-row">
                                    <td></td>
                                    <td class="major-name">' . $major_counter . '. ' . htmlspecialchars($major_name) . '</td>
                                    <td class="text-center">' . ($num_practice > 0 ? $num_practice : '-') . '</td>
                                    <td class="text-center">' . ($normal_practice > 0 ? $normal_practice : '-') . '</td>
                                    <td class="text-center">' . ($num_cwie > 0 ? $num_cwie : '-') . '</td>
                                    <td class="text-center">' . ($major_percentage > 0 ? $major_percentage : '-') . '</td>
                                    <td>' . htmlspecialchars($note) . '</td>
                                </tr>';

                                $major_counter++;
                            }
                        } else {
                            echo '<tr>
                                <td></td>
                                <td colspan="6" class="text-center">ไม่พบข้อมูลสาขาวิชา</td>
                            </tr>';
                        }

                        $faculty_counter++;
                    }

                    // คำนวณร้อยละรวมทั้งหมด
                    $grand_percentage = ($grand_practice > 0) ? min(100, number_format(($grand_cwie / $grand_practice) * 100, 2)) : 0;

                    echo '<tr class="table-secondary" style="background-color: #e9ecef; font-weight: bold;">
                        <td colspan="2" class="text-center"><strong>รวมทั้งหมด</strong></td>
                        <td class="text-center"><strong>' . ($grand_practice > 0 ? $grand_practice : '-') . '</strong></td>
                        <td class="text-center"><strong>' . ($grand_normal > 0 ? $grand_normal : '-') . '</strong></td>
                        <td class="text-center"><strong>' . ($grand_cwie > 0 ? $grand_cwie : '-') . '</strong></td>
                        <td class="text-center"><strong>' . ($grand_percentage > 0 ? $grand_percentage : '-') . '</strong></td>
                        <td></td>
                    </tr>';
                } else {
                    echo '<tr><td colspan="7" class="text-center">ไม่พบข้อมูลคณะ</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <div class="notes-section">
            <h5>หมายเหตุ:</h5>
            <p>
                1. จำนวนนักศึกษาที่ออกฝึกทั้งหมด หมายถึง จำนวนนักศึกษาทั้งหมดที่ออกฝึกประสบการณ์วิชาชีพ<br>
                2. จำนวนนักศึกษาที่ออกฝึกภาคปกติ หมายถึง จำนวนนักศึกษาที่ออกฝึกงานทั่วไป<br>
                3. จำนวนนักศึกษาที่ออกฝึกในรูปแบบสหกิจฯ (CWIE) หมายถึง จำนวนนักศึกษาที่ออกฝึกตามรูปแบบสหกิจศึกษา<br>
                4. ร้อยละ คำนวณจาก (จำนวนนักศึกษาที่ออกฝึกในรูปแบบสหกิจฯ / จำนวนนักศึกษาที่ออกฝึกทั้งหมด) × 100
            </p>
        </div>

        <div class="action-buttons">
            <button onclick="window.location.href='numStuCwieAdd.php'">กลับไปหน้าจัดการ</button>
            <button onclick="window.print()">พิมพ์รายงาน</button>
        </div>
    </div>
</body>

</html>