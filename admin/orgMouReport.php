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
            /* Center text horizontally by default */
            vertical-align: middle;
            /* Center text vertically */
        }

        th {
            background-color: #f2f2f2;
        }

        .text-left {
            text-align: left;
            /* Align text to the left */
        }

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
    <?php
    if ($username == 'admin_edu') {
        echo '<h4>การจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL)</h4>';
    } else {
        echo '<h4>สหกิจศึกษาและการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE)</h4>';
    }
    ?>
    <h4><?php echo $faculty; ?> มหาวิทยาลัยราชภัฏเลย</h4>
    <h4>ประจำภาคเรียนที่ <?php echo $year; ?></h4>
    <?php
    if ($username == 'admin_edu') {
        echo '<p>สถานประกอบการที่ทำบันทึกข้อตกลงการจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL)</p>';
    } else {
        echo '<p>สถานประกอบการที่ทำบันทึกข้อตกลงหลักสูตรสหกิจศึกษาและการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE)</p>';
    }
    ?>

    <table id="reportTable">
        <thead>
            <tr>
                <td>ลำดับที่</td>
                <td>รายชื่อสถานประกอบการ</td>
                <td>ที่อยู่</td>
                <td>วันที่ทำ MOU</td>
                <td>ระยะเวลา MOU</td>
                <td>หมายเหตุ</td>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <hr>
    <button onclick="window.location.href='orgMouAdd.php?year=<?php echo $year; ?>'">ย้อนกลับ</button> <button onclick="window.print()">พิมพ์รายงาน</button>

    <script>
        // ส่งปีการศึกษาไปยัง API เพื่อดึงข้อมูล
        fetch('orgMouData.php?year=<?php echo $year; ?>')
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector('#reportTable tbody');

                if (data.length === 0) {
                    // ถ้าไม่มีข้อมูล แสดงข้อความว่างเปล่า
                    const tr = document.createElement('tr');
                    const td = document.createElement('td');
                    td.setAttribute('colspan', '6');
                    td.textContent = 'ไม่พบข้อมูล';
                    tr.appendChild(td);
                    tbody.appendChild(tr);
                } else {
                    // แสดงข้อมูลในตาราง
                    data.forEach(item => {
                        const tr = document.createElement('tr');

                        for (const key in item) {
                            const td = document.createElement('td');
                            td.textContent = item[key];

                            // Align 'name' and 'address' columns to the left
                            if (key === 'name' || key === 'address') {
                                td.classList.add('text-left');
                            }

                            tr.appendChild(td);
                        }
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
                td.setAttribute('colspan', '6');
                td.textContent = 'เกิดข้อผิดพลาดในการดึงข้อมูล';
                tr.appendChild(td);
                tbody.appendChild(tr);
            });
    </script>
</body>

</html>