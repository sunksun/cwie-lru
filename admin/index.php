<?php
session_start();
include_once('connect.php');

// ตรวจสอบการล็อกอินและสถานะการอนุมัติ
if (!isset($_SESSION['id']) || empty($_SESSION['fullname'])) {
  echo '<script language="javascript">';
  echo 'alert("กรุณา Login เข้าสู่ระบบ"); location.href="login.php"';
  echo '</script>';
  exit; // เพิ่ม exit เพื่อหยุดการทำงานทันที
}

// ตรวจสอบสถานะการอนุมัติ (ถ้ามีการเก็บสถานะในเซสชัน)
// ถ้าไม่มีเก็บในเซสชัน ควรตรวจสอบจากฐานข้อมูล
$user_id = $_SESSION['id'];
$check_status_sql = "SELECT status FROM tblusers WHERE id = ?";
$check_status_stmt = $conn->prepare($check_status_sql);
$check_status_stmt->bind_param('i', $user_id);
$check_status_stmt->execute();
$result_status = $check_status_stmt->get_result();
if ($result_status->num_rows > 0) {
  $status_row = $result_status->fetch_assoc();
  if ($status_row['status'] !== 'approved') {
    echo '<script language="javascript">';
    echo 'alert("บัญชีของคุณยังไม่ได้รับการอนุมัติ กรุณาติดต่อผู้ดูแลระบบ"); location.href="login.php"';
    echo '</script>';
    exit;
  }
}

$user_img = $_SESSION['img'];
$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];

// รับค่าปีการศึกษาจาก URL หรือใช้ค่าล่าสุดจากฐานข้อมูล
if (isset($_GET['year'])) {
  $year = $_GET['year'];
} else {
  // ดึงปีล่าสุดจากตาราง year
  $latest_year_query = "SELECT year FROM year ORDER BY id DESC LIMIT 1";
  $latest_year_result = mysqli_query($conn, $latest_year_query);

  if (mysqli_num_rows($latest_year_result) > 0) {
    $latest_year_row = mysqli_fetch_assoc($latest_year_result);
    $year = $latest_year_row['year'];
  } else {
    // กรณีไม่มีข้อมูลในตาราง ใช้ค่าเริ่มต้น
    $year = "2/2567";
  }
}

