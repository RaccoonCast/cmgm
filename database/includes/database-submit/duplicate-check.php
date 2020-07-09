<?php
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

$sql = "SELECT * FROM database_db WHERE (carrier = '$carrier' AND id = '$id')";

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$result = mysqli_query($conn, $sql);



while ($row = mysqli_fetch_assoc($result)) {
  $colCount = 1;

    foreach ($row as $field => $value) {
      $sepCount = ($colCount++);
                  switch ($sepCount) {
                    case 3: $carrier = $value; break;
                    case 4: $latitude = $value;  break;
                    case 5: $longitude = $value;
                  }
                }
              }
              
$dblink = "DatabaseMap.php?latitude=$latitude&longitude=$longitude&carrier=$carrier";
mysqli_close($conn);

if (!mysqli_num_rows($result) == 0) {
        $dont_create = 'true';

        header("$dblink");
        exit;
} else {
    $dont_create = 'false';
}

?>
