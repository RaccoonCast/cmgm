<?php
$permit_cellsite = $_GET['permit_cellsite'];
$permit_suspected_carrier = $_GET['permit_suspected_carrier'];
$trails_match = $_GET['trails_match'];
$other_carriers_dont = $_GET['other_carriers_dont'];
$antennnas_match_carrier = $_GET['antennnas_match_carrier'];
$evidence_text = $_GET['evidence_text'];

$evidence_score = 0;

if($permit_cellsite == 'true') $evidence_score = ($evidence_score) + (1);
if($permit_suspected_carrier == 'true') $evidence_score = ($evidence_score) + (20);
if($trails_match == 'true') $evidence_score = ($evidence_score) + (5);
if($other_carriers_dont == 'true') $evidence_score = ($evidence_score) + (3);
if($antennnas_match_carrier == 'true') $evidence_score = ($evidence_score) + (1);

?>
