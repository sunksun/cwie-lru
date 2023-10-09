<?php
session_start();
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
  <title>เพิ่มข้อมูลนักศึกษา</title>

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
              <h1>เพิ่มข้อมูลนักศึกษา</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Project Add</li>
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
                <h3 class="card-title"><a href="#">Import CSV File</a></h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <form action="studentSave.php" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="inputProjectLeader">คำนำหน้าชื่อ</label>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="prefix" value="นาย">
                      <label class="form-check-label">นาย</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="prefix" value="นางสาว">
                      <label class="form-check-label">นางสาว</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="prefix" value="1">
                      <label class="form-check-label">อื่น ๆ </label>
                    </div>
                    <input type="text" name="other_prefix" id="inputName" class="form-control" placeholder="โปรดระบุ">
                  </div>
                  <div class="row form-group">
                    <div class="col-6">
                      <label for="inputClientCompany">ชื่อ</label>
                      <input type="text" name="name" id="inputClientCompany" class="form-control">
                    </div>
                    <div class="col-6">
                      <label for="inputClientCompany">นามสกุล</label>
                      <input type="text" name="last_name" id="inputClientCompany" class="form-control">
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-6">
                      <label for="inputName">รหัสนักศึกษา</label>
                      <input type="text" name="student_id" id="inputName" class="form-control">
                    </div>
                    <div class="col-6">
                      <label for="inputName">หมู่เรียน</label>
                      <input type="text" name="sec" id="inputName" class="form-control" placeholder="ว.6601">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName">สาขาวิชา</label>
                    <input type="text" class="form-control" id="live_search" name="course" tabindex="1" placeholder="ค้นหาสาขาวิชา....">
                  </div>
                  <div>
                    <ul id="search_result" class="list-group ullist">

                    </ul>
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
                    <label for="inputStatus">คณะ</label>
                    <select name="faculty" id="inputStatus" class="form-control custom-select">
                      <option selected disabled>เลือกคณะ</option>
                      <option>คณะครุศาสตร์</option>
                      <option>คณะวิทยาการจัดการ</option>
                      <option>คณะวิทยาศาสตร์และเทคโนโลยี</option>
                      <option>คณะเทคโนโลยีอุตสาหกรรม</option>
                      <option>คณะมนุษยศาสตร์และสังคมศาสตร์</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="inputClientCompany">รูปภาพ (ขนาด 220x220 px)</label>
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
            <a href="#" class="btn btn-secondary float-right">ยกเลิก</a>
            <input type="submit" name="save" value="บันทึกข้อมูล" class="btn btn-success float-left">
          </div>
        </div>
        </form>
      </section>
      <!-- /.content -->
      <hr>
      <?php
      $sql = "SELECT * FROM tblstudent";
      $result = $conn->query($sql);
      ?>
      <section class="content">

        <!-- Default box -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">DataTable with default features</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ลำดับ</th>
                  <th>รหัสนักศึกษา</th>
                  <th>ชื่อ-นามสกุล</th>
                  <th>คณะ</th>
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
                      <td><?php echo $row["student_id"]; ?></td>
                      <td><?php echo $row["name"]; ?></td>
                      <td><?php echo $row["faculty"]; ?></td>
                      <td class="project-actions text-right">
                        <a class="btn btn-info btn-sm" href="numTeachCwieEdit.php?numTeacid=<?php echo $row["id"]; ?>">
                          <i class="fas fa-pencil-alt">
                          </i>
                          Edit
                        </a>
                        <a class="btn btn-danger btn-sm" href="JavaScript:if(confirm('ยืนยันการลบข้อมูล?')==true){window.location='studentDel.php?student_id=<?php echo $row["student_id"]; ?>';}">
                          <i class="fas fa-trash">
                          </i>
                          Delete
                        </a>
                      </td>
                    </tr>
                <?php
                  }
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>ลำดับ</th>
                  <th>สาขาวิชา</th>
                  <th>ชื่อ-นามสกุล</th>
                  <th>รหัสขึ้นทะเบียน</th>
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