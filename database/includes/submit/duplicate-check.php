<?php
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

$sql = "SELECT * FROM database_db WHERE (carrier = '$carrier' AND lte_1 = '$LTE_1')";

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
  $colCount = 1;

    foreach ($row as $field => $value) {
      $sepCount = ($colCount++);
                  switch ($sepCount) {
                    case 8: $carrier = $value; break;
                    case 9: $latitude = $value;  break;
                    case 10: $longitude = $value;
                  }
                }
              }

$dblink = "map.php?latitude=$latitude&longitude=$longitude&carrier=$carrier";


if (!mysqli_num_rows($result) == 0) {
        echo 'This has already been added to the database.';
        $dont_create = 'true';
        echo '<meta http-equiv="refresh" content="2;url=' . $dblink . '">';
        mysqli_close($conn);
        exit;
} else {
    mysqli_close($conn);
    $dont_create = 'false';
}

?>