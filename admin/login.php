<?php
session_start();

include_once('connect.php');

if (isset($_POST['login'])) {

  $sql = "SELECT * FROM tblusers WHERE username = ? AND password = ? AND faculty_id = ?;";
  $uname = $_POST['username'];
  $password = md5($_POST['password']);
  $faculty_id = $_POST['faculty_id'];

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sss', $uname, $password, $faculty_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  if ($row > 0 && $row['pass_update'] == '0') {
    $_SESSION['id'] = $row['id'];
    $_SESSION['fullname'] = $row['fullname'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['faculty'] = $row['faculty'];
    $_SESSION['position'] = $row['position'];
    $_SESSION['faculty_id'] = $row['faculty_id'];
    session_write_close();
    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal.fire({
            title: "สำเร็จ!",
            text: "เข้าสู่หน้าแก้ไขรหัสผ่าน",
            type: "success",
            icon: "success"
        });';
    echo '}, 500 );</script>';

    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { 
            window.location.href = "pass_update.php";';
    echo '}, 3000 );</script>';
  } else if ($row > 0 && $row['pass_update'] == '1') {
    $_SESSION['id'] = $row['id'];
    $_SESSION['fullname'] = $row['fullname'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['faculty'] = $row['faculty'];
    $_SESSION['position'] = $row['position'];
    $_SESSION['faculty_id'] = $row['faculty_id'];
    session_write_close();
    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal.fire({
            title: "สำเร็จ!",
            text: "เข้าสู่ระบบเรียบร้อย!",
            type: "success",
            icon: "success"
        });';
    echo '}, 500 );</script>';

    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { 
            window.location.href = "index.php";';
    echo '}, 3000 );</script>';
  } else {
    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal.fire({
            title: "ผิดพลาด!",
            text: "กรุณาลองใหม่!",
            type: "warning",
            icon: "error"
        });';
    echo '}, 500);</script>';

    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { 
        window.location.href = "login.php";';
    echo '}, 3000 );</script>';
  }
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
  <script>
    function validateForm() {
      let x = document.forms["myForm"]["username"].value;
      if (x == "") {
        alert("คุณยังไม่ได้กรอกชื่อผู้ใช้งาน");
        document.forms["myForm"]["username"].focus();
        return false;
      }
      let y = document.forms["myForm"]["password"].value;
      if (y == "") {
        alert("คุณยังไม่ได้กรอกข้อมูลรหัสผ่าน");
        document.forms["myForm"]["password"].focus();
        return false;
      }
      let z = document.forms["myForm"]["faculty_id"].value;
      if (z == "0") {
        alert("คุณยังไม่ได้เลือกคณะ");
        document.forms["myForm"]["faculty_id"].focus();
        return false;
      }
    }
  </script>
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
        <p class="login-box-msg">สำหรับอาจารย์/เจ้าหน้าที่</p>

        <form name="myForm" method="post" onsubmit="return validateForm()">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Username" name="username" id="username">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password" name="password" id="password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3 justify-content-end">
            <select class="form-control" name="faculty_id" id="faculty_id" aria-label="Default select example">
              <option value="0" selected>---- เลือกคณะ ----</option>
              <option value="01">คณะวิทยาการจัดการ</option>
              <option value="02">คณะวิทยาศาสตร์และเทคโนโลยี</option>
              <option value="03">คณะเทคโนโลยีอุตสาหกรรม</option>
              <option value="04">คณะมนุษยศาสตร์และสังคมศาสตร์</option>
              <option value="05">คณะครุศาสตร์</option>
            </select>
          </div>

          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <p class="mb-1">
                  <a href="login2.php">ผู้ดูแลระบบ</a>
                </p>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" name="login" class="btn btn-primary btn-block">เข้าสู่ระบบ</button>
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