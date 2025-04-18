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
    <title>Admin | จัดการจำนวนคณาจารย์</title>

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
                            <h1>จัดการจำนวนคณาจารย์</h1>
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
                                <h3 class="card-title">เพิ่มข้อมูลจำนวนคณาจารย์</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="facultyTeacherSave.php" method="post">
                                    <div class="form-group">
                                        <label for="inputFaculty">คณะ</label>
                                        <select name="faculty_id" id="inputFaculty" class="form-control" required>
                                            <option value="">-- เลือกคณะ --</option>
                                            <?php
                                            $faculty_sql = "SELECT * FROM faculty ORDER BY faculty ASC";
                                            $faculty_result = $conn->query($faculty_sql);
                                            if ($faculty_result->num_rows > 0) {
                                                while ($faculty_row = $faculty_result->fetch_assoc()) {
                                                    echo '<option value="' . $faculty_row["fid"] . '">' . $faculty_row["faculty"] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputYear">ปีการศึกษา</label>
                                        <select name="year" id="inputYear" class="form-control" required>
                                            <option value="">-- เลือกปีการศึกษา --</option>
                                            <?php
                                            $year_sql = "SELECT * FROM year ORDER BY id DESC";
                                            $year_result = $conn->query($year_sql);
                                            if ($year_result->num_rows > 0) {
                                                while ($year_row = $year_result->fetch_assoc()) {
                                                    echo '<option value="' . $year_row["year"] . '">' . $year_row["year"] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputTeacherCount">จำนวนคณาจารย์ทั้งหมด</label>
                                        <input type="number" name="teacher_count" id="inputTeacherCount" class="form-control" required min="0">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="save" value="บันทึกข้อมูล" class="btn btn-success float-right">
                                    </div>
                                </form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>

                <hr>
                <?php
                // ดึงข้อมูลจำนวนอาจารย์แต่ละคณะ
                $sql = "SELECT ft.*, f.faculty, y.year 
                       FROM faculty_teachers ft
                       LEFT JOIN faculty f ON ft.faculty_id = f.fid
                       LEFT JOIN year y ON ft.year_id = y.id
                       ORDER BY ft.id DESC";
                $result = $conn->query($sql);
                ?>
                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">รายการข้อมูลจำนวนคณาจารย์</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">ลำดับ</th>
                                    <th width="30%">คณะ</th>
                                    <th width="20%">ปีการศึกษา</th>
                                    <th width="25%">จำนวนคณาจารย์ทั้งหมด</th>
                                    <th width="20%">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result && $result->num_rows > 0) {
                                    $i = 1;
                                    while ($row = $result->fetch_assoc()) {
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $row["faculty"]; ?></td>
                                            <td><?php echo $row["year"]; ?></td>
                                            <td><?php echo $row["teacher_count"]; ?></td>
                                            <td class="project-actions text-center">
                                                <a class="btn btn-info btn-sm" href="#" data-toggle="modal" data-target="#editModal<?php echo $row["id"]; ?>">
                                                    <i class="fas fa-pencil-alt"></i>
                                                    แก้ไข
                                                </a>
                                                <a class="btn btn-danger btn-sm" href="JavaScript:if(confirm('ยืนยันการลบข้อมูล?')==true){window.location='facultyTeacherDelete.php?id=<?php echo $row["id"]; ?>';}">
                                                    <i class="fas fa-trash"></i>
                                                    ลบ
                                                </a>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editModal<?php echo $row["id"]; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel">แก้ไขข้อมูลจำนวนคณาจารย์</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="facultyTeacherUpdate.php" method="post">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                                                            <div class="form-group">
                                                                <label for="editFaculty<?php echo $row["id"]; ?>">คณะ</label>
                                                                <select name="faculty_id" id="editFaculty<?php echo $row["id"]; ?>" class="form-control" required>
                                                                    <?php
                                                                    $faculty_sql2 = "SELECT * FROM faculty ORDER BY faculty ASC";
                                                                    $faculty_result2 = $conn->query($faculty_sql2);
                                                                    if ($faculty_result2->num_rows > 0) {
                                                                        while ($faculty_row2 = $faculty_result2->fetch_assoc()) {
                                                                            $selected = ($faculty_row2["fid"] == $row["faculty_id"]) ? "selected" : "";
                                                                            echo '<option value="' . $faculty_row2["fid"] . '" ' . $selected . '>' . $faculty_row2["faculty"] . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="editYear<?php echo $row["id"]; ?>">ปีการศึกษา</label>
                                                                <select name="year" id="editYear<?php echo $row["id"]; ?>" class="form-control" required>
                                                                    <?php
                                                                    $year_sql2 = "SELECT * FROM year ORDER BY id DESC";
                                                                    $year_result2 = $conn->query($year_sql2);
                                                                    if ($year_result2->num_rows > 0) {
                                                                        while ($year_row2 = $year_result2->fetch_assoc()) {
                                                                            $selected = ($year_row2["year"] == $row["year"]) ? "selected" : "";
                                                                            echo '<option value="' . $year_row2["year"] . '" ' . $selected . '>' . $year_row2["year"] . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="editTeacherCount<?php echo $row["id"]; ?>">จำนวนคณาจารย์ทั้งหมด</label>
                                                                <input type="number" name="teacher_count" id="editTeacherCount<?php echo $row["id"]; ?>" class="form-control" value="<?php echo $row["teacher_count"]; ?>" required min="0">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                                                            <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                        $i++;
                                    }
                                } else {
                                    echo '<tr><td colspan="5" class="text-center">ไม่พบข้อมูล</td></tr>';
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>คณะ</th>
                                    <th>ปีการศึกษา</th>
                                    <th>จำนวนคณาจารย์ทั้งหมด</th>
                                    <th>จัดการ</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0.0
            </div>
            <strong>Copyright &copy; 2023-2025 <a href="https://www.lru.ac.th">มหาวิทยาลัยราชภัฏเลย</a>.</strong> All rights reserved.
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
        });
    </script>
</body>

</html>