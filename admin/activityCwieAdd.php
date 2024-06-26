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
  <title>Admin | กิจกรรมการจัดการเรียนรู้ CWIE</title>

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
                <h3 class="card-title"><a href="#">เพิ่มกิจกรรมการจัดการเรียนรู้เชิงบรูณาการกับการทำงาน (CWIE) ที่เกี่ยวข้อง </a></h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <form action="activityCwieSave.php" method="post" enctype="multipart/form-data">
                  <div class="row form-group">
                    <div class="col-4">
                      <label for="inputClientCompany">ประเภทกิจกรรม</label>
                      <select class="form-control select2" name="activity_type" style="width: 100%;">
                        <option selected="selected">--- เลือกประเภทกิจกรรม ---</option>
                        <?php
                        $sql = "SELECT * FROM activity_type";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                          while ($optionData = $result->fetch_assoc()) {
                            $option = $optionData['activity_type'];
                            $activity_id = $optionData['id'];
                            $_SESSION['activity_id'] = $optionData['id'];
                        ?>
                            <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                        <?php
                          }
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-8">
                      <label for="inputClientCompany">ชื่อกิจกรรม</label>
                      <input type="text" name="activity_name" class="form-control">
                    </div>
                  </div>
                  <div class="row form-group">
                    <?php
                    // ดึงข้อมูลจากตาราง major โดยมีเงื่อนไข faculty = '02'
                    $sql = "SELECT * FROM major WHERE faculty = '$faculty_id'";
                    $result = $conn->query($sql);
                    ?>
                    <div class="col-6">
                      <label for="inputClientCompany">สาขาวิชา</label>
                      <?php
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                          $option = $row['major'];
                          $id = $row['id'];
                      ?>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="major[]" value="<?php echo $id; ?>" id="course<?php echo $id; ?>">
                            <label class="form-check-label" for="course<?php echo $id; ?>">
                              <?php echo $option; ?>
                            </label>
                          </div>
                      <?php
                        }
                      }
                      ?>
                      <!-- Checkbox สำหรับ "ทุกหลักสูตร" -->
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="major[]" value="all" id="courseAll">
                        <label class="form-check-label" for="courseAll">
                          ทุกหลักสูตร
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-6">
                      <label for="inputClientCompany">วันที่ดำเนินการ</label>
                      <input type="text" name="activity_date" class="form-control">
                    </div>
                    <div class="col-6">
                      <label for="inputClientCompany">จำนวนผู้เข้าร่วม</label>
                      <input type="number" name="amount" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputClientCompany">หมายเหตุ</label>
                    <input type="text" name="note" id="inputClientCompany" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="inputName">ภาพกิจกรรม</label>
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
      $sql = "SELECT * FROM `activity_cwie` WHERE faculty_id = '$faculty_id' ORDER BY `activity_cwie`.`id` DESC";
      $result = $conn->query($sql);
      ?>
      <section class="content">

        <!-- Default box -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">จำนวนนักศึกษาและบัณฑิต CWIE</h3>
            <a href="#" id="openReportButton" class="btn btn-secondary float-right">พิมพ์รายงาน</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ลำดับ</th>
                  <th>ชื่อกิจกรรม</th>
                  <th>สาขาวิชา</th>
                  <th>วันที่ดำเนินการ</th>
                  <th>จำนวนผู้เข้าร่วม</th>
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
                      <td><?php echo $row["activity_name"]; ?></td>
                      <td><?php echo $row["course"]; ?></td>
                      <td><?php echo $row["activity_date"]; ?></td>
                      <td><?php echo $row["amount"]; ?></td>
                      <td class="project-actions text-right">
                        <a class="btn btn-danger btn-sm" href="JavaScript:if(confirm('ยืนยันการลบข้อมูล?')==true){window.location='activityCwieDel.php?actid=<?php echo $row["id"]; ?>';}">
                          <i class="fas fa-trash">
                          </i>
                          Delete
                        </a>
                      </td>
                    </tr>
                <?php
                    include 'activityCwieView.php';
                    $i++;
                  }
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>ลำดับ</th>
                  <th>ชื่อกิจกรรม</th>
                  <th>สาขาวิชา</th>
                  <th>วันที่ดำเนินการ</th>
                  <th>จำนวนผู้เข้าร่วม</th>
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
      // Function to open cwieCourseReport.php
      function openCwieCourseReport() {
        window.location.href = 'activityCwieReport.php';
      }

      // Bind click event to the button
      $('#openReportButton').on('click', function() {
        openCwieCourseReport();
      });
    });
  </script>

</body>

</html>