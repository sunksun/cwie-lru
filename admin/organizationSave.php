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
$tel1 = $_POST["tel1"];
$tel2 = $_POST["tel2"];
$fax = $_POST["fax"];
$line = $_POST["line"];
$facebook = $_POST["facebook"];
$website = $_POST["website"];
$email = $_POST["email"];
$post_by = $_SESSION['fullname'];

//$img = $_FILES["fileToUpload"]["name"];

$temp = explode(".", $_FILES["logo"]["name"]);
$newfilename = $newfilename . '.' . end($temp);

$target_file = $target_dir . basename($newfilename);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["logo"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
}

// Check if file already exists
if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["logo"]["size"] > 500000) {
  echo '<script language="javascript">';
  echo 'alert("Sorry, your file is too large."); location.href="newsAdd.php"';
  echo '</script>';
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo '<script language="javascript">';
  echo 'alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed."); location.href="newsAdd.php"';
  echo '</script>';
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
    $sql = "INSERT INTO `organization` (`name`, `address`, `subdistrict`, `district`, 
    `province`, `postcode`, `tel1`, `tel2`, `fax`, `line`, `facebook`, `website`, `email`, 
    `logo`, `date_regis`, `post_by`) VALUES 
    ('$name', '$address', '$subdistrict', '$district', '$province', '$postcode', '$tel1', 
    '$tel2', '$fax', '$line', '$facebook', '$website', '$email', 
    '$newfilename', current_timestamp(), '$post_by')";
    mysqli_query($conn, $sql);
	echo '<script language="javascript">';
	echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว"); location.href="organizationAdd.php"';
	echo '</script>';
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
?>