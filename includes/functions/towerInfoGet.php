<?php
function towerInfoGet($gt_what,$gt_id,$gt_carrier) {
$gt_sql = "SELECT $gt_what FROM database_db WHERE LTE_1='$gt_id' OR LTE_2='$gt_id' OR LTE_3='$gt_id' OR LTE_4='$gt_id' OR LTE_5='$gt_id' AND carrier='$gt_carrier'";
include 'sqlpw.php';
$conn = mysqli_connect($servername, $username, $password, $dbname);

$result = mysqli_query($conn, $gt_sql);
      while ($row = mysqli_fetch_assoc($result)) {

        $colCount = 1;
          foreach ($row as $field => $value) {
            $sepCount = ($colCount++);

            switch ($sepCount) {
              case 1: ${"gt_" . $gt_what} = $value; break;

            }
          }
        }
        return ${"gt_" . $gt_what};
}
