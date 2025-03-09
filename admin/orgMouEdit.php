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

// ตรวจสอบ URL parameter
if (!isset($_GET["orgMouid"]) || empty($_GET["orgMouid"])) {
  echo '<script language="javascript">';
  echo 'alert("ไม่พบรหัสสถานประกอบการที่ต้องการแก้ไข"); location.href="orgMouAdd.php"';
  echo '</script>';
  exit();
}

// ดึงปีการศึกษาจาก URL หรือใช้ค่าปีล่าสุด
if (isset($_GET['year']) && !empty($_GET['year'])) {
  $academic_year = $_GET['year'];
} else {
  // ดึงปีการศึกษาล่าสุดจากตาราง year
  $latest_year_query = "SELECT year FROM year ORDER BY id DESC LIMIT 1";
  $latest_year_result = mysqli_query($conn, $latest_year_query);

  if ($latest_year_result && mysqli_num_rows($latest_year_result) > 0) {
    $latest_year_row = mysqli_fetch_assoc($latest_year_result);
    $academic_year = $latest_year_row['year'];
  } else {
    $academic_year = "2/2566"; // ค่าเริ่มต้นกรณีไม่พบข้อมูล
  }
}

$orgMouid = $_GET["orgMouid"];
$sql = "SELECT * FROM organization_mou WHERE id = '$orgMouid'";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
  echo '<script language="javascript">';
  echo 'alert("ไม่พบข้อมูลสถานประกอบการที่ต้องการแก้ไข"); location.href="orgMouAdd.php"';
  echo '</script>';
  exit();
}

$row = $result->fetch_assoc();

// แยกข้อมูลวันที่ MOU
$date_mou = $row["date_mou"];
$date_mou_spil = explode(" ", $date_mou);
$day = isset($date_mou_spil[0]) ? $date_mou_spil[0] : "";
$mount = isset($date_mou_spil[1]) ? $date_mou_spil[1] : "";
$mou_year = isset($date_mou_spil[2]) ? $date_mou_spil[2] : "";

