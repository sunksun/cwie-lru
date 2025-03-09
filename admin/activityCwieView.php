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

// รับค่า ID กิจกรรมและปีการศึกษาจาก URL
$actid = isset($_GET['actid']) ? intval($_GET['actid']) : 0;
$year = isset($_GET['year']) ? $_GET['year'] : '2/2566';

// ตรวจสอบว่ามี ID กิจกรรมหรือไม่
if ($actid <= 0) {
  echo '<script language="javascript">';
  echo 'alert("ไม่พบข้อมูลกิจกรรม"); window.location.href="activityCwieAdd.php?year=' . $year . '";';
  echo '</script>';
  exit;
}

// ดึงข้อมูลกิจกรรม
$sql = "SELECT * FROM activity_cwie WHERE id = '$actid'";
$result = $conn->query($sql);

// ตรวจสอบว่ามีข้อมูลหรือไม่
if (!$result || $result->num_rows == 0) {
  echo '<script language="javascript">';
  echo 'alert("ไม่พบข้อมูลกิจกรรม"); window.location.href="activityCwieAdd.php?year=' . $year . '";';
  echo '</script>';
  exit;
}

$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>รายละเอียดกิจกรรม CWIE</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
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
              <h1>รายละเอียดกิจกรรม CWIE</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                <li class="breadcrumb-item"><a href="activityCwieAdd.php?year=<?php echo $year; ?>">จัดการกิจกรรม CWIE</a></li>
                <li class="breadcrumb-item active">รายละเอียดกิจกรรม</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title"><?php echo htmlspecialchars($row["activity_type"]); ?></h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>ชื่อกิจกรรม:</label>
                        <p class="lead"><?php echo htmlspecialchars($row["activity_name"]); ?></p>
                      </div>
                      <div class="form-group">
                        <label>สาขาวิชา:</label>
                        <p class="lead"><?php echo htmlspecialchars($row["course"]); ?></p>
                      </div>
                      <div class="form-group">
                        <label>วันที่ดำเนินการ:</label>
                        <p class="lead"><?php echo htmlspecialchars($row["activity_date"]); ?></p>
                      </div>
                      <div class="form-group">
                        <label>จำนวนผู้เข้าร่วม:</label>
                        <p class="lead"><?php echo htmlspecialchars($row["amount"]); ?> คน</p>
                      </div>
                      <div class="form-group">
                        <label>หมายเหตุ:</label>
                        <p class="lead"><?php echo !empty($row["note"]) ? htmlspecialchars($row["note"]) : "-"; ?></p>
                      </div>
                      <div class="form-group">
                        <label>วันที่บันทึกข้อมูล:</label>
                        <p class="lead"><?php echo date('d/m/Y H:i:s', strtotime($row["date_regis"])); ?></p>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>ภาพกิจกรรม:</label>
                        <?php if (!empty($row["filename"])): ?>
                          <div class="text-center">
                            <img src="img_act_cwie/<?php echo $row["filename"]; ?>" class="img-fluid img-thumbnail" alt="รูปกิจกรรม" style="max-height: 400px;">
                          </div>
                        <?php else: ?>
                          <p class="lead">ไม่มีรูปภาพ</p>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <a href="activityCwieAdd.php?year=<?php echo $year; ?>" class="btn btn-default">กลับไปหน้าจัดการกิจกรรม</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <footer class="main-footer">
      <div class="float-right d-none d-sm-block">
        <b>Version</b> 3.2.0
      </div>
      <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
    </footer>
  </div>

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
</body>

</html>