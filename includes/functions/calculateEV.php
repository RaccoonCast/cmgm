<?php
function calculateEV($gt_id,$gt_carrier) {
  include "sqlpw.php";

  $gt_evidence_score = 0;
  $db_variables = "LTE_1='$gt_id' OR LTE_2='$gt_id' OR LTE_3='$gt_id' OR LTE_4='$gt_id' OR LTE_5='$gt_id' OR LTE_6='$gt_id'";
  $database_get_list = "permit_cellsite,permit_suspected_carrier,trails_match,other_carriers_dont,antennas_match_carrier,cellmapper_triangulation,image_evidence,
  verified_by_visit,sector_split_match,contact_permit_carrier,archival_antenna_addition,only_Reasonable_location";
  $sql = "SELECT $database_get_list FROM database_db WHERE $db_variables";
  $result = mysqli_query($conn, $sql);
  while ($row = mysqli_fetch_assoc($result)) {

    $colCount = 1;
      foreach ($row as $field => $value) {
        $sepCount = ($colCount++);

  switch ($sepCount) {
    case 1:  $gt_permit_cellsite = $value; break;
    case 2:  $gt_permit_suspected_carrier = $value; break;
    case 3:  $gt_trails_match = $value; break;
    case 4:  $gt_other_carriers_dont = $value; break;
    case 5:  $gt_antennas_match_carrier = $value; break;
    case 6:  $gt_cellmapper_triangulation = $value; break;
    case 7:  $gt_image_evidence = $value; break;
    case 8:  $gt_verified_by_visit = $value; break;
    case 9:  $gt_sector_split_match = $value; break;
    case 10:  $gt_contact_permit_carrier = $value; break;
    case 11:  $gt_archival_antenna_addition = $value; break;
    case 12:  $gt_only_reasonable_location= $value;
    if($gt_permit_cellsite == 'true') $gt_evidence_score = ($gt_evidence_score) + (1);
    if($gt_permit_suspected_carrier == 'true') $gt_evidence_score = ($gt_evidence_score) + (14);
    if($gt_trails_match == 'true') $gt_evidence_score = ($gt_evidence_score) + (4);
    if($gt_other_carriers_dont == 'true') $gt_evidence_score = ($gt_evidence_score) + (4);
    if($gt_antennas_match_carrier == 'true') $gt_evidence_score = ($gt_evidence_score) + (5);
    if($gt_cellmapper_triangulation == 'true') $gt_evidence_score = ($gt_evidence_score) + (3);
    if($gt_image_evidence == 'true') $gt_evidence_score = ($gt_evidence_score) + (9);
    if($gt_verified_by_visit == 'true') $gt_evidence_score = ($gt_evidence_score) + (10);
    if($gt_sector_split_match == 'true') $gt_evidence_score = ($gt_evidence_score) + (8);
    if($gt_contact_permit_carrier == 'true') $gt_evidence_score = ($gt_evidence_score) + (8);
    if($gt_archival_antenna_addition == 'true') $gt_evidence_score = ($gt_evidence_score) + (3);
    if($gt_only_reasonable_location == 'true') $gt_evidence_score = ($gt_evidence_score) + (4);
    break;
              }
      }
  }
return $gt_evidence_score;
}
 ?>
