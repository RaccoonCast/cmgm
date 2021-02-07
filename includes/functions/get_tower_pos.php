<?php


function towerPosGet($gt_id,$gt_carrier) {
$servername = 'mysql.cmgm.gq';
$username = 'cmgm';
$password = 'My$QLP@$$w0rd';
$dbname = 'cmgm';
$conn = mysqli_connect($servername, $username, $password, $dbname);
$gt_sql = "SELECT latitude,longitude FROM database_db WHERE LTE_1ss='$gt_id' AND carrier='$gt_carrier'";
$result = mysqli_query($conn, $gt_sql);
      while ($row = mysqli_fetch_assoc($result)) {

        $colCount = 1;
          foreach ($row as $field => $value) {
            $sepCount = ($colCount++);

            switch ($sepCount) {
              case 1: $gt_latitude = $value; break;
              case 2: $gt_longitude = $value;
            }
          }
        }
//echo $gt_longitude;
return $gt_longitude;
}
?>
