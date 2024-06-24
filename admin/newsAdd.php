<?php
session_start();
$user_img = $_SESSION['img'];
include_once('connect.php');
$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];
$faculty = $_SESSION['faculty'];
$position = $_SESSION['position'];
$faculty_id = $_SESSION['faculty_id'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | จัดการข่าวประชาสัมพันธ์</title>

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
                <h3 class="card-title"><a href="#">เพิ่มข่าวประชาสัมพันธ์</a></h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <form action="newsSave.php" method="post" enctype="multipart/form-data">
                  <div class="row form-group">
                    <div class="col-6">
                      <div class="form-group">
                        <label for="inputClientCompany">หัวข้อข่าว</label>
                        <input type="text" name="title" id="inputClientCompany" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-2">
                      <div class="form-group">
                        <label for="inputClientCompany">วันที่จัดกิจกรรม</label>
                        <input type="text" name="date1" id="inputClientCompany" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-2">
                      <div class="form-group">
                        <label for="inputClientCompany">&nbsp;</label>
                        <select id="inputState" class="form-control" name="date2">
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
                    </div>
                    <div class="col-2">
                      <div class="form-group">
                        <label for="inputClientCompany">&nbsp;</label>
                        <select id="inputState" class="form-control" name="date3">
                          <option selected>ปี พ.ศ.</option>
                          <?php
                          for ($year = 2564; $year <= 2570; $year++) {
                            echo "<option value='$year'>$year</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputClientCompany">ประเภทข่าวประชาสัมพันธ์</label>
                    <!-- radio -->
                    <div class="form-group">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="new_type" value="information">
                        <label class="form-check-label">ข่าวประชาสัมพันธ์</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="new_type" value="Highlight">
                        <label class="form-check-label">Highlight</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="new_type" value="next_activity">
                        <label class="form-check-label">กิจกรรมถัดไป</label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName">รายละเอียดข่าว (Paragraph 1)</label>
                    <textarea name="detail" class="form-control" id="exampleFormControlTextarea1" rows="3" required></textarea>
                  </div>
                  <div class="form-group">
                    <label for="inputName">รายละเอียดข่าว (Paragraph 2)</label>
                    <textarea name="detail2" class="form-control" id="exampleFormControlTextarea1" rows="3" required></textarea>
                  </div>
                  <div class="form-group">
                    <label for="inputName">รูปภาพ (รูปข่าวประชาสัมพันธ์ ขนาด 370x360px / รูป Highlight ขนาด 666x799px / รูป กิจกรรมถัดไป ขนาด 460x549px )</label>
                    <input class="form-control" type="file" name="fileToUpload" id="fileToUpload">
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
      $sql = "SELECT * FROM `news` ORDER BY `id` DESC";
      $result = $conn->query($sql);
      ?>
      <section class="content">

        <!-- Default box -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">รายการข่าวประชาสัมพันธ์</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ลำดับ</th>
                  <th>ชื่อหัวข้อข่าว</th>
                  <th>วันที่</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                      <td><?php echo $row["id"]; ?></td>
                      <td>
                        <?php
                        $title = $row["title"];
                        if (mb_strlen($title) > 40) {
                          $title = mb_substr($title, 0, 40) . '...';
                        }
                        echo $title;
                        ?>
                      </td>
                      <td><?php echo $row["date_time"]; ?></td>
                      <td class="project-actions text-right">
                        <a class="btn btn-primary btn-sm" href="#">
                          <i class="fas fa-folder"></i>
                          View
                        </a>
                        <a class="btn btn-info btn-sm" href="newsEdit.php?newid=<?php echo $row["id"]; ?>">
                          <i class="fas fa-pencil-alt"></i>
                          Edit
                        </a>
                        <a class="btn btn-danger btn-sm" href="JavaScript:if(confirm('ยืนยันการลบข้อมูล?')==true){window.location='newsDel.php?newid=<?php echo $row["id"]; ?>';}">
                          <i class="fas fa-trash"></i>
                          Delete
                        </a>
                      </td>
                    </tr>
                <?php
                  }
                } else {
                  echo "<tr><td colspan='4'>No news found.</td></tr>";
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>ลำดับ</th>
                  <th>ชื่อหัวข้อข่าว</th>
                  <th>วันที่</th>
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
    });
  </script>
</body>

</html>