// ตรวจสอบว่า username เป็น admin หรือไม่
if ($username === 'admin') {
  header("Location: index2.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($faculty); ?> | สหกิจศึกษา :: มหาวิทยาลัยราชภัฏเลย</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <?php include_once 'navbar.php'; ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php include_once 'sidebar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <?php
              // ใช้ Prepared Statement แทน
              $stmt = $conn->prepare("SELECT SUM(num_cwie) AS num_cwie FROM num_stu_cwie WHERE faculty_id = ?");
              $stmt->bind_param('s', $faculty_id);
              $stmt->execute();
              $result = $stmt->get_result();
              $row_num_cwie = $result->fetch_assoc();
              $num_cwie = $row_num_cwie["num_cwie"] ?? 0; // ใช้ Null coalescing operator เพื่อป้องกันค่า null
              ?>
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?php echo number_format($num_cwie); ?></h3>

                  <p>นักศึกษาสหกิจฯ</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <?php
              // ใช้ Prepared Statement แทน
              $stmt = $conn->prepare("SELECT SUM(num_practice) AS num_practice FROM num_stu_cwie WHERE faculty_id = ?");
              $stmt->bind_param('s', $faculty_id);
              $stmt->execute();
              $result = $stmt->get_result();
              $row_num_practice = $result->fetch_assoc();
              $num_practice = $row_num_practice["num_practice"] ?? 0;
              ?>
              <div class="small-box bg-success">
                <div class="inner">
                  <h3><?php echo number_format($num_practice); ?><sup style="font-size: 20px"></sup></h3>

                  <p>นักศึกษาฝึกประสบการณ์วิชาชีพ</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <?php
              // ใช้ Prepared Statement แทน
              $stmt = $conn->prepare("SELECT COUNT(*) AS sum_count FROM num_tea_cwie WHERE faculty_id = ?");
              $stmt->bind_param('s', $faculty_id);
              $stmt->execute();
              $result = $stmt->get_result();
              $row_sum_count = $result->fetch_assoc();
              $sum_count = $row_sum_count["sum_count"] ?? 0;
              ?>
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3><?php echo number_format($sum_count); ?></h3>

                  <p>อาจารย์นิเทศสหกิจ</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <?php
              // ใช้ Prepared Statement แทน
              $stmt = $conn->prepare("SELECT COUNT(*) AS num_org_mou FROM organization_mou WHERE faculty_id = ?");
              $stmt->bind_param('s', $faculty_id);
              $stmt->execute();
              $result = $stmt->get_result();
              $row_num_org_mou = $result->fetch_assoc();
              $num_org_mou = $row_num_org_mou["num_org_mou"] ?? 0;
              ?>
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3><?php echo number_format($num_org_mou); ?></h3>

                  <p>สถานประกอบการ</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
          </div>
          <!-- /.row -->
          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <section class="col-lg-7 connectedSortable">
              <?php
              // ดึงข้อมูลสำหรับกราฟข้อมูลนักศึกษาและบัณฑิต CWIE
              $chart_query = "
                SELECT major, 
                      num_practice, 
                      num_cwie, 
                      num_pundit, 
                      num_pundit_job, 
                      num_pundit_job_work
                FROM num_stu_cwie 
                WHERE faculty_id = ? AND year = ?
                ORDER BY id DESC
                LIMIT 10";

              $chart_stmt = $conn->prepare($chart_query);
              $chart_stmt->bind_param('ss', $faculty_id, $year);
              $chart_stmt->execute();
              $chart_result = $chart_stmt->get_result();

              // เตรียมข้อมูลสำหรับกราฟ
              $majors = [];
              $practice_data = [];
              $cwie_data = [];
              $graduate_data = [];
              $employed_data = [];
              $employed_in_org_data = [];

              while ($row = $chart_result->fetch_assoc()) {
                // ตัดชื่อสาขาให้สั้นลงเพื่อแสดงในกราฟ
                $major_parts = explode('--', $row['major']);
                $short_major = end($major_parts);

                // ตัดคำว่า "สาขาวิชา" ออก
                $short_major = str_replace("สาขาวิชา", "", $short_major);

                $majors[] = $short_major;
                $practice_data[] = (int)$row['num_practice'];
                $cwie_data[] = (int)$row['num_cwie'];
                $graduate_data[] = (int)$row['num_pundit'];
                $employed_data[] = (int)$row['num_pundit_job'];
                $employed_in_org_data[] = (int)$row['num_pundit_job_work'];
              }

              // แปลงข้อมูลเป็น JSON สำหรับใช้ใน JavaScript
              $majors_json = json_encode($majors);
              $practice_json = json_encode($practice_data);
              $cwie_json = json_encode($cwie_data);
              $graduate_json = json_encode($graduate_data);
              $employed_json = json_encode($employed_data);
              $employed_in_org_json = json_encode($employed_in_org_data);

              ?>

              <!-- ส่วนกราฟข้อมูลนักศึกษา -->
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    คณะ<?php echo htmlspecialchars($faculty);
                        echo "ภาคเรียนที่ " . $year; ?>
                  </h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div><!-- /.card-header -->
                <div class="card-body">
                  <div class="tab-content p-0">
                    <div class="chart tab-pane active" id="student-chart" style="position: relative; height: 350px;">
                      <canvas id="student-chart-canvas" height="350" style="height: 350px;"></canvas>
                    </div>
                  </div>
                </div><!-- /.card-body -->
              </div>

              <!-- /.card -->


              <!-- /.card -->
            </section>
            <!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-5 connectedSortable">

              <!-- News card -->
              <div class="card bg-gradient-primary">
                <div class="card-header border-0">
                  <h3 class="card-title">
                    <i class="fas fa-newspaper mr-1"></i>
                    ข่าวประชาสัมพันธ์ล่าสุด
                  </h3>
                  <!-- card tools -->
                  <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                  <!-- /.card-tools -->
                </div>

                <!-- card-body -->
                <div class="card-body p-0">
                  <ul class="list-group list-group-flush">
                    <?php
                    // ตรวจสอบว่ามีการเชื่อมต่อฐานข้อมูลและมีการตั้งค่า session แล้ว
                    $faculty_id = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : '0';
                    $position = isset($_SESSION['position']) ? $_SESSION['position'] : '';

                    // ดึงข้อมูลข่าวล่าสุด 5 รายการ
                    if ($position == "ผู้ดูแลระบบ" || $faculty_id == "1") {
                      // กรณีเป็นแอดมินหรือมี faculty_id เป็น 1 ให้ดูข่าวทั้งหมดได้
                      $sql = "SELECT id, title, date1, mou_year, date_time FROM news ORDER BY date_time DESC LIMIT 5";
                    } else {
                      // กรณีเป็นผู้ใช้ทั่วไป ให้ดูได้เฉพาะข่าวของคณะตัวเองและข่าวทั่วไป (faculty_id = 0)
                      $sql = "SELECT id, title, date1, mou_year, date_time FROM news 
            WHERE faculty_id = '$faculty_id' OR faculty_id = '0' 
            ORDER BY date_time DESC LIMIT 5";
                    }

                    $result = mysqli_query($conn, $sql);

                    if ($result && mysqli_num_rows($result) > 0) {
                      // แสดงข้อมูลแต่ละแถว
                      while ($row = mysqli_fetch_assoc($result)) {
                        // จัดรูปแบบวันที่เพื่อแสดงผล
                        $date_display = $row["date1"] . ' ' . $row["mou_year"];

                        echo '<li class="list-group-item bg-gradient-primary border-light">';
                        echo '  <div class="d-flex justify-content-between align-items-center">';
                        echo '    <a href="news_detail.php?id=' . $row["id"] . '" class="text-white">' . htmlspecialchars($row["title"]) . '</a>';
                        echo '    <span class="badge bg-primary"><i class="far fa-calendar-alt"></i> ' . htmlspecialchars($date_display) . '</span>';
                        echo '  </div>';
                        echo '</li>';
                      }
                    } else {
                      echo '<li class="list-group-item bg-gradient-primary text-white text-center">ไม่มีข่าวประชาสัมพันธ์</li>';
                    }

                    // ไม่ควรปิดการเชื่อมต่อฐานข้อมูลที่นี่ถ้ายังต้องใช้ในส่วนอื่นของหน้าเว็บ
                    // mysqli_close($conn);
                    ?>
                  </ul>
                </div>

                <!-- /.card-body -->
              </div>
              <!-- /.card -->

              <!-- solid sales graph -->

              <!-- /.card -->

              <!-- Calendar -->

              <!-- /.card -->
            </section>
            <!-- right col -->
          </div>
          <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
      All rights reserved.
      <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.2.0
      </div>
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
  <!-- jQuery UI 1.11.4 -->
  <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- ChartJS -->
  <script src="plugins/chart.js/Chart.min.js"></script>
  <!-- Sparkline -->
  <script src="plugins/sparklines/sparkline.js"></script>
  <!-- JQVMap -->
  <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
  <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
  <!-- jQuery Knob Chart -->
  <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
  <!-- daterangepicker -->
  <script src="plugins/moment/moment.min.js"></script>
  <script src="plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Summernote -->
  <script src="plugins/summernote/summernote-bs4.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.js"></script>
  <!-- AdminLTE for demo purposes -->
  <!-- <script src="dist/js/demo.js"></script> -->
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="dist/js/pages/dashboard.js"></script>

  <!-- เพิ่ม JavaScript สำหรับสร้างกราฟข้อมูลนักศึกษา -->
  <script>
    $(function() {
      // ข้อมูลกราฟจาก PHP
      var majors = <?php echo $majors_json; ?>;
      var practiceData = <?php echo $practice_json; ?>;
      var cwieData = <?php echo $cwie_json; ?>;
      var graduateData = <?php echo $graduate_json; ?>;
      var employedData = <?php echo $employed_json; ?>;
      var employedInOrgData = <?php echo $employed_in_org_json; ?>;

      // สร้างกราฟข้อมูลนักศึกษาและบัณฑิต CWIE
      var ctx = document.getElementById('student-chart-canvas').getContext('2d');

      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: majors,
          datasets: [{
              label: 'นักศึกษาฝึกประสบการณ์วิชาชีพ',
              backgroundColor: '#4bc0c0',
              data: practiceData
            },
            {
              label: 'นักศึกษาสหกิจศึกษา',
              backgroundColor: '#36a2eb',
              data: cwieData
            },
            {
              label: 'บัณฑิต CWIE',
              backgroundColor: '#ff6384',
              data: graduateData
            },
            {
              label: 'บัณฑิต CWIE ที่ได้งานทำ',
              backgroundColor: '#ffcd56',
              data: employedData
            },
            {
              label: 'บัณฑิต CWIE ที่ได้งานทำในสถานประกอบการ',
              backgroundColor: '#9966ff',
              data: employedInOrgData
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            xAxes: [{
              stacked: false,
              ticks: {
                autoSkip: false,
                maxRotation: 45,
                minRotation: 45
              }
            }],
            yAxes: [{
              stacked: false,
              ticks: {
                beginAtZero: true
              }
            }]
          },
          tooltips: {
            mode: 'index',
            intersect: false,
          },
          hover: {
            mode: 'nearest',
            intersect: true
          },
          legend: {
            display: true,
            position: 'top'
          }
        }
      });

      // จัดการกับการเปลี่ยนปีการศึกษา
      $('select[name="fid"]').on('change', function() {
        var selectedYear = $(this).val();
        $('.btn-success').attr('href', 'index.php?year=' + selectedYear);
      });
    });
  </script>
</body>

</html>