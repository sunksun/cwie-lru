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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | แก้ไขจำนวนนักศึกษาและบัณฑิต การจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL)</title>

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
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                <li class="breadcrumb-item active"><a href="logout.php">ออกจากระบบ</a></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><a href="#">แก้ไขจำนวนนักศึกษาและบัณฑิต การจัดการเรียนรู้การปฏิบัติงานในสถานศึกษา (SIL)</a></h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <?php
              $numStuid = $_GET["numStuid"];
              $sql = "SELECT * FROM num_stu_cwie WHERE id = '$numStuid'";
              $result = $conn->query($sql);
              $row = $result->fetch_assoc()
              ?>
              <div class="card-body">
                <form action="numStuSilSave.php?numStuid=<?php echo $row["id"]; ?>" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="inputClientCompany">เลือกภาคการศึกษา</label>
                    <select class="form-control select2" name="year" style="width: 100%;" required>
                      <?php
                      $sql = "SELECT * FROM year ";
                      $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                        while ($optionData = $result->fetch_assoc()) {
                          $option = $optionData['year'];
                      ?>
                          <option value="<?php echo $option; ?>" <?php if ($option == $row["year"]) echo 'selected="selected"'; ?>> ปีการศึกษา
                            <?php echo $option; ?></option>
                      <?php
                        }
                      }
                      ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="inputClientCompany">สาขาวิชา</label>
                    <select class="form-control select2" name="course" style="width: 100%;">
                      <option selected="selected">--- เลือกสาขาวิชา ---</option>
                      <?php
                      $sql = "SELECT * FROM major WHERE faculty = '$faculty_id'";
                      $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                        while ($optionData = $result->fetch_assoc()) {
                          $option = $optionData["course"] . '--' . $optionData["major"];
                          $id = $optionData['faculty'];
                      ?>
                          <option value="<?php echo $option; ?>" <?php if ($option == $row["major"]) echo 'selected="selected"'; ?>>
                            <?php echo $option; ?></option>
                      <?php
                        }
                      }
                      ?>
                    </select>
                  </div>
                  <div class="row form-group">
                    <div class="col-6">
                      <label for="inputClientCompany">จำนวน นศ. ออกฝึกประสบการวิชาชีพ (ระบบปกติ)</label>
                      <input type="number" name="num_practice" class="form-control" value="<?php echo $row["num_practice"]; ?>">
                    </div>
                    <div class="col-6">
                      <label for="inputClientCompany">จำนวน นส. สหกิจศึกษา</label>
                      <input type="number" name="num_cwie" class="form-control" value="<?php echo $row["num_cwie"]; ?>">
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-3">
                      <label for="inputClientCompany">จำนวนบัณฑิต SIL</label>
                      <input type="number" name="num_pundit" class="form-control" value="<?php echo $row["num_pundit"]; ?>">
                    </div>
                    <div class="col-3">
                      <label for="inputClientCompany">จำนวนบัณฑิต SIL ที่ได้งานทำ</label>
                      <input type="number" name="num_pundit_job" class="form-control" value="<?php echo $row["num_pundit_job"]; ?>">
                    </div>
                    <div class="col-6">
                      <label for="inputClientCompany">จำนวนบัณฑิต SIL ที่ได้งานทำในสถานประกอบการ</label>
                      <input type="number" name="num_pundit_job_work" class="form-control" value="<?php echo $row["num_pundit_job_work"]; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputClientCompany">หมายเหตุ</label>
                    <input type="text" name="note" id="inputClientCompany" class="form-control" value="<?php echo $row["note"]; ?>">
                  </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

        </div>
        <div class="row">
          <div class="col-12">
            <input type="submit" name="update" value="บันทึกข้อมูล" class="btn btn-success float-left">
          </div>
        </div>
        </form>
      </section>
      <!-- /.content -->
      <hr>
      <?php
      $sql = "SELECT * FROM major";
      $result = $conn->query($sql);
      ?>
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
  <!-- AdminLTE for demo purposes -->
  <!-- <script src="dist/js/demo.js"></script> -->
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
  </script>
</body>

</html>