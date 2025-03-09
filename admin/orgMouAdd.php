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

// รับค่า year จาก URL ถ้ามี
if (isset($_GET['year'])) {
  $year = $_GET['year'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | สถานประกอบการที่ทำบันทึก MOU CWIE</title>

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
                <h3 class="card-title"><a href="#">เพิ่มสถานประกอบการที่ทำบันทึกข้อตกลงหลักสูตรสหกิจศึกษาฯ (CWIE) | การจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL)</a></h3>

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
                <form action="orgMouSave.php" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="year" value="<?php echo $year; ?>">
                  <div class="form-group">
                    <label for="inputClientCompany">ชื่อสถานประกอบการ/โรงเรียน</label>
                    <input type="text" name="name" id="inputClientCompany" class="form-control" required>
                  </div>
                  <div class="row form-group">
                    <div class="col-5">
                      <label for="inputClientCompany">ที่อยู่</label>
                      <input type="text" name="address" class="form-control">
                    </div>
                    <div class="col-4">
                      <label for="inputClientCompany">แขวง/ตำบล</label>
                      <input type="text" name="subdistrict" class="form-control">
                    </div>
                    <div class="col-3">
                      <label for="inputClientCompany">เขต/อำเภอ</label>
                      <input type="text" name="district" class="form-control">
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-6">
                      <label>จังหวัด</label>
                      <select class="form-control select2" name="province" style="width: 100%;">
                        <option selected="selected">--- เลือกจังหวัด ---</option>
                        <?php
                        $sql = "SELECT * FROM thai_provinces";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                          while ($optionData = $result->fetch_assoc()) {
                            $option = $optionData['thai_province'];
                        ?>
                            <option value="<?php echo $option; ?>"> <?php echo $option; ?></option>
                        <?php
                          }
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-6">
                      <label for="inputClientCompany">รหัสไปรษณีย์</label>
                      <input type="text" name="postcode" class="form-control">
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-2">
                      <label for="inputClientCompany">วันที่ทำ MOU</label>
                      <select id="inputState" class="form-control" name="date_mou1">
                        <option selected>วันที่</option>
                        <?php
                        $sql = "SELECT * FROM day";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                          while ($optionData = $result->fetch_assoc()) {
                            $option = $optionData['day'];
                        ?>
                            <option value="<?php echo $option; ?>"> <?php echo $option; ?></option>
                        <?php
                          }
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-2">
                      <label for="inputClientCompany">&nbsp;</label>
                      <select id="inputState" class="form-control" name="date_mou2">
                        <option selected>เดือน</option>
                        <?php
                        $sql = "SELECT * FROM mount";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                          while ($optionData = $result->fetch_assoc()) {
                            $option = $optionData['mount'];
                        ?>
                            <option value="<?php echo $option; ?>"> <?php echo $option; ?></option>
                        <?php
                          }
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-2">
                      <label for="inputClientCompany">&nbsp;</label>
                      <select id="inputState" class="form-control" name="date_mou3">
                        <option selected>ปี พ.ศ.</option>
                        <?php
                        for ($yearNum = 2564; $yearNum <= 2570; $yearNum++) {
                          echo "<option value='$yearNum'>$yearNum</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-6">
                      <label for="inputClientCompany">ระยะเวลา MOU</label>
                      <input type="number" name="time_mou" class="form-control">
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-5">
                      <label for="inputClientCompany">หมายเลขโทรศัพท์ 1</label>
                      <input type="text" name="tel1" class="form-control">
                    </div>
                    <div class="col-4">
                      <label for="inputClientCompany">หมายเลขโทรศัพท์ 2</label>
                      <input type="text" name="tel2" class="form-control">
                    </div>
                    <div class="col-3">
                      <label for="inputClientCompany">Fax</label>
                      <input type="text" name="fax" class="form-control">
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-6">
                      <label for="inputClientCompany">Line ID</label>
                      <input type="text" name="line" class="form-control">
                    </div>
                    <div class="col-6">
                      <label for="inputClientCompany">Facebook</label>
                      <input type="text" name="facebook" class="form-control">
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-6">
                      <label for="inputClientCompany">Web Site</label>
                      <input type="text" name="website" class="form-control">
                    </div>
                    <div class="col-6">
                      <label for="inputClientCompany">Email</label>
                      <input type="text" name="email" class="form-control">
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-12">
                      <label for="inputClientCompany">หมายเหตุ</label>
                      <input type="text" name="note" class="form-control">
                    </div>
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
      // ปรับ SQL query ให้กรองตาม year ด้วย
      $sql = "SELECT * FROM `organization_mou` WHERE faculty_id = '$faculty_id' ORDER BY `organization_mou`.`id` DESC;";
      $result = $conn->query($sql);
      ?>
      <section class="content">

        <!-- Default box -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">รายการสถานประกอบการที่ทำบันทึกข้อตกลง (MOU) ปีการศึกษา <?php echo $year; ?></h3>
            <a href="orgMouReport.php?year=<?php echo $year; ?>" id="openReportButton" class="btn btn-secondary float-right">พิมพ์รายงาน</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ลำดับ</th>
                  <th>ชื่อสถานประกอบการ</th>
                  <th>ที่อยู่</th>
                  <th>จังหวัด</th>
                  <th>วัน MOU</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($result->num_rows > 0) {
                  $i = 1;
                  while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $row["name"]; ?></td>
                      <td><?php echo $row["address"]; ?></td>
                      <td><?php echo $row["province"]; ?></td>
                      <td><?php echo $row["date_mou"]; ?></td>
                      <td class="project-actions text-right">
                        <a class="btn btn-info btn-sm" href="orgMouEdit.php?orgMouid=<?php echo $row["id"]; ?>&year=<?php echo $year; ?>">
                          <i class="fas fa-pencil-alt">
                          </i>
                          Edit
                        </a>
                        <a class="btn btn-danger btn-sm" href="JavaScript:if(confirm('ยืนยันการลบข้อมูล?')==true){window.location='orgMouDel.php?orgMouid=<?php echo $row["id"]; ?>&year=<?php echo $year; ?>';}">
                          <i class="fas fa-trash">
                          </i>
                          Delete
                        </a>
                      </td>
                    </tr>
                <?php
                    include 'view_org.php';
                    $i++;
                  }
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>ลำดับ</th>
                  <th>ชื่อสถานประกอบการ</th>
                  <th>ที่อยู่</th>
                  <th>จังหวัด</th>
                  <th>วัน MOU</th>
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
      window.location.href = 'orgMouAdd.php?year=' + selectedYear;
    }
  </script>
</body>

</html>