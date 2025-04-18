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
    <title>รายงานกิจกรรมการจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL)</title>
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
            <h4>การจัดการเรียนรู้เชิงบูรณาการกับการทำงานในสถานศึกษา (School-Integrated Learning : SIL)</h4>
            <h4>มหาวิทยาลัยราชภัฏเลย</h4>
            <h4>ประจำภาคเรียนที่ <?php echo htmlspecialchars($year); ?></h4>
        </div>

        <!-- กิจกรรมนักศึกษา -->
        <div class="activity-section">
            <h3 class="text-center">กิจกรรมนักศึกษา</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">ลำดับ</th>
                        <th style="width: 20%">คณะ</th>
                        <th style="width: 25%">ชื่อกิจกรรม</th>
                        <th style="width: 25%">หลักสูตร/สาขาวิชา</th>
                        <th style="width: 15%">วันที่จัดกิจกรรม</th>
                        <th style="width: 10%">จำนวนผู้เข้าร่วม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // สร้างเงื่อนไขการกรองเฉพาะคณะครุศาสตร์
                    $where_condition = "activity_type = 'กิจกรรมนักศึกษา' AND faculty_id = '05'";
                    if (isset($_GET['year']) && !empty($_GET['year'])) {
                        $where_condition .= " AND year = '" . $conn->real_escape_string($_GET['year']) . "'";
                    }

                    // ดึงข้อมูลกิจกรรมนักศึกษา
                    $sql_student = "SELECT ac.*, f.faculty AS faculty_name 
                                    FROM activity_cwie ac 
                                    LEFT JOIN faculty f ON ac.faculty_id = f.fid 
                                    WHERE $where_condition
                                    ORDER BY ac.date_regis DESC";
                    $result_student = $conn->query($sql_student);

                    if ($result_student && $result_student->num_rows > 0) {
                        $counter = 1;
                        while ($row = $result_student->fetch_assoc()) {
                            echo '<tr>
                                <td class="text-center">' . $counter . '</td>
                                <td class="text-left">' . htmlspecialchars($row['faculty_name']) . '</td>
                                <td class="text-left">' . htmlspecialchars($row['activity_name']) . '</td>
                                <td class="text-left">' . htmlspecialchars($row['course']) . '</td>
                                <td class="text-center">' . htmlspecialchars($row['activity_date']) . '</td>
                                <td class="text-center">' . htmlspecialchars($row['amount']) . '</td>
                            </tr>';
                            $counter++;
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center">ไม่พบข้อมูลกิจกรรมนักศึกษา</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- กิจกรรมอาจารย์ -->
        <div class="activity-section">
            <h3 class="text-center">กิจกรรมอาจารย์</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">ลำดับที่</th>
                        <th style="width: 20%">คณะ</th>
                        <th style="width: 25%">ชื่อกิจกรรม</th>
                        <th style="width: 25%">หลักสูตร/สาขาวิชา</th>
                        <th style="width: 15%">วันที่จัดกิจกรรม</th>
                        <th style="width: 10%">จำนวนผู้เข้าร่วม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // สร้างเงื่อนไขการกรองเฉพาะคณะครุศาสตร์
                    $where_condition = "activity_type = 'กิจกรรมอาจารย์' AND faculty_id = '05'";
                    if (isset($_GET['year']) && !empty($_GET['year'])) {
                        $where_condition .= " AND year = '" . $conn->real_escape_string($_GET['year']) . "'";
                    }

                    // ดึงข้อมูลกิจกรรมอาจารย์
                    $sql_teacher = "SELECT ac.*, f.faculty AS faculty_name 
                                   FROM activity_cwie ac 
                                   LEFT JOIN faculty f ON ac.faculty_id = f.fid 
                                   WHERE $where_condition
                                   ORDER BY ac.date_regis DESC";
                    $result_teacher = $conn->query($sql_teacher);

                    if ($result_teacher && $result_teacher->num_rows > 0) {
                        $counter = 1;
                        while ($row = $result_teacher->fetch_assoc()) {
                            echo '<tr>
                                <td class="text-center">' . $counter . '</td>
                                <td class="text-left">' . htmlspecialchars($row['faculty_name']) . '</td>
                                <td class="text-left">' . htmlspecialchars($row['activity_name']) . '</td>
                                <td class="text-left">' . htmlspecialchars($row['course']) . '</td>
                                <td class="text-center">' . htmlspecialchars($row['activity_date']) . '</td>
                                <td class="text-center">' . htmlspecialchars($row['amount']) . '</td>
                            </tr>';
                            $counter++;
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center">ไม่พบข้อมูลกิจกรรมอาจารย์</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- กิจกรรมร่วมกับสถานศึกษา -->
        <div class="activity-section">
            <h3 class="text-center">กิจกรรมร่วมกับสถานศึกษา</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">ลำดับที่</th>
                        <th style="width: 20%">คณะ</th>
                        <th style="width: 25%">ชื่อกิจกรรม</th>
                        <th style="width: 25%">หลักสูตร/สาขาวิชา</th>
                        <th style="width: 15%">วันที่จัดกิจกรรม</th>
                        <th style="width: 10%">จำนวนผู้เข้าร่วม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // สร้างเงื่อนไขการกรองเฉพาะคณะครุศาสตร์
                    $where_condition = "activity_type = 'กิจกรรมร่วมกับสถานประกอบการ' AND faculty_id = '05'";
                    if (isset($_GET['year']) && !empty($_GET['year'])) {
                        $where_condition .= " AND year = '" . $conn->real_escape_string($_GET['year']) . "'";
                    }

                    // ดึงข้อมูลกิจกรรมร่วมกับสถานศึกษา
                    $sql_org = "SELECT ac.*, f.faculty AS faculty_name 
                               FROM activity_cwie ac 
                               LEFT JOIN faculty f ON ac.faculty_id = f.fid 
                               WHERE $where_condition
                               ORDER BY ac.date_regis DESC";
                    $result_org = $conn->query($sql_org);

                    if ($result_org && $result_org->num_rows > 0) {
                        $counter = 1;
                        while ($row = $result_org->fetch_assoc()) {
                            echo '<tr>
                                <td class="text-center">' . $counter . '</td>
                                <td class="text-left">' . htmlspecialchars($row['faculty_name']) . '</td>
                                <td class="text-left">' . htmlspecialchars($row['activity_name']) . '</td>
                                <td class="text-left">' . htmlspecialchars($row['course']) . '</td>
                                <td class="text-center">' . htmlspecialchars($row['activity_date']) . '</td>
                                <td class="text-center">' . htmlspecialchars($row['amount']) . '</td>
                            </tr>';
                            $counter++;
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center">ไม่พบข้อมูลกิจกรรมร่วมกับสถานศึกษา</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="notes-section">
            <h5>หมายเหตุ:</h5>
            <p>
                1. ข้อมูลแสดงกิจกรรมด้านการจัดการเรียนรู้เชิงบูรณาการกับการทำงานในสถานศึกษา (SIL) แยกตามประเภทกิจกรรม<br>
                2. ข้อมูล ณ วันที่ <?php echo date('d/m/Y'); ?>
            </p>
        </div>

        <div class="action-buttons">
            <button onclick="window.location.href='activityCwieAdd.php?year=<?php echo htmlspecialchars($year); ?>'">กลับไปหน้าจัดการ</button>
            <button onclick="window.print()">พิมพ์รายงาน</button>
        </div>
    </div>
</body>

</html>