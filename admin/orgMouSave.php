<?php
// เปิดการแสดงข้อผิดพลาดทั้งหมด
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

// ดึงปีการศึกษาจากฟอร์มหรือใช้ค่าปีล่าสุด
if (isset($_POST['year']) && !empty($_POST['year'])) {
  $year = $_POST['year'];
} else {
  // ดึงปีการศึกษาล่าสุดจากตาราง year
  $latest_year_query = "SELECT year FROM year ORDER BY id DESC LIMIT 1";
  $latest_year_result = mysqli_query($conn, $latest_year_query);

  if ($latest_year_result && mysqli_num_rows($latest_year_result) > 0) {
    $latest_year_row = mysqli_fetch_assoc($latest_year_result);
    $year = $latest_year_row['year'];
  } else {
    $year = "2/2566"; // ค่าเริ่มต้นกรณีไม่พบข้อมูล
  }
}

$target_dir = "img_org/";

date_default_timezone_set("Asia/Bangkok");
$newfilename = date('dmYHis');

$name = mysqli_real_escape_string($conn, $_POST["name"]);
$address = mysqli_real_escape_string($conn, $_POST["address"]);
$subdistrict = mysqli_real_escape_string($conn, $_POST["subdistrict"]);
$district = mysqli_real_escape_string($conn, $_POST["district"]);
$province = mysqli_real_escape_string($conn, $_POST["province"]);
$postcode = mysqli_real_escape_string($conn, $_POST["postcode"]);
$date_mou1 = mysqli_real_escape_string($conn, $_POST["date_mou1"]);
$date_mou2 = mysqli_real_escape_string($conn, $_POST["date_mou2"]);
$date_mou3 = mysqli_real_escape_string($conn, $_POST["date_mou3"]);
$date_mou = "$date_mou1 $date_mou2 $date_mou3";
$note = mysqli_real_escape_string($conn, $_POST["note"]);
$time_mou = mysqli_real_escape_string($conn, $_POST["time_mou"]);
$tel1 = mysqli_real_escape_string($conn, $_POST["tel1"]);
$tel2 = mysqli_real_escape_string($conn, $_POST["tel2"]);
$fax = mysqli_real_escape_string($conn, $_POST["fax"]);
$line = mysqli_real_escape_string($conn, $_POST["line"]);
$facebook = mysqli_real_escape_string($conn, $_POST["facebook"]);
$website = mysqli_real_escape_string($conn, $_POST["website"]);
$email = mysqli_real_escape_string($conn, $_POST["email"]);

$post_by = $_SESSION['fullname'];

if (isset($_POST['save'])) {
  $sql = "INSERT INTO `organization_mou` (`faculty_id`, `name`, `address`, `subdistrict`, 
  `district`, `province`, `postcode`, `date_mou`, `time_mou`, `note`, `tel1`, `tel2`, 
  `fax`, `line`, `facebook`, `website`, `email`, `date_regis`, `post_by`, `year`) 
  VALUES ('$faculty_id', '$name', '$address', '$subdistrict', '$district', '$province', '$postcode', 
  '$date_mou', '$time_mou', '$note', '$tel1', '$tel2', '$fax', '$line', '$facebook', 
  '$website', '$email', current_timestamp(), '$post_by', '$year')";

  if (mysqli_query($conn, $sql)) {
    echo '<script language="javascript">';
    echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="orgMouAdd.php?year=' . $year . '"';
    echo '</script>';
  } else {
    echo '<script language="javascript">';
    echo 'alert("เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . mysqli_error($conn) . '"); location.href="orgMouAdd.php?year=' . $year . '"';
    echo '</script>';
  }
} elseif (isset($_POST['update'])) {
  $orgMouid = $_GET["orgMouid"];
  $sql = "UPDATE organization_mou SET 
  name = '$name', 
  address = '$address', 
  subdistrict = '$subdistrict', 
  district = '$district', 
  province = '$province', 
  postcode = '$postcode', 
  date_mou = '$date_mou', 
  time_mou = '$time_mou', 
  note = '$note', 
  tel1 = '$tel1', 
  tel2 = '$tel2', 
  fax = '$fax', 
  line = '$line', 
  facebook = '$facebook', 
  website = '$website', 
  email = '$email', 
  post_by = '$post_by',
  year = '$year'
  WHERE organization_mou.id = $orgMouid";

  if (mysqli_query($conn, $sql)) {
    echo '<script language="javascript">';
    echo 'alert("อัปเดตข้อมูลเรียบร้อยแล้ว"); location.href="orgMouAdd.php?year=' . $year . '"';
    echo '</script>';
  } else {
    echo '<script language="javascript">';
    echo 'alert("เกิดข้อผิดพลาดในการอัปเดตข้อมูล: ' . mysqli_error($conn) . '"); location.href="orgMouAdd.php?year=' . $year . '"';
    echo '</script>';
  }
}
