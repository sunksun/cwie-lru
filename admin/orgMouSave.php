<?php
session_start();
include_once('connect.php');
$target_dir = "img_org/";

date_default_timezone_set("Asia/Bangkok");
$newfilename = date('dmYHis');

$name = $_POST["name"];
$address = $_POST["address"];
$subdistrict = $_POST["subdistrict"];
$district = $_POST["district"];
$province = $_POST["province"];
$postcode = $_POST["postcode"];
$date_mou1 = $_POST["date_mou1"];
$date_mou2 = $_POST["date_mou2"];
$date_mou3 = $_POST["date_mou3"];
$date_mou = "$date_mou1 $date_mou2 $date_mou3";
$note = $_POST["note"];
$time_mou = $_POST["time_mou"];
$tel1 = $_POST["tel1"];
$tel2 = $_POST["tel2"];
$fax = $_POST["fax"];
$line = $_POST["line"];
$facebook = $_POST["facebook"];
$website = $_POST["website"];
$email = $_POST["email"];

$post_by = $_SESSION['fullname'];

if (isset($_POST['save'])) {
	$sql = "INSERT INTO `organization_mou` (`name`, `address`, `subdistrict`, 
  `district`, `province`, `postcode`, `date_mou`, `time_mou`, `note`, `tel1`, `tel2`, 
  `fax`, `line`, `facebook`, `website`, `email`, `date_regis`, `post_by`) 
  VALUES ('$name', '$address', '$subdistrict', '$district', '$province', '$postcode', 
  '$date_mou', '$time_mou', '$note', '$tel1', '$tel2', '$fax', '$line', '$facebook', 
  '$website', '$email', current_timestamp(), '$post_by')";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="orgMouAdd.php"';
	echo '</script>';
} elseif (isset($_POST['update'])){
  $orgMouid = $_GET["orgMouid"];
	$sql = "UPDATE organization_mou SET name = '$name', 
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
  post_by = '$post_by' 
  WHERE organization_mou.id = $orgMouid";
	mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("อัปเดทข้อมูลเรียบร้อยแล้ว"); location.href="orgMouAdd.php"';
	echo '</script>';
}

?>