<?php
$ip = $_SERVER["REMOTE_ADDR"];
$sql = "SELECT userIP FROM userID WHERE userIP = '$ip'";

$result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
    $colCount = 1;
    foreach ($row as $field => $value) {
      $sepCount = ($colCount++);
                  switch ($sepCount) {
                    case 1:  $IPfromDB = $value;
                  }
            }

    }
if (isset($IPfromDB)) {
  $maps_api_key = file_get_contents($siteroot . "/maps_api_key.hiddenpass", true);
}
?>
