<?php
session_start();
$user_img = $_SESSION['img'];
include_once('connect.php');
if ($_SESSION['fullname'] == '') {
    echo '<script language="javascript">';
    echo 'alert("กรุณา Login เข้าสู่ระบบ"); location.href="login.php"';
    echo '</script>';
}
$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];

// ดึงปีการศึกษาล่าสุดจากตาราง year
$latest_year_query = "SELECT year FROM year ORDER BY id DESC LIMIT 1";
$latest_year_result = mysqli_query($conn, $latest_year_query);

if ($latest_year_result && mysqli_num_rows($latest_year_result) > 0) {
    $latest_year_row = mysqli_fetch_assoc($latest_year_result);
    $year = $latest_year_row['year'];
} else {
    $year = "2/2566"; // ค่าเริ่มต้นกรณีไม่พบข้อมูล
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แสดงผลการรายงาน</title>
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
    <h4>การจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL)</h4>
    <h4><?php echo $faculty; ?> มหาวิทยาลัยราชภัฏเลย</h4>
    <h4>ประจำภาคเรียนที่ <?php echo $year; ?></h4>
    <p>หลักสูตรที่มีการเรียนการสอนแบบการจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL) </p>
    <table id="reportTable">
        <thead>
            <tr>
                <td rowspan="2">ลำดับที่</td>
                <td rowspan="2" class="text-left">สาขาวิชา</td>
                <td>
                    การจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL)
                </td>
                <td rowspan="2">หมายเหตุ</td>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <hr>
    <button onclick="history.back()">ย้อนกลับ</button> <button onclick="window.print()">พิมพ์รายงาน</button>

    <script>
        // ส่งค่าปีการศึกษาไปยัง silCourseData.php
        fetch('silCourseData.php?year=<?php echo $year; ?>')
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector('#reportTable tbody');

                if (data.length === 0) {
                    // กรณีไม่มีข้อมูล
                    const tr = document.createElement('tr');
                    const td = document.createElement('td');
                    td.textContent = 'ไม่พบข้อมูล';
                    td.colSpan = 4; // ขยาย column ให้ครอบคลุมทั้งตาราง
                    td.style.textAlign = 'center';
                    tr.appendChild(td);
                    tbody.appendChild(tr);
                } else {
                    // กรณีมีข้อมูล
                    data.forEach(item => {
                        const tr = document.createElement('tr');

                        // Create and append sequence cell
                        const sequenceTd = document.createElement('td');
                        sequenceTd.textContent = item.sequence;
                        tr.appendChild(sequenceTd);

                        // Create and append major cell with text-left class
                        const majorTd = document.createElement('td');
                        majorTd.textContent = item.major;
                        majorTd.classList.add('text-left');
                        tr.appendChild(majorTd);

                        // Create and append SIL cell
                        const silTd = document.createElement('td');
                        silTd.textContent = item.sil || '-';
                        tr.appendChild(silTd);

                        // Create and append note cell
                        const noteTd = document.createElement('td');
                        noteTd.textContent = item.note || '-';
                        tr.appendChild(noteTd);

                        tbody.appendChild(tr);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // แสดงข้อความเมื่อเกิดข้อผิดพลาด
                const tbody = document.querySelector('#reportTable tbody');
                const tr = document.createElement('tr');
                const td = document.createElement('td');
                td.textContent = 'เกิดข้อผิดพลาดในการโหลดข้อมูล';
                td.colSpan = 4;
                td.style.textAlign = 'center';
                tr.appendChild(td);
                tbody.appendChild(tr);
            });
    </script>
</body>

</html>