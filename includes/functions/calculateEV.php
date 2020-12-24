<?php
function calculateEV($gt_what,$gt_id,$gt_carrier) {
$gt_permit_cellsite = towerInfoGet("permit_cellsite",$gt_id,$gt_carrier);
$gt_permit_suspected_carrier = towerInfoGet("permit_suspected_carrier",$gt_id,$gt_carrier);
$gt_trails_match = towerInfoGet("trails_match",$gt_id,$gt_carrier);
$gt_other_carriers_dont = towerInfoGet("other_carriers_dont",$gt_id,$gt_carrier);
$gt_antennas_match_carrier = towerInfoGet("antennas_match_carrier",$gt_id,$gt_carrier);
$gt_cellmapper_triangulation = towerInfoGet("cellmapper_triangulation",$gt_id,$gt_carrier);
$gt_image_evidence = towerInfoGet("image_evidence",$gt_id,$gt_carrier);
$gt_verified_by_visit = towerInfoGet("verified_by_visit",$gt_id,$gt_carrier);
$gt_sector_split_match = towerInfoGet("sector_split_match",$gt_id,$gt_carrier);
$gt_contact_permit_carrier = towerInfoGet("contact_permit_carrier",$gt_id,$gt_carrier);
$gt_archival_antenna_addition = towerInfoGet("archival_antenna_addition",$gt_id,$gt_carrier);
$gt_only_reasonable_location = towerInfoGet("only_reasonable_location",$gt_id,$gt_carrier);

$gt_evidence_score = 0;
if($gt_permit_cellsite == 'true') $gt_evidence_score = ($gt_evidence_score) + (1);
if($gt_permit_suspected_carrier == 'true') $gt_evidence_score = ($gt_evidence_score) + (14);
if($gt_trails_match == 'true') $gt_evidence_score = ($gt_evidence_score) + (4);
if($gt_other_carriers_dont == 'true') $gt_evidence_score = ($gt_evidence_score) + (2);
if($gt_antennas_match_carrier == 'true') $gt_evidence_score = ($gt_evidence_score) + (5);
if($gt_cellmapper_triangulation == 'true') $gt_evidence_score = ($gt_evidence_score) + (2);
if($gt_image_evidence == 'true') $gt_evidence_score = ($gt_evidence_score) + (10);
if($gt_verified_by_visit == 'true') $gt_evidence_score = ($gt_evidence_score) + (5);
if($gt_sector_split_match == 'true') $gt_evidence_score = ($gt_evidence_score) + (7 );
if($gt_contact_permit_carrier == 'true') $gt_evidence_score = ($gt_evidence_score) + (10);
if($gt_archival_antenna_addition == 'true') $gt_evidence_score = ($gt_evidence_score) + (3);
if($gt_only_reasonable_location == 'true') $gt_evidence_score = ($gt_evidence_score) + (3);

${"gt_" . $gt_what} = $gt_evidence_score;
return ${"gt_" . $gt_what};
}
 ?>
