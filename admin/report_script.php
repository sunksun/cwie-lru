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
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
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
    <h4>คณะวิทยาศาสตร์และเทคโนโลยี</h4>
    <h4>มหาวิทยาลัยราชภัฏเลย</h4>
    <h4>ประจำภาคเรียนที่ 2 ปีการศึกษา 2566</h4>
    <p>หลักสูตรที่มีการเรียนการสอนแบบสหกิจศึกษาและการจัดการเรียนรู้เชิงบรูณาการกับการทำงาน (CWIE) </p>
    <table id="reportTable">
        <thead>
            <tr>
                <td rowspan="2">ลำดับที่</td>
                <td rowspan="2">สาขาวิชา</td>
                <td colspan="3">
                    รูปแบบสหกิจศึกษาและการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE)
                </td>
                <td>&nbsp;</td>
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
    <button onclick="window.print()">Print Report</button>

    <script>
        fetch('example.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector('#reportTable tbody');
                data.forEach(item => {
                    const tr = document.createElement('tr');
                    for (const key in item) {
                        const td = document.createElement('td');
                        td.textContent = item[key];
                        tr.appendChild(td);
                    }
                    tbody.appendChild(tr);
                });
            })
            .catch(error => console.error('Error:', error));
    </script>
</body>

</html>