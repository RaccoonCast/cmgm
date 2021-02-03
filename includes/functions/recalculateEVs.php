<?php
function recalculatEVs($limit) {
$sql = "SELECT LTE_1,carrier FROM database_db LIMIT $limit;";
include 'sqlpw.php';
$conn = mysqli_connect($servername, $username, $password, $dbname);
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {

 $colCount = 1;
   foreach ($row as $field => $value) {
     $sepCount = ($colCount++);

switch ($sepCount) {
 case 1:  $gt_id = $value; break;
 case 2:  $gt_carrier = $value;
 $evScore = calculateEV("calculateEV",$gt_id,$gt_carrier);
 echo $evScore;
 echo "\n";
break;
           }
   }
}
}
?>
