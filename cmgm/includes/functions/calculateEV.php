<?php
function calculateEV($id,$carrier) {

include "sqlpw.php";
$db_variables = "id='$id'";
$database_get_list = "permit_score,trails_match,carriers_dont_trail_match,antennas_match_carrier,cellmapper_triangulation,image_evidence,
verified_by_visit,sector_split_match,archival_antenna_addition,only_reasonable_location,alt_carriers_here,other_user_map_primary";
$sql = "SELECT $database_get_list FROM database_db WHERE $db_variables";
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {
      $$key = $value;
      }
    }

include "EV-math.php";
return $ev;
}
 ?>
