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
if(isset($_GET['evidence_link'])) $evidence_link = $_GET['evidence_link'];
if(isset($_GET['cellmapper_triangulation'])) $cellmapper_triangulation = $_GET['cellmapper_triangulation'];
if(isset($_GET['image_evidence'])) $image_evidence = $_GET['image_evidence'];
if(isset($_GET['verified_by_visit'])) $verified_by_visit = $_GET['verified_by_visit'];
if(isset($_GET['sector_split_match'])) $sector_split_match = $_GET['sector_split_match'];
if(isset($_GET['contact_permit_carrier'])) $contact_permit_carrier = $_GET['contact_permit_carrier'];
if(isset($_GET['archival_antenna_addition'])) $archival_antenna_addition = $_GET['archival_antenna_addition'];
if(isset($_GET['only_reasonable_location'])) $only_reasonable_location = $_GET['only_reasonable_location'];

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
if(!isset($sector_split_match)) $sector_split_match = 'false';
if(!isset($contact_permit_carrier)) $contact_permit_carrier = 'false';
if(!isset($archival_antenna_addition)) $archival_antenna_addition = 'false';
if(!isset($only_reasonable_location)) $only_reasonable_location = 'false';

//Failed to read tooltip (mobile? idk whatever)
if ("$contact_permit_carrier" == "true" && "$permit_suspected_carrier" == "true") {$contact_permit_carrier ='false';}

// Add evidence score up
if($permit_cellsite == 'true') $evidence_score = ($evidence_score) + (1);
if($permit_suspected_carrier == 'true') $evidence_score = ($evidence_score) + (20);
if($trails_match == 'true') $evidence_score = ($evidence_score) + (5);
if($other_carriers_dont == 'true') $evidence_score = ($evidence_score) + (3);
if($antennas_match_carrier == 'true') $evidence_score = ($evidence_score) + (3);
if($cellmapper_triangulation == 'true') $evidence_score = ($evidence_score) + (2);
if($image_evidence == 'true') $evidence_score = ($evidence_score) + (10);
if($verified_by_visit == 'true') $evidence_score = ($evidence_score) + (5);
if($sector_split_match == 'true') $evidence_score = ($evidence_score) + (4);
if($contact_permit_carrier == 'true') $evidence_score = ($evidence_score) + (10);
if($archival_antenna_addition == 'true') $evidence_score = ($evidence_score) + (3);
if($only_reasonable_location == 'true') $evidence_score = ($evidence_score) + (2);

?>