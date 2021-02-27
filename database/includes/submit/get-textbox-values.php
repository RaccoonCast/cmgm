<?php
$list = array('latitude', 'longitude', 'carrier', 'cellsite_type', 'LTE_1', 'LTE_2', 'LTE_3', 'LTE_4', 'LTE_5', 'LTE_6',
'NR_1', 'NR_2', 'pci_match','id_pattern_match', 'sector_match', 'bio', 'status', 'evidence_link', 'address', 'zip',
'city', 'state', 'alt_carriers_here', 'permit_score', 'trails_match', 'carriers_dont_trail_match',
'antennas_match_carrier', 'evidence_link', 'cellmapper_triangulation', 'image_evidence', 'verified_by_visit', 'sector_split_match',
`archival_antenna_addition`, `only_reasonable_location`);

// Infix for the Build-A-Query
foreach ($list as $value) {
      if(isset($_GET[$value])) ${$value} = $_GET[$value];
}
?>
