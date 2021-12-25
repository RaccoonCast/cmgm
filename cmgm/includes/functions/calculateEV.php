<?php
function calculateEV($id,$carrier) {

include "sqlpw.php";
$db_vars = "id='$id' AND carrier='$carrier'";
$database_get_list = "permit_score,trails_match,carriers_ruled_out,antennas_match_carrier,cellmapper_triangulation,image_evidence,
verified_by_visit,sector_split_match,archival_antenna_addition,only_reasonable_location,alt_carriers_here,other_user_map_primary";
$sql = "SELECT $database_get_list FROM db WHERE $db_vars";
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {
      $$key = $value;
      }
    }

include "calculateEV-math.php";
return $ev;
}
 ?>
