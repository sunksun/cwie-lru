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
    <title>รายงานจำนวนคณาจารย์ที่นิเทศการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL)</title>
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

        .faculty-row {
            background-color: #f2f2f2;
            font-weight: bold;
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
            <h4>การจัดการเรียนรู้เชิงบูรณาการกับการทำงานในสถานศึกษา (School-Integrated Learning : SIL)</h4>
            <h4>มหาวิทยาลัยราชภัฏเลย</h4>
            <h4>ประจำภาคเรียนที่ <?php echo htmlspecialchars($year); ?></h4>
        </div>

        <h3 class="text-center mb-4">จำนวนคณาจารย์ที่นิเทศรูปแบบของการจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL)</h3>

        <table>
            <thead>
                <tr>
                    <th class="align-middle" style="width: 5%">ลำดับที่</th>
                    <th class="align-middle" style="width: 45%; text-align: left;">คณะ/สาขาวิชา</th>
                    <th class="align-middle" style="width: 16%">จำนวนคณาจารย์ทั้งหมด</th>
                    <th class="align-middle" style="width: 16%">จำนวนคณาจารย์ที่นิเทศ</th>
                    <th class="align-middle" style="width: 16%">คิดเป็นร้อยละ (%)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // ข้อมูลจำนวนคณาจารย์ทั้งหมดคณะครุศาสตร์ (ตามไฟล์ Word)
                $total_faculty_data = [
                    '05' => 91,  // คณะครุศาสตร์
                ];

                // ดึงข้อมูลเฉพาะคณะครุศาสตร์
                $sql_faculty = "SELECT fid, faculty FROM faculty WHERE fid = '05' ORDER BY id ASC";
                $result_faculty = $conn->query($sql_faculty);

                if ($result_faculty && $result_faculty->num_rows > 0) {
                    $counter = 1;
                    $grand_total_all = 0;
                    $grand_total_trained = 0;

                    while ($faculty_row = $result_faculty->fetch_assoc()) {
                        $faculty_id_current = $faculty_row['fid'];
                        $faculty_name = $faculty_row['faculty'];

                        // ข้อมูลจำนวนคณาจารย์ทั้งหมดคณะครุศาสตร์
                        $total_faculty = $total_faculty_data[$faculty_id_current] ?? 0;

                        // นับจำนวนคณาจารย์ที่นิเทศจากตาราง num_tea_cwie (หรืออาจเป็นตารางอื่นที่เก็บข้อมูล SIL)
                        $sql_count = "SELECT COUNT(*) as total FROM num_tea_cwie WHERE faculty_id = '$faculty_id_current'";
                        $result_count = $conn->query($sql_count);
                        $row_count = $result_count->fetch_assoc();
                        $total_trained = $row_count['total'] ?? 0;

                        // คำนวณร้อยละ
                        $percentage = $total_faculty > 0 ? number_format(($total_trained / $total_faculty) * 100, 2) : 0;

                        // สะสมผลรวมทั้งหมด
                        $grand_total_all += $total_faculty;
                        $grand_total_trained += $total_trained;

                        echo '<tr class="faculty-row">
                            <td class="text-center">' . $counter . '</td>
                            <td class="text-left"><strong>' . $faculty_name . '</strong></td>
                            <td class="text-center">' . ($total_faculty > 0 ? $total_faculty : '-') . '</td>
                            <td class="text-center">' . ($total_trained > 0 ? $total_trained : '-') . '</td>
                            <td class="text-center">' . ($percentage > 0 ? $percentage : '-') . '</td>
                        </tr>';

                        // ดึงข้อมูลสาขาวิชาของคณะครุศาสตร์
                        $sql_major = "SELECT major, COUNT(*) as total_major 
                                      FROM num_tea_cwie 
                                      WHERE faculty_id = '$faculty_id_current'
                                      GROUP BY major
                                      ORDER BY major ASC";
                        $result_major = $conn->query($sql_major);

                        if ($result_major && $result_major->num_rows > 0) {
                            $major_counter = 1;

                            while ($major_row = $result_major->fetch_assoc()) {
                                $major = $major_row['major'];
                                $total_major = $major_row['total_major'] ?: 0;

                                // ดึงชื่อสาขาวิชาจากข้อความเต็ม (หลังคำว่า --)
                                $major_name = $major;
                                if (strpos($major, '--') !== false) {
                                    $parts = explode('--', $major);
                                    $major_name = isset($parts[1]) ? $parts[1] : $major;
                                }

                                echo '<tr>
                                    <td></td>
                                    <td class="text-left" style="padding-left: 20px;">' . $major_counter . '. ' . htmlspecialchars($major_name) . '</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">' . ($total_major > 0 ? $total_major : '-') . '</td>
                                    <td class="text-center">-</td>
                                </tr>';

                                $major_counter++;
                            }
                        } else {
                            echo '<tr>
                                <td></td>
                                <td colspan="4" class="text-center">ไม่พบข้อมูลสาขาวิชา</td>
                            </tr>';
                        }

                        $counter++;
                    }

                    // คำนวณร้อยละสำหรับผลรวมทั้งหมด
                    $grand_percentage = $grand_total_all > 0 ? number_format(($grand_total_trained / $grand_total_all) * 100, 2) : 0;

                    // แสดงแถวสรุปผลรวม
                    echo '<tr style="background-color: #e9ecef; font-weight: bold;">
                        <td colspan="2" class="text-center"><strong>รวมทั้งหมด</strong></td>
                        <td class="text-center"><strong>' . ($grand_total_all > 0 ? $grand_total_all : '-') . '</strong></td>
                        <td class="text-center"><strong>' . ($grand_total_trained > 0 ? $grand_total_trained : '-') . '</strong></td>
                        <td class="text-center"><strong>' . ($grand_percentage > 0 ? $grand_percentage : '-') . '</strong></td>
                    </tr>';
                } else {
                    echo '<tr><td colspan="5" class="text-center">ไม่พบข้อมูลคณะครุศาสตร์</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <div class="notes-section">
            <h5>หมายเหตุ:</h5>
            <p>
                1. ข้อมูลแสดงจำนวนคณาจารย์ที่นิเทศนักศึกษาในรูปแบบการจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL)<br>
                2. จำนวนคณาจารย์ที่นิเทศ หมายถึง คณาจารย์ที่ทำหน้าที่นิเทศนักศึกษาฝึกปฏิบัติงานในสถานศึกษา<br>
                3. ร้อยละ คำนวณจาก (จำนวนคณาจารย์ที่นิเทศ / จำนวนคณาจารย์ทั้งหมด) × 100<br>
                4. ข้อมูล ณ วันที่ <?php echo date('d/m/Y'); ?>
            </p>
        </div>

        <div class="action-buttons">
            <button onclick="window.location.href='numTeachCwieAdd.php'">กลับไปหน้าจัดการ</button>
            <button onclick="window.print()">พิมพ์รายงาน</button>
        </div>
    </div>
</body>

</html>