// เก็บค่าปีการศึกษาของข้อมูลนี้ (ถ้ามีในฐานข้อมูล)
$org_academic_year = isset($row["year"]) ? $row["year"] : $academic_year;
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
              <h1>สถานประกอบการที่ทำบันทึก MOU CWIE</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                <li class="breadcrumb-item"><a href="orgMouAdd.php">สถานประกอบการ MOU</a></li>
                <li class="breadcrumb-item active">แก้ไขข้อมูล</li>
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
                <h3 class="card-title"><a href="#">แก้ไขสถานประกอบการที่ทำบันทึกข้อตกลงหลักสูตรสหกิจศึกษาและการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE)</a></h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <form action="orgMouSave.php?orgMouid=<?php echo $orgMouid; ?>" method="post" enctype="multipart/form-data">
                  <div class="row form-group">
                    <div class="col-12">
                      <label>ปีการศึกษา</label>
                      <select class="form-control select2" name="year" style="width: 100%;">
                        <?php
                        $sql = "SELECT * FROM year ORDER BY id DESC";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                          while ($optionData = $result->fetch_assoc()) {
                            $option = $optionData['year'];
                            $selected = ($option == $org_academic_year) ? 'selected' : '';
                        ?>
                            <option value="<?php echo $option; ?>" <?php echo $selected; ?>>ปีการศึกษา <?php echo $option; ?></option>
                        <?php
                          }
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputClientCompany">ชื่อสถานประกอบการ</label>
                    <input type="text" name="name" id="inputClientCompany" class="form-control" value="<?php echo htmlspecialchars($row["name"]); ?>" required>
                  </div>
                  <div class="row form-group">
                    <div class="col-5">
                      <label for="inputClientCompany">ที่อยู่</label>
                      <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($row["address"]); ?>">
                    </div>
                    <div class="col-4">
                      <label for="inputClientCompany">แขวง/ตำบล</label>
                      <input type="text" name="subdistrict" class="form-control" value="<?php echo htmlspecialchars($row["subdistrict"]); ?>">
                    </div>
                    <div class="col-3">
                      <label for="inputClientCompany">เขต/อำเภอ</label>
                      <input type="text" name="district" class="form-control" value="<?php echo htmlspecialchars($row["district"]); ?>">
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-6">
                      <label>จังหวัด</label>
                      <select class="form-control select2" name="province" style="width: 100%;">
                        <option>--- เลือกจังหวัด ---</option>
                        <?php
                        $sql = "SELECT * FROM thai_provinces";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                          while ($optionData = $result->fetch_assoc()) {
                            $option = $optionData['thai_province'];
                            $selected = ($option == $row["province"]) ? 'selected="selected"' : '';
                        ?>
                            <option value="<?php echo $option; ?>" <?php echo $selected; ?>>
                              <?php echo $option; ?></option>
                        <?php
                          }
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-6">
                      <label for="inputClientCompany">รหัสไปรษณีย์</label>
                      <input type="text" name="postcode" class="form-control" value="<?php echo htmlspecialchars($row["postcode"]); ?>">
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-2">
                      <label for="inputClientCompany">วันที่ทำ MOU</label>
                      <select id="inputState" class="form-control" name="date_mou1">
                        <option>วันที่</option>
                        <?php
                        $sql = "SELECT * FROM day";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                          while ($optionData = $result->fetch_assoc()) {
                            $option = $optionData['day'];
                            $selected = ($option == $day) ? 'selected="selected"' : '';
                        ?>
                            <option value="<?php echo $option; ?>" <?php echo $selected; ?>>
                              <?php echo $option; ?></option>
                        <?php
                          }
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-2">
                      <label for="inputClientCompany">&nbsp;</label>
                      <select id="inputState" class="form-control" name="date_mou2">
                        <option>เดือน</option>
                        <?php
                        $sql = "SELECT * FROM mount";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                          while ($optionData = $result->fetch_assoc()) {
                            $option = $optionData['mount'];
                            $selected = ($option == $mount) ? 'selected="selected"' : '';
                        ?>
                            <option value="<?php echo $option; ?>" <?php echo $selected; ?>>
                              <?php echo $option; ?></option>
                        <?php
                          }
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-2">
                      <label for="inputClientCompany">&nbsp;</label>
                      <select id="inputState" class="form-control" name="date_mou3">
                        <option>ปี พ.ศ.</option>
                        <?php
                        for ($y = 2564; $y <= 2570; $y++) {
                          $selected = ($y == $mou_year) ? "selected='selected'" : "";
                          echo "<option value='$y' $selected>$y</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-6">
                      <label for="inputClientCompany">ระยะเวลา MOU</label>
                      <input type="number" name="time_mou" class="form-control" value="<?php echo htmlspecialchars($row["time_mou"]); ?>">
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-5">
                      <label for="inputClientCompany">หมายเลขโทรศัพท์ 1</label>
                      <input type="text" name="tel1" class="form-control" value="<?php echo htmlspecialchars($row["tel1"]); ?>">
                    </div>
                    <div class="col-4">
                      <label for="inputClientCompany">หมายเลขโทรศัพท์ 2</label>
                      <input type="text" name="tel2" class="form-control" value="<?php echo htmlspecialchars($row["tel2"]); ?>">
                    </div>
                    <div class="col-3">
                      <label for="inputClientCompany">Fax</label>
                      <input type="text" name="fax" class="form-control" value="<?php echo htmlspecialchars($row["fax"]); ?>">
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-6">
                      <label for="inputClientCompany">Line ID</label>
                      <input type="text" name="line" class="form-control" value="<?php echo htmlspecialchars($row["line"]); ?>">
                    </div>
                    <div class="col-6">
                      <label for="inputClientCompany">Facebook</label>
                      <input type="text" name="facebook" class="form-control" value="<?php echo htmlspecialchars($row["facebook"]); ?>">
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-6">
                      <label for="inputClientCompany">Web Site</label>
                      <input type="text" name="website" class="form-control" value="<?php echo htmlspecialchars($row["website"]); ?>">
                    </div>
                    <div class="col-6">
                      <label for="inputClientCompany">Email</label>
                      <input type="text" name="email" class="form-control" value="<?php echo htmlspecialchars($row["email"]); ?>">
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-12">
                      <label for="inputClientCompany">หมายเหตุ</label>
                      <input type="text" name="note" class="form-control" value="<?php echo htmlspecialchars($row["note"]); ?>">
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
            <a href="orgMouAdd.php?year=<?php echo $academic_year; ?>" class="btn btn-secondary float-right">ยกเลิก</a>
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