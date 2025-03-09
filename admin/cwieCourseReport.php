<?php
session_start();
$user_img = $_SESSION['img'];
include_once('connect.php');
if (!isset($_SESSION['fullname']) || $_SESSION['fullname'] == '') {
    echo '<script language="javascript">';
    echo 'alert("กรุณา Login เข้าสู่ระบบ"); location.href="login.php"';
    echo '</script>';
    exit; // เพิ่ม exit เพื่อหยุดการทำงานหากไม่ได้เข้าสู่ระบบ
}
$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];
$year = isset($_GET['year']) ? $_GET['year'] : (isset($_SESSION['selected_year']) ? $_SESSION['selected_year'] : "2/2566");
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
    <h4><?php echo htmlspecialchars($faculty); ?></h4>
    <h4>มหาวิทยาลัยราชภัฏเลย</h4>
    <h4>ประจำภาคเรียนที่ <?php echo htmlspecialchars($year); ?></h4>
    <p>หลักสูตรที่มีการเรียนการสอนแบบสหกิจศึกษาและการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE) </p>
    <table id="reportTable">
        <thead>
            <tr>
                <td rowspan="2">ลำดับที่</td>
                <td rowspan="2" class="text-left">สาขาวิชา</td>
                <td colspan="4">
                    รูปแบบสหกิจศึกษาและการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE)
                </td>
            </tr>
            <tr>
                <td>แบบแยก (Separate)</td>
                <td>แบบคู่ขนาน (Parallel)</td>
                <td>แบบผสม (Mix)</td>
                <td>หมายเหตุ</td>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <hr>
    <button onclick="window.location.href='cwieCourseAdd.php'">กลับไปหน้าจัดการ</button> <button onclick="window.print()">พิมพ์รายงาน</button>

    <script>
        fetch('cwieCourseData.php')
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