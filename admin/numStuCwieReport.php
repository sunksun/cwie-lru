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
$year = "2/2566";
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
                margin: 0;
            }

            body {
                margin: 1cm;
            }

            button {
                display: none;
            }

            @page {
                size: auto;
                margin: 0mm;
            }
        }
    </style>
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
    <button onclick="history.back()">ย้อนกลับ</button> | <button onclick="window.print()">พิมพ์รายงาน</button>

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