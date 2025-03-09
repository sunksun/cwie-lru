<?php
session_start();
$user_img = $_SESSION['img'];
include_once('connect.php');
if (!isset($_SESSION['fullname']) || $_SESSION['fullname'] == '') {
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

// ดึงปีการศึกษาล่าสุดจากตาราง year หรือใช้ค่าจาก GET ถ้ามี
if (isset($_GET['year']) && !empty($_GET['year'])) {
    $year = $_GET['year'];
} else {
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
    <title>รายงาน : จำนวนนักศึกษาและบัณฑิต การจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL)</title>
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
    <div class="report-header">
        <h4>การจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL)</h4>
        <h4><?php echo $faculty; ?> มหาวิทยาลัยราชภัฏเลย</h4>
        <h4>ประจำภาคเรียนที่ <?php echo $year; ?></h4>
    </div>

    <div class="year-selector no-print">
        <form method="get" action="">
            <label for="yearSelect">เลือกปีการศึกษา: </label>
            <select id="yearSelect" name="year" onchange="this.form.submit()">
                <?php
                // ดึงข้อมูลปีการศึกษาทั้งหมด
                $year_query = "SELECT year FROM year ORDER BY id DESC";
                $year_result = mysqli_query($conn, $year_query);

                if ($year_result && mysqli_num_rows($year_result) > 0) {
                    while ($year_row = mysqli_fetch_assoc($year_result)) {
                        $selected = ($year_row['year'] == $year) ? 'selected' : '';
                        echo "<option value='" . $year_row['year'] . "' " . $selected . ">ปีการศึกษา " . $year_row['year'] . "</option>";
                    }
                }
                ?>
            </select>
        </form>
    </div>

    <p>จำนวนนักศึกษาและบัณฑิต การจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL)</p>
    <table id="reportTable">
        <thead>
            <tr>
                <th>ลำดับที่</th>
                <th class="text-left">สาขาวิชา</th>
                <th>นักศึกษาออกฝึกประสบการวิชาชีพ</th>
                <th>จำนวนนักศึกษา SIL</th>
                <th>บัณฑิต SIL (ที่สำเร็จการศึกษาปีที่ผ่านมา)</th>
                <th>บัณฑิต SIL ที่ได้งานทำ</th>
                <th>บัณฑิต SIL ที่ได้งานทำในสถานประกอบการ</th>
                <th>หมายเหตุ</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="8" class="no-data">กำลังโหลดข้อมูล...</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="summary-row">
                <td colspan="2" class="text-left">รวม</td>
                <td id="total-practice">0</td>
                <td id="total-cwie">0</td>
                <td id="total-pundit">0</td>
                <td id="total-pundit-job">0</td>
                <td id="total-pundit-job-work">0</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="controls">
        <button onclick="history.back()" class="no-print">ย้อนกลับ</button>
        <button onclick="window.print()" class="no-print">พิมพ์รายงาน</button>
    </div>

    <script>
        // ใช้ year parameter ในการดึงข้อมูล
        const selectedYear = '<?php echo $year; ?>';

        fetch('numStuSilData.php?year=' + encodeURIComponent(selectedYear))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                const tbody = document.querySelector('#reportTable tbody');
                tbody.innerHTML = ''; // ล้างข้อมูลเดิม

                if (data.length === 0) {
                    const tr = document.createElement('tr');
                    const td = document.createElement('td');
                    td.colSpan = 8;
                    td.className = 'no-data';
                    td.textContent = 'ไม่พบข้อมูล';
                    tr.appendChild(td);
                    tbody.appendChild(tr);
                } else {
                    // สร้างแถวข้อมูล
                    let totalPractice = 0;
                    let totalCwie = 0;
                    let totalPundit = 0;
                    let totalPunditJob = 0;
                    let totalPunditJobWork = 0;

                    data.forEach(item => {
                        const tr = document.createElement('tr');

                        // เพิ่มลำดับ
                        const tdSeq = document.createElement('td');
                        tdSeq.textContent = item.sequence;
                        tr.appendChild(tdSeq);

                        // เพิ่มชื่อสาขา
                        const tdMajor = document.createElement('td');
                        tdMajor.textContent = item.major;
                        tdMajor.className = 'text-left';
                        tr.appendChild(tdMajor);

                        // เพิ่มจำนวนนักศึกษาออกฝึก
                        const tdPractice = document.createElement('td');
                        tdPractice.textContent = item.num_practice;
                        tr.appendChild(tdPractice);
                        totalPractice += parseInt(item.num_practice || 0);

                        // เพิ่มจำนวนนักศึกษา SIL
                        const tdCwie = document.createElement('td');
                        tdCwie.textContent = item.num_cwie;
                        tr.appendChild(tdCwie);
                        totalCwie += parseInt(item.num_cwie || 0);

                        // เพิ่มจำนวนบัณฑิต SIL
                        const tdPundit = document.createElement('td');
                        tdPundit.textContent = item.num_pundit;
                        tr.appendChild(tdPundit);
                        totalPundit += parseInt(item.num_pundit || 0);

                        // เพิ่มจำนวนบัณฑิต SIL ที่ได้งาน
                        const tdPunditJob = document.createElement('td');
                        tdPunditJob.textContent = item.num_pundit_job;
                        tr.appendChild(tdPunditJob);
                        totalPunditJob += parseInt(item.num_pundit_job || 0);

                        // เพิ่มจำนวนบัณฑิต SIL ที่ได้งานในสถานประกอบการ
                        const tdPunditJobWork = document.createElement('td');
                        tdPunditJobWork.textContent = item.num_pundit_job_work;
                        tr.appendChild(tdPunditJobWork);
                        totalPunditJobWork += parseInt(item.num_pundit_job_work || 0);

                        // เพิ่มหมายเหตุ
                        const tdNote = document.createElement('td');
                        tdNote.textContent = item.note || '';
                        tr.appendChild(tdNote);

                        tbody.appendChild(tr);
                    });

                    // อัปเดตยอดรวม
                    document.getElementById('total-practice').textContent = totalPractice;
                    document.getElementById('total-cwie').textContent = totalCwie;
                    document.getElementById('total-pundit').textContent = totalPundit;
                    document.getElementById('total-pundit-job').textContent = totalPunditJob;
                    document.getElementById('total-pundit-job-work').textContent = totalPunditJobWork;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const tbody = document.querySelector('#reportTable tbody');
                tbody.innerHTML = ''; // ล้างข้อมูลเดิม

                const tr = document.createElement('tr');
                const td = document.createElement('td');
                td.colSpan = 8;
                td.className = 'no-data';
                td.textContent = 'เกิดข้อผิดพลาดในการโหลดข้อมูล: ' + error.message;
                tr.appendChild(td);
                tbody.appendChild(tr);
            });
    </script>
</body>

</html>