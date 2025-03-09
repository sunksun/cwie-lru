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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | อาจารย์นิเทศสหกิจฯ</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
    #openReportButton {
      background-color: #04AA6D;
      /* Green */
      border: none;
      color: white;
      padding: 5px 5px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 14px;
      /* ปรับความกว้างเท่ากับ 200px */
    }
  </style>
</head>

<body class="hold-transition sidebar-mini">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    <?php include_once 'navbar.php'; ?>

    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php include_once 'sidebar.php'; ?>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">


      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><a href="#">อาจารย์นิเทศหลักสูตรสหกิจศึกษาฯ (CWIE) | การจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL)</a></h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="row form-group">
                  <div class="col-12">
                    <label>ปีการศึกษา</label>
                    <select id="yearSelect" class="form-control select2" name="year" style="width: 100%;" onchange="changeYear(this.value)">
                      <?php
                      $sql = "SELECT * FROM year ORDER BY id DESC";
                      $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                        while ($optionData = $result->fetch_assoc()) {
                          $option = $optionData['year'];
                          $selected = ($option == $year) ? 'selected' : '';
                      ?>
                          <option value="<?php echo $option; ?>" <?php echo $selected; ?>>ปีการศึกษา <?php echo $option; ?></option>
                      <?php
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <form action="numTeachCwieSave.php" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="year" value="<?php echo $year; ?>">
                  <div class="form-group">
                    <label for="inputClientCompany">สาขาวิชา</label>
                    <input type="text" class="form-control" id="live_search" name="course" tabindex="1" placeholder="ค้นหาสาขาวิชา....">
                  </div>
                  <div>
                    <ul id="search_result" class="list-group ullist"></ul>
                  </div>
                  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                  <script type="text/javascript">
                    $(document).ready(function() {
                      $("#live_search").keyup(function() {
                        var query = $(this).val();
                        if (query != "") {
                          $.ajax({
                            url: 'ajax-db-search.php',
                            method: 'POST',
                            data: {
                              query: query
                            },
                            success: function(data) {
                              $('#search_result').html(data);
                              $('#search_result').css('display', 'block');
                              $("#search_result li").click(function() {
                                var value = $(this).html();
                                $('#live_search').val(value);
                                $('#search_result').css('display', 'none');
                              });
                            }
                          });
                        } else {
                          $('#search_result').css('display', 'none');
                        }
                      });
                    });
                  </script>

                  <div class="row form-group">
                    <div class="col-6">
                      <label for="inputClientCompany">ชื่ออาจารย์นิเทศ</label>
                      <input type="text" name="name_tea_cwie" class="form-control" required>
                    </div>
                    <div class="col-6">
                      <label for="inputClientCompany">หมายเลขประจำตัวผู้ขึ้นทะเบียน</label>
                      <input type="text" name="num_tea_cwie" class="form-control" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputClientCompany">หมายเหตุ</label>
                    <input type="text" name="note" id="inputClientCompany" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="inputClientCompany">รูปภาพ (ขนาด 220x220 px)</label>
                    <input class="form-control" type="file" name="filename" id="fileToUpload">
                  </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

        </div>
        <div class="row">
          <div class="col-12">
            <input type="submit" name="save" value="บันทึกข้อมูล" class="btn btn-success float-left">
          </div>
        </div>
        </form>
      </section>
      <!-- /.content -->
      <hr>
      <?php
      // ตรวจสอบว่ามีคอลัมน์ year ในตารางหรือไม่
      $check_column = mysqli_query($conn, "SHOW COLUMNS FROM num_tea_cwie LIKE 'year'");
      $column_exists = mysqli_num_rows($check_column) > 0;

      if ($column_exists) {
        // ถ้ามีคอลัมน์ year ให้กรองข้อมูลตามปีการศึกษาด้วย
        $sql = "SELECT * FROM num_tea_cwie WHERE faculty_id = '$faculty_id' AND year = '$year' ORDER BY id DESC";
      } else {
        // ถ้าไม่มีคอลัมน์ year ให้ดึงข้อมูลตาม faculty_id อย่างเดียว
        $sql = "SELECT * FROM num_tea_cwie WHERE faculty_id = '$faculty_id' ORDER BY id DESC";
      }

      $result = $conn->query($sql);
      ?>
      <section class="content">

        <!-- Default box -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">จำนวนอาจารย์นิเทศหลักสูตรสหกิจศึกษาฯ (CWIE) | การจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL) ปีการศึกษา <?php echo $year; ?></h3>
            <a href="numTeachCwieReport.php?year=<?php echo $year; ?>" id="openReportButton" class="btn btn-secondary float-right">พิมพ์รายงาน</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ลำดับ</th>
                  <th>สาขาวิชา</th>
                  <th>ชื่อ-นามสกุล</th>
                  <th>รหัสขึ้นทะเบียน</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                  $i = 1;
                  while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo htmlspecialchars($row["course"]); ?></td>
                      <td><?php echo htmlspecialchars($row["name_tea_cwie"]); ?></td>
                      <td><?php echo htmlspecialchars($row["num_tea_cwie"]); ?></td>
                      <td class="project-actions text-right">
                        <a class="btn btn-info btn-sm" href="numTeachCwieEdit.php?numTeacid=<?php echo $row["id"]; ?>&year=<?php echo $year; ?>">
                          <i class="fas fa-pencil-alt">
                          </i>
                          Edit
                        </a>
                        <a class="btn btn-danger btn-sm" href="JavaScript:if(confirm('ยืนยันการลบข้อมูล?')==true){window.location='numTeachCwieDel.php?numTeachid=<?php echo $row["id"]; ?>&year=<?php echo $year; ?>';}">
                          <i class="fas fa-trash">
                          </i>
                          Delete
                        </a>
                      </td>
                    </tr>
                <?php
                    $i++;
                  }
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>ลำดับ</th>
                  <th>สาขาวิชา</th>
                  <th>ชื่อ-นามสกุล</th>
                  <th>รหัสขึ้นทะเบียน</th>
                  <th></th>
                </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

      </section>
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
      <div class="float-right d-none d-sm-block">
        <b>Version</b> 3.2.0
      </div>
      <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- DataTables  & Plugins -->
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="plugins/jszip/jszip.min.js"></script>
  <script src="plugins/pdfmake/pdfmake.min.js"></script>
  <script src="plugins/pdfmake/vfs_fonts.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- Page specific script -->
  <script>
    $(function() {
      $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });

    // ฟังก์ชันสำหรับเปลี่ยนปีการศึกษา
    function changeYear(selectedYear) {
      window.location.href = 'numTeachCwieAdd.php?year=' + selectedYear;
    }
  </script>

</body>

</html>