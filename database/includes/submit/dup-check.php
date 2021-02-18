<?php
// Create connection
if(!isset($status)) $status = 'unverified';
if ($status == "verified") {
$conn = mysqli_connect($servername, $username, $password, $dbname);

$sql = "SELECT * FROM database_db WHERE (carrier = '$carrier' AND lte_1 = '$LTE_1')";

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
  $colCount = 1;

    foreach ($row as $field => $value) {
      $sepCount = ($colCount++);
                  switch ($sepCount) {
                    case 1: $row_id_dupcheck = $value; break;
                    case 15: $carrier = $value; break;
                    case 16: $latitude = $value;  break;
                    case 17: $longitude = $value;
                  }
                }
              }

$dblink = "DatabaseMap.php?latitude=$latitude&longitude=$longitude&carrier=$carrier";


if (!mysqli_num_rows($result) == 0) {
        echo 'This has already been added to the database as ' . $row_id_dupcheck . '.';
        $dont_create = 'true';
        echo '<meta http-equiv="refresh" content="200;url=' . $dblink . '">';
        mysqli_close($conn);
        exit;
} else {
    mysqli_close($conn);
    $dont_create = 'false';
}
} else {
    $dont_create = 'false';
}
?>
