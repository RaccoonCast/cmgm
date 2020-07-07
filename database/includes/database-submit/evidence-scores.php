<?php
// calculate evidence score (numerical value)

//default evidence score
$evidence_score = 0;

// Get data from URL
if(isset($_GET['permit_cellsite'])) $permit_cellsite = $_GET['permit_cellsite'];
if(isset($_GET['permit_suspected_carrier'])) $permit_suspected_carrier = $_GET['permit_suspected_carrier'];
if(isset($_GET['trails_match'])) $trails_match = $_GET['trails_match'];
if(isset($_GET['other_carriers_dont'])) $other_carriers_dont = $_GET['other_carriers_dont'];
if(isset($_GET['antennas_match_carrier'])) $antennas_match_carrier = $_GET['antennas_match_carrier'];
if(isset($_GET['evidence_text'])) $evidence_text = $_GET['evidence_text'];
if(isset($_GET['cellmapper_triangulation'])) $cellmapper_triangulation = $_GET['cellmapper_triangulation'];
if(isset($_GET['image_evidence'])) $image_evidence = $_GET['image_evidence'];
if(isset($_GET['verified_by_visit'])) $verified_by_visit = $_GET['verified_by_visit'];

// Change the unchecked checkboxes to false so we don't have NULL.
if(!isset($carrier_multiple)) $carrier_multiple = 'false';
if(!isset($permit_cellsite)) $permit_cellsite = 'false';
if(!isset($permit_suspected_carrier)) $permit_suspected_carrier = 'false';
if(!isset($trails_match)) $trails_match = 'false';
if(!isset($other_carriers_dont)) $other_carriers_dont = 'false';
if(!isset($antennas_match_carrier)) $antennas_match_carrier = 'false';
if(!isset($cellmapper_triangulation)) $cellmapper_triangulation = 'false';
if(!isset($image_evidence)) $image_evidence = 'false';
if(!isset($verified_by_visit)) $verified_by_visit = 'false';

// Add evidence score up
if($permit_cellsite == 'true') $evidence_score = ($evidence_score) + (1);
if($permit_suspected_carrier == 'true') $evidence_score = ($evidence_score) + (20);
if($trails_match == 'true') $evidence_score = ($evidence_score) + (5);
if($other_carriers_dont == 'true') $evidence_score = ($evidence_score) + (3);
if($antennas_match_carrier == 'true') $evidence_score = ($evidence_score) + (1);
if($cellmapper_triangulation == 'true') $evidence_score = ($evidence_score) + (2);
if($image_evidence == 'true') $evidence_score = ($evidence_score) + (10);
if($verified_by_visit == 'true') $evidence_score = ($evidence_score) + (5);
?>
