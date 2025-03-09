<?php
session_start();
$user_img = $_SESSION['img'];
include_once('connect.php');
if ($_SESSION['fullname'] == '') {
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

// รับค่าปีการศึกษาจาก URL หรือใช้ค่าเริ่มต้น
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


      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><a href="#">เพิ่มกิจกรรมการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE) ที่เกี่ยวข้อง ปีการศึกษา <?php echo $year; ?></a></h3>

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
                    <select id="yearSelect" class="form-control select2" style="width: 100%;" onchange="changeYear(this.value)">
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
                <form action="activityCwieSave.php" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="year" value="<?php echo $year; ?>">
                  <div class="row form-group">
                    <div class="col-4">
                      <label for="inputClientCompany">ประเภทกิจกรรม</label>
                      <select class="form-control select2" name="activity_type" style="width: 100%;" required>
                        <option selected="selected" value="">--- เลือกประเภทกิจกรรม ---</option>
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
                      <input type="text" name="activity_name" class="form-control" required>
                    </div>
                  </div>
                  <div class="row form-group">
                    <?php
                    // ดึงข้อมูลจากตาราง major โดยมีเงื่อนไข faculty = faculty_id
                    $sql = "SELECT * FROM major WHERE faculty = '$faculty_id'";
                    $result = $conn->query($sql);
                    ?>
                    <div class="col-6">
                      <label for="inputClientCompany">สาขาวิชา</label>
                      <div style="max-height: 250px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
                        <?php
                        if ($result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                            $option = $row['major'];
                            $id = $row['id'];
                        ?>
                            <div class="form-check">
                              <input class="form-check-input course-checkbox" type="checkbox" name="major[]" value="<?php echo $id; ?>" id="course<?php echo $id; ?>">
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
                            <strong>ทุกหลักสูตร</strong>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="row">
                        <div class="col-12">
                          <label for="inputClientCompany">วันที่ดำเนินการ</label>
                          <input type="text" name="activity_date" class="form-control" placeholder="เช่น 15 มิถุนายน 2567" required>
                        </div>
                      </div>
                      <div class="row mt-3">
                        <div class="col-12">
                          <label for="inputClientCompany">จำนวนผู้เข้าร่วม</label>
                          <input type="number" name="amount" class="form-control" min="0" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputClientCompany">หมายเหตุ</label>
                    <input type="text" name="note" id="inputClientCompany" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="inputName">ภาพกิจกรรม</label>
                    <input class="form-control" type="file" name="filename" id="fileToUpload" accept="image/*">
                    <small class="text-muted">รองรับไฟล์ภาพ JPG, PNG, GIF ขนาดไม่เกิน 2MB</small>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <button type="submit" name="save" class="btn btn-success float-right">บันทึกข้อมูล</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </section>
      <!-- /.content -->
      <hr>
      <?php
      // ตรวจสอบว่ามีคอลัมน์ year ในตารางหรือไม่
      $check_column = mysqli_query($conn, "SHOW COLUMNS FROM activity_cwie LIKE 'year'");
      $column_exists = mysqli_num_rows($check_column) > 0;

      if ($column_exists) {
        // ถ้ามีคอลัมน์ year ให้กรองข้อมูลตามปีการศึกษาด้วย
        $sql = "SELECT * FROM `activity_cwie` WHERE faculty_id = '$faculty_id' AND year = '$year' ORDER BY `activity_cwie`.`id` DESC";
      } else {
        // ถ้าไม่มีคอลัมน์ year ให้ดึงข้อมูลตาม faculty_id อย่างเดียว
        $sql = "SELECT * FROM `activity_cwie` WHERE faculty_id = '$faculty_id' ORDER BY `activity_cwie`.`id` DESC";
      }
      $result = $conn->query($sql);
      ?>
      <section class="content">

        <!-- Default box -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">รายการกิจกรรม CWIE ปีการศึกษา <?php echo $year; ?></h3>
            <a href="activityCwieReport.php?year=<?php echo $year; ?>" id="openReportButton" class="btn btn-secondary float-right">พิมพ์รายงาน</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th width="5%">ลำดับ</th>
                  <th width="30%">ชื่อกิจกรรม</th>
                  <th width="25%">สาขาวิชา</th>
                  <th width="15%">วันที่ดำเนินการ</th>
                  <th width="10%">จำนวนผู้เข้าร่วม</th>
                  <th width="15%">จัดการ</th>
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
                      <td><?php echo htmlspecialchars($row["activity_name"]); ?></td>
                      <td><?php echo htmlspecialchars($row["course"]); ?></td>
                      <td><?php echo htmlspecialchars($row["activity_date"]); ?></td>
                      <td><?php echo htmlspecialchars($row["amount"]); ?></td>
                      <td class="project-actions text-right">
                        <a class="btn btn-info btn-sm" href="activityCwieView.php?actid=<?php echo $row["id"]; ?>&year=<?php echo $year; ?>">
                          <i class="fas fa-eye"></i>
                          View
                        </a>
                        <a class="btn btn-danger btn-sm" href="JavaScript:if(confirm('ยืนยันการลบข้อมูล?')==true){window.location='activityCwieDel.php?actid=<?php echo $row["id"]; ?>&year=<?php echo $year; ?>';}">
                          <i class="fas fa-trash"></i>
                          Delete
                        </a>
                      </td>
                    </tr>
                  <?php
                    $i++;
                  }
                } else {
                  ?>
                  <tr>
                    <td colspan="6" class="text-center">ไม่พบข้อมูลกิจกรรม</td>
                  </tr>
                <?php
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
                  <th>จัดการ</th>
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
      window.location.href = 'activityCwieAdd.php?year=' + selectedYear;
    }

    // ฟังก์ชันสำหรับจัดการ checkbox ทุกหลักสูตร
    $(document).ready(function() {
      // เมื่อคลิกที่ checkbox "ทุกหลักสูตร"
      $('#courseAll').click(function() {
        if ($(this).is(':checked')) {
          // ถ้าเลือก "ทุกหลักสูตร" ให้ยกเลิกการเลือกสาขาวิชาอื่นๆ
          $('.course-checkbox').prop('checked', false);
        }
      });

      // เมื่อคลิกที่ checkbox สาขาวิชาอื่นๆ
      $('.course-checkbox').click(function() {
        if ($(this).is(':checked')) {
          // ถ้าเลือกสาขาวิชาอื่น ให้ยกเลิกการเลือก "ทุกหลักสูตร"
          $('#courseAll').prop('checked', false);
        }
      });
    });
  </script>

</body>

</html>