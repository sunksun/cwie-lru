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
    <title>รายงาน : จำนวนนักศึกษาและบัณฑิต ในรูปแบบของการจัดหลักสูตรสหกิจศึกษาและการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE)</title>
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
    <script>
        // ฟังก์ชันสำหรับพิมพ์เอกสาร
        function printReport() {
            // เก็บชื่อเดิมของ title
            const originalTitle = document.title;

            // เปลี่ยน title เป็นค่าว่างก่อนพิมพ์
            document.title = '';

            // สั่งพิมพ์
            window.print();

            // คืนค่า title กลับเป็นค่าเดิมหลังจากพิมพ์
            setTimeout(function() {
                document.title = originalTitle;
            }, 100);
        }
    </script>
</head>

<body>
    <h4>สหกิจศึกษาและการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE)</h4>
    <h4><?php echo $faculty; ?></h4>
    <h4>มหาวิทยาลัยราชภัฏเลย</h4>
    <h4>ประจำภาคเรียนที่ <?php echo $year; ?></h4>
    <p>จำนวนนักศึกษาและบัณฑิต ในรูปแบบของการจัดหลักสูตรสหกิจศึกษาและการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE)</p>
    <table id="reportTable">
        <thead>
            <tr>
                <td>ลำดับที่</td>
                <td class="text-left">สาขาวิชา</td>
                <td>นักศึกษาออกฝึกประสบการวิชาชีพ</td>
                <td>จำนวนนักศึกษาสหกิจศึกษา</td>
                <td>บัณฑิต CWIE (ที่สำเร็จการศึกษาปีที่ผ่านมา)</td>
                <td>บัณฑิต CWIE ที่ได้งานทำ</td>
                <td>บัณฑิต CWIE ที่ได้งานทำในสถานประกอบการ</td>
                <td>หมายเหตุ</td>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <hr>
    <button onclick="history.back()">กลับไปหน้าจัดการ</button> <button onclick="printReport()">พิมพ์รายงาน</button>

    <script>
        fetch('numStuCwieData.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector('#reportTable tbody');
                data.forEach(item => {
                    const tr = document.createElement('tr');

                    for (const key in item) {
                        const td = document.createElement('td');
                        td.textContent = item[key];

                        // Align 'major' column to the right
                        if (key === 'major') {
                            td.classList.add('text-left');
                        }

                        tr.appendChild(td);
                    }
                    tbody.appendChild(tr);
                });
            })
            .catch(error => console.error('Error:', error));
    </script>
</body>

</html>