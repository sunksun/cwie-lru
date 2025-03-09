<?php
session_start();
$user_img = $_SESSION['img'];
include_once('connect.php');
$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];

// ตรวจสอบว่ามี newid หรือไม่
if (!isset($_GET["newid"]) || empty($_GET["newid"])) {
  echo '<script language="javascript">';
  echo 'alert("ไม่พบรหัสข่าวที่ต้องการแก้ไข"); window.location="newsAdd.php";';
  echo '</script>';
  exit();
}

$newid = $_GET["newid"];

// ดึงข้อมูลข่าวตาม ID
$sql = "SELECT * FROM news WHERE id = '$newid'";
$result = $conn->query($sql);

// ตรวจสอบว่าพบข้อมูลหรือไม่
if ($result->num_rows == 0) {
  echo '<script language="javascript">';
  echo 'alert("ไม่พบข้อมูลข่าวที่ต้องการแก้ไข"); window.location="newsAdd.php";';
  echo '</script>';
  exit();
}

$row = $result->fetch_assoc();

// ตรวจสอบสิทธิ์ในการแก้ไข (ถ้าไม่ใช่แอดมินระบบ ต้องเป็นคณะเดียวกันกับข่าว)
if ($position != "ผู้ดูแลระบบ" && $faculty_id != "1" && $row["faculty_id"] != $faculty_id) {
  echo '<script language="javascript">';
  echo 'alert("คุณไม่มีสิทธิ์แก้ไขข่าวนี้"); window.location="newsAdd.php";';
  echo '</script>';
  exit();
}

// แยกค่าวันที่ เดือน และปี จาก mou_year
$date1 = $row["date1"];
$mou_year_parts = explode(" ", $row["mou_year"]);
$date2 = $mou_year_parts[0] ?? ""; // เดือน
$date3 = $mou_year_parts[1] ?? ""; // ปี
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | แก้ไขข่าวประชาสัมพันธ์</title>

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
              <h1>จัดการข่าวประชาสัมพันธ์</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                <li class="breadcrumb-item"><a href="newsAdd.php">จัดการข่าวประชาสัมพันธ์</a></li>
                <li class="breadcrumb-item active">แก้ไขข่าวประชาสัมพันธ์</li>
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
                <h3 class="card-title">แก้ไขข่าวประชาสัมพันธ์</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <form action="newsSave.php?newid=<?php echo $newid; ?>" method="post" enctype="multipart/form-data">
                  <div class="row form-group">
                    <div class="col-6">
                      <div class="form-group">
                        <label for="inputClientCompany">หัวข้อข่าว</label>
                        <input type="text" name="title" id="inputClientCompany" class="form-control" value="<?php echo htmlspecialchars($row["title"]); ?>" required>
                      </div>
                    </div>
                    <div class="col-2">
                      <div class="form-group">
                        <label for="inputClientCompany">วันที่จัดกิจกรรม</label>
                        <input type="text" name="date1" id="inputClientCompany" class="form-control" value="<?php echo htmlspecialchars($date1); ?>" required>
                      </div>
                    </div>
                    <div class="col-2">
                      <div class="form-group">
                        <label for="inputClientCompany">เดือน</label>
                        <select id="inputState" class="form-control" name="date2">
                          <?php
                          $sql_month = "SELECT * FROM mount";
                          $result_month = $conn->query($sql_month);
                          if ($result_month->num_rows > 0) {
                            while ($optionData = $result_month->fetch_assoc()) {
                              $option = $optionData['mount'];
                              $selected = ($option == $date2) ? 'selected' : '';
                          ?>
                              <option value="<?php echo $option; ?>" <?php echo $selected; ?>> <?php echo $option; ?></option>
                          <?php
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-2">
                      <div class="form-group">
                        <label for="inputClientCompany">ปี พ.ศ.</label>
                        <select id="inputState" class="form-control" name="date3">
                          <?php
                          for ($year = 2564; $year <= 2570; $year++) {
                            $selected = ($year == $date3) ? 'selected' : '';
                            echo "<option value='$year' $selected>$year</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName">รายละเอียดข่าว (Paragraph 1)</label>
                    <textarea name="detail" class="form-control" id="exampleFormControlTextarea1" rows="3" required><?php echo htmlspecialchars($row["detail"]); ?></textarea>
                  </div>
                  <div class="form-group">
                    <label for="inputName">รายละเอียดข่าว (Paragraph 2)</label>
                    <textarea name="detail2" class="form-control" id="exampleFormControlTextarea2" rows="3" required><?php echo htmlspecialchars($row["detail2"]); ?></textarea>
                  </div>
                  <div class="form-group">
                    <label for="inputName">รูปภาพปัจจุบัน</label>
                    <div class="text-center">
                      <?php if (!empty($row["img"])) { ?>
                        <img src="img_news/<?php echo htmlspecialchars($row["img"]); ?>" class="img-rounded" width="200px" alt="รูปข่าว">
                      <?php } else { ?>
                        <p>ไม่มีรูปภาพ</p>
                      <?php } ?>
                    </div>
                    <br>
                    <label for="inputName">อัปโหลดรูปภาพใหม่ (ถ้าต้องการเปลี่ยน)</label>
                    <input class="form-control" type="file" name="fileToUpload" id="fileToUpload">
                    <small class="form-text text-muted">รูปข่าวประชาสัมพันธ์ ขนาด 370x360px (ถ้าไม่ต้องการเปลี่ยนรูปภาพ ไม่ต้องอัปโหลดไฟล์ใหม่)</small>
                  </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <a href="newsAdd.php" class="btn btn-secondary float-right">ยกเลิก</a>
            <input type="submit" name="update" value="บันทึกข้อมูล" class="btn btn-success float-left">
          </div>
        </div>
        </form>
      </section>
      <!-- /.content -->
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
  </script>
</body>

</html>