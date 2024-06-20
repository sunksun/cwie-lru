<?php
session_start();

include_once('connect.php');

if (isset($_POST['save'])) {
    $id = $_SESSION['id'];
    // ข้อมูลที่จะอัปเดต
    $newPassword = $_POST["new_password"]; // รหัสผ่านใหม่ที่ต้องการอัปเดต
    $hashedPassword = md5($newPassword); // เข้ารหัสรหัสผ่านด้วย MD5

    // คำสั่ง SQL เพื่ออัปเดตฟิลด์ pass_update และ password
    $sql = "UPDATE tblusers SET 
    pass_update = '1', 
    password = '$hashedPassword' 
    WHERE id = $id";

    // ตรวจสอบการอัปเดต
    if ($conn->query($sql) === TRUE) {
        echo '<script language="javascript">';
        echo 'alert("ตั้งรหัสผ่านใหม่ เรียบร้อยแล้ว"); location.href="../index.php"';
        echo '</script>';
    } else {
        echo '<script language="javascript">';
        echo 'alert("เกิดข้อผิดพลาด รหัสผ่านซ้ำ"); location.href="pass_update.php"';
        echo '</script>';
    }

    // ปิดการเชื่อมต่อ
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เข้าสู่ระบบ | สหกิจศึกษา :: มหาวิทยาลัยราชภัฏเลย</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <!-- sweetalert2 -->
    <script src="sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="sweetalert2/dist/sweetalert2.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <img src="../images/lru.png" width="120 px" alt="" class="">
                <p class="mb-1 text-center">
                    <a href="../index.php">สหกิจศึกษา มหาวิทยาลัยราชภัฏเลย</a>
                </p>
            </div>
            <div class="card-body">
                <p class="login-box-msg">ตั้งรหัสผ่านใหม่</p>

                <form method="post">
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="new_password" id="password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" name="save" class="btn btn-primary btn-block">ตกลง</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>


                <!-- /.social-auth-links -->
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
</body>

</html>