<?php
session_start();
include_once('connect.php');
$faculty_id = $_SESSION['faculty_id'];

if (isset($_POST['query'])) {
  $query = "SELECT * FROM major WHERE major LIKE '%{$_POST['query']}%' AND faculty = '$faculty_id'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    while ($res = mysqli_fetch_array($result)) {
      $course = htmlspecialchars($res["course"]);
      $major = htmlspecialchars($res["major"]);
      echo '<li tabindex="2" class="list-group-item" style="cursor:pointer;" value="' . $major . '">' . $course . '--' . $major . '</li>';
    }
  } else {
    echo "<li class='list-group-item text-center'>ไม่พบข้อมูล</li>";
  }
}
