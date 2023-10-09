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
  <title>Admin | แก้ไขข้อมูลเจ้าหน้าที่</title>

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
            <h1>จัดการจัดการข้อมูลเจ้าหน้าที่</h1>
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
              <h3 class="card-title"><a href="#">แก้ไขข้อมูลเจ้าหน้าที่</a></h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
        <?php
          $offid = $_GET["offid"];
          $sql = "SELECT * FROM tblusers WHERE id = '$offid'";
          $result = $conn->query($sql);
          $row = $result->fetch_assoc()
        ?>
        <div class="card-body">
         <form action="officerSave.php?offid=<?php echo "$offid";?>" method="post" enctype="multipart/form-data">
         <div class="form-group">
                <label for="inputClientCompany">คณะ</label>
                <select class="form-control select2" name="fid" style="width: 100%;" required>
                    <option selected="selected">--- เลือกคณะ ---</option>
                    <?php
                    $sql = "SELECT * FROM faculty";
                    $result = $conn->query($sql);
                    if($result->num_rows> 0){
                    while($optionData=$result->fetch_assoc()){
                    $option =$optionData['faculty'];
                    $fid =$optionData['fid'];
                    ?>
                    <option value="<?php echo $fid; ?>"
                    <?php if($option == $row["faculty"]) echo 'selected="selected"'; ?>
                    >
                    <?php echo $option; ?></option>
                    <?php
                      }
                    }
                    ?>
                </select>
              </div>
              <div class="row form-group">
                <div class="col-6">
					        <label for="inputClientCompany">ชื่อ-นามสกุล</label>
                  <input type="text" name="fullname" class="form-control" value="<?php echo $row["fullname"]; ?>">
                </div>
                <div class="col-6">
					        <label for="inputClientCompany">ตำแหน่ง</label>
                  <input type="text" name="position" class="form-control" value="<?php echo $row["position"]; ?>">
                </div>
              </div>
              <div class="row form-group">
                <div class="col-4">
					        <label for="inputClientCompany">อีเมล์</label>
                  <input type="eamil" name="useremail" class="form-control" value="<?php echo $row["useremail"]; ?>">
                </div>
                <div class="col-4">
					        <label for="inputClientCompany">รหัสผู้ใช้งาน</label>
                  <input type="text" name="username" class="form-control" value="<?php echo $row["username"]; ?>">
                </div>
                <div class="col-4">
					        <label for="inputClientCompany">รหัสผ่าน</label>
                  <input type="password" name="password" class="form-control" value="<?php echo $row["password"]; ?>">
                </div>
              </div>
              <div class="form-group">
                <label for="inputName">รูปภาพ</label>
                <div class="text-center">
                  <img src="img_officer/<?php echo $row["img"]; ?>" class="img-rounded" width="150 px" alt="...">
                </div>
                <br>
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
          <a href="#" class="btn btn-secondary float-right">ยกเลิก</a>
          <input type="submit" name="update" value="บันทึกข้อมูล" class="btn btn-success float-left">
        </div>
      </div>
      </form>
    </section>
    <!-- /.content -->
	<hr>
	<?php
	$sql = "SELECT * FROM tblusers";
	$result = $conn->query($sql);
	?>
	<section class="content">

      <!-- Default box -->
      
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
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
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
