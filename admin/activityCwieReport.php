<?php
session_start();
$user_img = $_SESSION['img'];
include_once('connect.php');
if ($_SESSION['fullname'] == '') {
    echo '<script language="javascript">';
    echo 'alert("กรุณา Login เข้าสู่ระบบ"); location.href="login.php"';
    echo '</script>';
    exit;
}
$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];

// รับค่าปีการศึกษาจาก URL หรือใช้ค่าเริ่มต้น
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานกิจกรรม CWIE ปีการศึกษา <?php echo $year; ?></title>
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
    <script>
        // ฟังก์ชันสำหรับพิมพ์เอกสาร
        function printReport() {
            window.print();
        }
    </script>
</head>

<body>
    <?php
    if ($username == 'admin_edu') {
        echo '<h4>การจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL)</h4>';
    } else {
        echo '<h4>สหกิจศึกษาและการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE)</h4>';
    }
    ?>
    <h4><?php echo $faculty; ?></h4>
    <h4>มหาวิทยาลัยราชภัฏเลย</h4>
    <h4>ประจำภาคเรียนที่ <?php echo $year; ?></h4>
    <?php
    if ($username == 'admin_edu') {
        echo '<p>กิจกรรมการจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL) (กิจกรรมของนักศึกษา)</p>';
    } else {
        echo '<p>กิจกรรมการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE) ที่เกี่ยวข้อง (กิจกรรมของนักศึกษา)</p>';
    }
    ?>
    <table id="reportTable">
        <thead>
            <tr>
                <th>ลำดับที่</th>
                <th>กิจกรรม</th>
                <th>สาขาวิชา</th>
                <th>วันที่ดำเนินการ</th>
                <th>จำนวนผู้เข้าร่วม</th>
                <th>หมายเหตุ</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" class="no-data">กำลังโหลดข้อมูล...</td>
            </tr>
        </tbody>
    </table>

    <?php
    if ($username == 'admin_edu') {
        echo '<p>กิจกรรมการจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL) (กิจกรรมอาจารย์)</p>';
    } else {
        echo '<p>กิจกรรมการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE) ที่เกี่ยวข้อง (กิจกรรมอาจารย์)</p>';
    }
    ?>
    <table id="reportTable2">
        <thead>
            <tr>
                <th>ลำดับที่</th>
                <th>กิจกรรม</th>
                <th>สาขาวิชา</th>
                <th>วันที่ดำเนินการ</th>
                <th>จำนวนผู้เข้าร่วม</th>
                <th>หมายเหตุ</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" class="no-data">กำลังโหลดข้อมูล...</td>
            </tr>
        </tbody>
    </table>

    <?php
    if ($username == 'admin_edu') {
        echo '<p>กิจกรรมการจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL) (กิจกรรมร่วมกับสถานประกอบการ)</p>';
    } else {
        echo '<p>กิจกรรมการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE) ที่เกี่ยวข้อง (กิจกรรมร่วมกับสถานประกอบการ)</p>';
    }
    ?>
    <table id="reportTable3">
        <thead>
            <tr>
                <th>ลำดับที่</th>
                <th>กิจกรรม</th>
                <th>สาขาวิชา</th>
                <th>วันที่ดำเนินการ</th>
                <th>จำนวนผู้เข้าร่วม</th>
                <th>หมายเหตุ</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" class="no-data">กำลังโหลดข้อมูล...</td>
            </tr>
        </tbody>
    </table>

    <div class="button-container">
        <button class="back" onclick="window.location.href='activityCwieAdd.php?year=<?php echo $year; ?>'">กลับไปหน้าจัดการ</button>
        <button onclick="printReport()">พิมพ์รายงาน</button>
    </div>

    <script>
        // ฟังก์ชันสำหรับแสดงข้อความ "ไม่พบข้อมูล" ถ้าไม่มีข้อมูล
        function showNoData(tableId) {
            const tbody = document.querySelector(`#${tableId} tbody`);
            tbody.innerHTML = `<tr><td colspan="6" class="no-data">ไม่พบข้อมูล</td></tr>`;
        }

        // ดึงข้อมูลกิจกรรมนักศึกษา
        fetch('activityCwieData.php?year=<?php echo $year; ?>')
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector('#reportTable tbody');
                tbody.innerHTML = '';

                if (data.length === 0) {
                    showNoData('reportTable');
                    return;
                }

                data.forEach(item => {
                    const tr = document.createElement('tr');

                    for (const key in item) {
                        const td = document.createElement('td');
                        td.textContent = item[key] || '-';

                        // Align text to left for activity name and course
                        if (key === 'activity_name' || key === 'course') {
                            td.classList.add('text-left');
                        }

                        tr.appendChild(td);
                    }
                    tbody.appendChild(tr);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                showNoData('reportTable');
            });

        // ดึงข้อมูลกิจกรรมอาจารย์
        fetch('activityCwieData2.php?year=<?php echo $year; ?>')
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector('#reportTable2 tbody');
                tbody.innerHTML = '';

                if (data.length === 0) {
                    showNoData('reportTable2');
                    return;
                }

                data.forEach(item => {
                    const tr = document.createElement('tr');

                    for (const key in item) {
                        const td = document.createElement('td');
                        td.textContent = item[key] || '-';

                        // Align text to left for activity name and course
                        if (key === 'activity_name' || key === 'course') {
                            td.classList.add('text-left');
                        }

                        tr.appendChild(td);
                    }
                    tbody.appendChild(tr);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                showNoData('reportTable2');
            });

        // ดึงข้อมูลกิจกรรมร่วมกับสถานประกอบการ
        fetch('activityCwieData3.php?year=<?php echo $year; ?>')
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector('#reportTable3 tbody');
                tbody.innerHTML = '';

                if (data.length === 0) {
                    showNoData('reportTable3');
                    return;
                }

                data.forEach(item => {
                    const tr = document.createElement('tr');

                    for (const key in item) {
                        const td = document.createElement('td');
                        td.textContent = item[key] || '-';

                        // Align text to left for activity name and course
                        if (key === 'activity_name' || key === 'course') {
                            td.classList.add('text-left');
                        }

                        tr.appendChild(td);
                    }
                    tbody.appendChild(tr);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                showNoData('reportTable3');
            });
    </script>
</body>

</html>