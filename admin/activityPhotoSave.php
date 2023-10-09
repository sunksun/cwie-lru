<?php 
session_start();
include_once('connect.php');
     
  if (isset($_POST['save'])) {
  $activity_id = $_POST['activity_id'];
  //echo "$activity_id";
  mkdir("img_act/$activity_id/");
  $uploadFolder = "img_act/$activity_id/"; 

  foreach ($_FILES['imageFile']['tmp_name'] as $key => $image) {
    $imageTmpName = $_FILES['imageFile']['tmp_name'][$key];
    $imageName = $_FILES['imageFile']['name'][$key];
    $result = move_uploaded_file($imageTmpName,$uploadFolder.$imageName);

    // save to database
    $query = "INSERT INTO activity_photo (activity_id, filename, date_regis) 
    VALUES ('$activity_id', '$imageName', current_timestamp());";
    $run = $conn->query($query) or die("Error in saving image".$connection->error);
}
if ($result) {
    echo '<script>alert("Images uploaded successfully !")</script>';
    echo '<script>window.location.href="activityPhotoAdd.php";</script>';
}
  
}

 
?>