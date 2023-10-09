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
            <h1>การกิจกรรมการจัดการเรียนรู้ CWIE</h1>
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
              <h3 class="card-title"><a href="#">แก้ไขกิจกรรมการจัดการเรียนรู้ CWIE</a></h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
        <?php
          $actid = $_GET["actid"];
          $sql = "SELECT * FROM activity_cwie WHERE id = '$actid'";
          $result = $conn->query($sql);
          $row = $result->fetch_assoc()
        ?>
        <div class="card-body">
         <form action="activityCwieSave.php?actid=<?php echo "$actid"; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="inputClientCompany">ประเภทกิจกรรม</label>
                <select class="form-control select2" name="activity_type" style="width: 100%;">
                    <option selected="selected">--- เลือกประเภทกิจกรรม ---</option>
                    <?php
                    $sql = "SELECT * FROM activity_type";
                    $result = $conn->query($sql);
                    if($result->num_rows> 0){
                    while($optionData=$result->fetch_assoc()){
                    $option =$optionData['activity_type'];
                    $id =$optionData['id'];
                    ?>
                    <option value="<?php echo $option; ?>"
                    <?php if($option == $row["activity_type"]) echo 'selected="selected"'; ?>
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
					<label for="inputClientCompany">ชื่อกิจกรรม</label>
                    <input type="text" name="activity_name" class="form-control" value="<?php echo $row["activity_name"]; ?>">
                  </div>
                  <div class="col-6">
					<label for="inputClientCompany">สาขาวิชา</label>
          <select class="form-control select2" name="course" style="width: 100%;">
                    <option selected="selected">--- เลือกสาขาวิชา ---</option>
                    <?php
                    $sql = "SELECT * FROM course WHERE id_faculty = '$faculty_id'";
                    $result = $conn->query($sql);
                    if($result->num_rows> 0){
                    while($optionData=$result->fetch_assoc()){
                    $option =$optionData['name_course'];
                    $id =$optionData['id'];
                    ?>
                    <option value="<?php echo $option; ?>"
                    <?php if($option == $row["course"]) echo 'selected="selected"'; ?>
                    ><?php echo $option; ?></option>
                    <?php
                      }
                    }
                    ?>
                </select>
                  </div>
             </div>
             <div class="row form-group">
                  <div class="col-6">
					<label for="inputClientCompany">วันที่ดำเนินการ</label>
                    <input type="text" name="activity_date" class="form-control" value="<?php echo $row["activity_date"]; ?>">
                  </div>
                  <div class="col-6">
					<label for="inputClientCompany">จำนวนผู้เข้าร่วม</label>
            <input type="text" name="amount" class="form-control" value="<?php echo $row["amount"]; ?>">
                  </div>
             </div>
          <div class="form-group">
                <label for="inputClientCompany">หมายเหตุ</label>
                <input type="text" name="note" id="inputClientCompany" class="form-control" value="<?php echo $row["note"]; ?>">
          </div>
          <div class="form-group">
                <label for="inputName">ภาพกิจกรรม</label>
                <input class="form-control" type="file" name="filename" id="fileToUpload">
            </div>
          <div class="form-group">
            <img src="img_act_cwie/<?php echo $row["filename"]; ?>" class="img-rounded" alt="" width="150">
            <input type="hidden" id="imgname" name="imgname" value="<?php echo $row["filename"]; ?>">
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
	$sql = "SELECT * FROM activity_cwie";
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
