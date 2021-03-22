<?php
$sql = "SELECT * FROM userID WHERE userID = '$userID'";
$result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
    $colCount = 1;
    foreach ($row as $field => $value) {
      $sepCount = ($colCount++);
                  switch ($sepCount) {
                    case 1:  $userID = $value; break;
                    case 2:  $userIP = $value; break;
                    case 3:  $username = $value; break;
                    case 5:  $gmaps_api_key = $value; break;
                    case 6:  $default_carrier = $value; break;
                    case 7:  $default_latitude = $value; break;
                    case 8:  $default_longitude = $value; break;
                    case 9:  $theme = $value;
                     ?>
