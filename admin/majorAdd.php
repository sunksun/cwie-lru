<?php
session_start();
include_once('connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | จัดการข้อมูลหลักสูตร</title>

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
            <h1>จัดการข้อมูลหลักสูตร</h1>
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
              <h3 class="card-title"><a href="#">เพิ่มข้อมูลหลักสูตร</a></h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
        <div class="card-body">
         <form action="majorSave.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="inputClientCompany">คณะ</label>
                <select class="form-control select2" name="faculty" style="width: 100%;">
                    <option selected="selected">--- เลือกคณะ ---</option>
					<?php
					$sql = "SELECT * FROM faculty";
					$result = $conn->query($sql);
					if($result->num_rows> 0){
					while($optionData=$result->fetch_assoc()){
					$option =$optionData['faculty'];
					$id =$optionData['fid'];
					?>
                    <option value="<?php echo $id; ?>"><?php echo $option; ?></option>
					<?php
						}
					}
					?>
                  </select>
            </div>
			<div class="form-group">
                <label for="inputClientCompany">หลักสูตร</label>
                <input type="text" name="major" id="inputClientCompany" class="form-control" required>
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
	$sql = "SELECT * FROM major";
	$result = $conn->query($sql);
	?>
	<section class="content">

      <!-- Default box -->
      <div class="card">
              <div class="card-header">
                <h3 class="card-title">รายการข้อมูลคณะ</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ลำดับ</th>
                    <th>คณะ</th>
                    <th>หลักสูตร</th>
                    <th></th>
                  </tr>
                  </thead>
                  <tbody>
				  <?php
				  if ($result->num_rows > 0) {
				  while($row = $result->fetch_assoc()) {
				  ?>
                  <tr>
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo $row["faculty"]; ?></td>
                    <td><?php echo $row["major"]; ?></td>
                    <td class="project-actions text-right">
                          <a class="btn btn-info btn-sm" href="majorEdit.php?majid=<?php echo $row["id"]; ?>">
                              <i class="fas fa-pencil-alt">
                              </i>
                              Edit
                          </a>
                          <a class="btn btn-danger btn-sm" href="#">
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
                    <th>คณะ</th>
                    <th>หลักสูตร</th>
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
