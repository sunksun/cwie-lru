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
    <title>Admin | โครงการและกิจกรรม</title>

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
                                <h3 class="card-title"><a href="#">แก้ไขโครงการและกิจกรรม</a></h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <?php
                            $actid = $_GET["actid"];
                            $sql = "SELECT * FROM activity WHERE id = '$actid'";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();

                            $date = $row["activity_date"];

                            // ใช้ explode() เพื่อแยกคำ
                            $parts = explode(' ', $date, 3);
                            //echo "วัน: " . $parts[0] . "\n"; // 6
                            //echo "เดือน: " . $parts[1] . "\n"; // มีนาคม
                            //echo "ปี: " . $parts[2] . "\n"; // 2565
                            //echo $row["activity_id"];

                            ?>
                            <div class="card-body">
                                <form action="activitySave.php?actid=<?php echo "$actid"; ?>" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="inputClientCompany">ประเภทกิจกรรม</label>
                                        <select class="form-control select2" name="activity_type" style="width: 100%;">
                                            <option selected="selected">--- เลือกประเภทกิจกรรม ---</option>
                                            <?php
                                            $sql = "SELECT * FROM activity_type";
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                while ($optionData = $result->fetch_assoc()) {
                                                    $option = $optionData['activity_type'];
                                                    $id = $optionData['id'];
                                            ?>
                                                    <option value="<?php echo $option; ?>" <?php if ($option == $row["activity_type"]) echo 'selected="selected"'; ?>>
                                                        <?php echo $option; ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-6">
                                            <label for="inputClientCompany">ชื่อโครงการ/กิจกรรม</label>
                                            <input type="text" name="activity_name" value="<?php echo $row["activity_name"]; ?>" class="form-control">
                                        </div>
                                        <div class="col-2">
                                            <label for="inputClientCompany">วันที่ดำเนินการ</label>
                                            <select id="inputState" class="form-control" name="date1">
                                                <option selected>วันที่</option>
                                                <?php
                                                $sql = "SELECT * FROM day";
                                                $result = $conn->query($sql);
                                                if ($result->num_rows > 0) {
                                                    while ($optionData = $result->fetch_assoc()) {
                                                        $option = $optionData['day'];
                                                ?>
                                                        <option value="<?php echo $option; ?>" <?php if ($option == $parts[0]) echo 'selected="selected"'; ?>>
                                                            <?php echo $option; ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-2">
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
                                                        <option value="<?php echo $option; ?>" <?php if ($option == $parts[1]) echo 'selected="selected"'; ?>>
                                                            <?php echo $option; ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <label for="inputClientCompany">&nbsp;</label>
                                            <select id="inputState" class="form-control" name="date3">
                                                <option selected>ปี พ.ศ.</option>
                                                <?php
                                                // สร้างรายการปี 2564 ถึง 2570
                                                for ($year = 2564; $year <= 2570; $year++) {
                                                    echo "<option value='$year'>$year</option>";
                                                }

                                                // กำหนดค่าตัวแปร $optionData
                                                $optionData = $parts[2];

                                                // ตรวจสอบว่ามีตัวแปร $parts และ $parts[2] ถูกกำหนดหรือไม่
                                                if (isset($parts[2])) {
                                                    $option = $optionData;
                                                ?>
                                                    <option value="<?php echo $option; ?>" <?php if ($option == $parts[2]) echo 'selected="selected"'; ?>>
                                                        <?php echo $option; ?>
                                                    </option>
                                                <?php
                                                }
                                                ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputClientCompany">รายละเอียด</label>
                                        <textarea class="form-control" rows="3" name="details"><?php echo $row["details"]; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName">ภาพกิจกรรม</label>
                                        <div class="text-center">
                                            <img src="img_act/<?php echo $row["filename"]; ?>" class="img-rounded" width="150 px" alt="...">
                                        </div>
                                        <br>
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
                        <input type="submit" name="update" value="บันทึกข้อมูล" class="btn btn-success float-left">
                    </div>
                </div>
                </form>
            </section>
            <!-- /.content -->
            <hr>

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