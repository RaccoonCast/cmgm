<?php
// Create connection
if(!isset($_GET['status'])) $status = "unverifed";
if ($status == "verified") {
$conn = mysqli_connect($servername, $username, $password, $dbname);

$sql = "SELECT * FROM database_db WHERE (carrier = '$carrier' AND lte_1 = '$LTE_1')";

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
  $colCount = 1;

    foreach ($row as $field => $value) {
      $sepCount = ($colCount++);
                  switch ($sepCount) {
                    case 1: $id = $value; break;
                    case 15: $carrier = $value; break;
                    case 16: $latitude = $value;  break;
                    case 17: $longitude = $value;
                  }
                }
              }

$dblink = "map.php?latitude=$latitude&longitude=$longitude&carrier=$carrier&zoom=17";


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
