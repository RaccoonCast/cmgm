<?php
// Create connection
if (empty($LTE_1)) {
  echo "Error, " . "LTE_1" . " variable was not set."
  ?><br><br><button onclick="window.history.go(-1)">Return</button><?php
  die();
}
if (empty($status)) {
  echo "Error, tower status variable was not set."
  ?><br><br><button onclick="window.history.go(-1)">Return</button><?php
  die();
}
if ($status == "verified" && isset($LTE_1)) {

$sql = "SELECT id,carrier,latitude,longitude FROM database_db WHERE (carrier = '$carrier' AND LTE_1 = '$LTE_1')";

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
  $colCount = 1;

    foreach ($row as $field => $value) {
      $sepCount = ($colCount++);
                  switch ($sepCount) {
                    case 1: $id = $value; break;
                    case 2: $carrier = $value; break;
                    case 3: $latitude = $value;  break;
                    case 4: $longitude = $value;
                  }
                }
              }

$dblink = "Map.php?latitude=$latitude&longitude=$longitude&carrier=$carrier&zoom=17";


if (!mysqli_num_rows($result) == 0) {
        echo 'This has already been added to the database, ID for that row is: ' . $id . ', redirecting now...';
        echo '<meta http-equiv="refresh" content="5;url=' . $dblink . '">';
        mysqli_close($conn);
        die();
} else {
    mysqli_close($conn);
}
}
?>
