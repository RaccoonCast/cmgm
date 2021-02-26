<?php
function towerPosGet($gt_id,$gt_carrier) {
$siteroot = $_SERVER['DOCUMENT_ROOT'];
$servername = 'mysql.cmgm.gq';
$username = 'cmgm';
$password = file_get_contents($siteroot . "/secret-sql-login.hiddenpass", true);
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
return $gt_longitude;
}
?>
