<?php
include_once('connect.php');

if (isset($_POST['query'])) {
  $query = "SELECT * FROM major WHERE major LIKE '%{$_POST['query']}%'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($res = mysqli_fetch_array($result)) {

      echo '<li tabindex = "2" class=list-group-item style=cursor:pointer value="$res[major]">' . $res["course"] . '--' . $res["major"] . '</li>';
    }
  } else {
    echo "<div class='alert alert-danger mt-3 text-center' role='alert'>
          Data not found
      </div>
      ";
  }
}
