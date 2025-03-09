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
  <title>Admin | จัดการรูปแบบการจัดหลักสูตรสหกิจศึกษาฯ</title>

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

  <!-- Live Search -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <style>
    .ullist {
      padding-left: 10px;
      margin-bottom: 10px;
      width: 635px;
      margin-top: -15px
    }
  </style>
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
                <h3 class="card-title"><a href="#">รูปแบบของการจัดหลักสูตรสหกิจศึกษาและการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE)</a></h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <form action="cwieCourseSave.php" method="post" enctype="multipart/form-data">
                  <div class="row form-group">
                    <div class="col-12">
                      <label for="inputClientCompany">เลือกภาคการศึกษา</label>
                      <select class="form-control select2" name="year" style="width: 100%;" required>
                        <?php
                        // ดึงปีการศึกษาล่าสุดมาเก็บไว้ก่อน
                        $latest_year_query = "SELECT year FROM year ORDER BY id DESC LIMIT 1";
                        $latest_year_result = $conn->query($latest_year_query);

                        // กำหนดค่าเริ่มต้นของ $year
                        if ($latest_year_result->num_rows > 0) {
                          $latest_year_data = $latest_year_result->fetch_assoc();
                          $year = $latest_year_data['year']; // ตั้งค่า $year เป็นปีล่าสุด
                        } else {
                          $year = "2/2566"; // ค่าเริ่มต้นกรณีไม่มีข้อมูลในตาราง
                        }

                        // ถ้ามีการส่งค่า year จาก URL ให้ใช้ค่านั้นแทน
                        if (isset($_GET['year'])) {
                          $year = $_GET['year'];
                        }

                        // แสดงตัวเลือกทั้งหมด
                        $sql = "SELECT * FROM year ORDER BY id DESC";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                          while ($optionData = $result->fetch_assoc()) {
                            $option = $optionData['year'];
                        ?>
                            <option value="<?php echo $option; ?>" <?php if ($option == $year) echo 'selected="selected"'; ?>> ปีการศึกษา
                              <?php echo $option; ?></option>
                        <?php
                          }
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-12">
                      <label for="inputClientCompany">สาขาวิชา</label>
                      <input type="text" class="form-control" id="live_search" name="course" tabindex="1" placeholder="ค้นหาสาขาวิชา....">
                    </div>
                    <div>
                      <ul id="search_result" class="list-group ullist">

                      </ul>
                    </div>
                  </div>
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
                  <div class="form-group">
                    <label for="inputClientCompany">รูปแบบสหกิจศึกษาและการจัดการเรียนรู้</label>
                    <!-- radio -->
                    <div class="form-group">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" value="Separate" id="separate">
                        <label class="form-check-label" for="separate">แบบแยก (Separate)</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" value="Parallel" id="parallel">
                        <label class="form-check-label" for="parallel">แบบคู่ขนาน (Parallel)</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" value="Mix" id="mix">
                        <label class="form-check-label" for="mix">แบบผสม (Mix)</label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputClientCompany">หมายเหตุ</label>
                    <input type="text" name="note" id="inputClientCompany" class="form-control">
                  </div>
                  <input type="hidden" id="faculty_id" name="faculty_id" value="<?php echo $faculty_id; ?>">
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
      // สร้างคำสั่ง SQL
      $sql = "SELECT id, faculty_id, major, 
        CASE 
            WHEN separate != '' THEN 'separate'
            WHEN parallel != '' THEN 'parallel'
            WHEN mix != '' THEN 'mix'
            ELSE 'none'
        END as type,
        note, date_regis, year
        FROM `cwie_course`
        WHERE faculty_id = '$faculty_id'
        ORDER BY `cwie_course`.`id` DESC";
      $result = $conn->query($sql);
      ?>
      <section class="content">

        <!-- Default box -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">รูปแบบสหกิจศึกษาและการจัดการเรียนรู้</h3>
            <a href="#" id="openReportButton" class="btn btn-secondary float-right">พิมพ์รายงาน</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ลำดับ</th>
                  <th>สาขาวิชา</th>
                  <th>รูปแบบ</th>
                  <th>ภาคการศึกษา</th>
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
                      <td style="width: 50%"><?php echo $row["major"]; ?></td>
                      <td><?php echo $row["type"]; ?></td>
                      <td><?php echo $row["year"]; ?></td>
                      <td class="project-actions text-right">
                        <a class="btn btn-info btn-sm" href="cwieCourseEdit.php?cwieCoid=<?php echo $row["id"]; ?>">
                          <i class="fas fa-pencil-alt">
                          </i>
                          Edit
                        </a>
                        <a class="btn btn-danger btn-sm" href="JavaScript:if(confirm('ยืนยันการลบข้อมูล?')==true){window.location='cwieCourseDel.php?cwieCoid=<?php echo $row["id"]; ?>';}">
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
                  <th>รูปแบบ</th>
                  <th>ภาคการศึกษา</th>
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
        window.location.href = 'cwieCourseReport.php';
      }

      // Bind click event to the button
      $('#openReportButton').on('click', function() {
        openCwieCourseReport();
      });
    });
  </script>
</body>

</html>