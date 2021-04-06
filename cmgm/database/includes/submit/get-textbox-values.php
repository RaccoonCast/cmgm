<?php
$list = array('latitude', 'longitude', 'carrier', 'cellsite_type', 'LTE_1', 'LTE_2', 'LTE_3', 'LTE_4', 'LTE_5', 'LTE_6',
'NR_1', 'NR_2', 'pci_match','id_pattern_match', 'sector_match', 'other_user_map_primary', 'bio', 'status', 'evidence_link', 'address', 'zip', 'city',
'state', 'alt_carriers_here', 'permit_score', 'trails_match', 'archival_antenna_addition', 'only_reasonable_location', 'carriers_dont_trail_match',
'antennas_match_carrier', 'evidence_link', 'cellmapper_triangulation', 'image_evidence', 'photo_link_a', 'photo_link_b', 'photo_link_c', 'photo_link_d', 'attached_file_link', 'verified_by_visit',
'sector_split_match', 'tags', 'street_view_url');

// Infix for the Build-A-Query
foreach ($list as $value) {
      if(isset($_GET[$value])) {
        ${$value} = $_POST[$value];
      } else {
        ${$value} = null;
      }
      // echo "$value" . ": " . ${$value};
      // echo "<br>";
}
// Remove spaces
$LTE_1 = str_replace(' ', '', $LTE_1);
$LTE_2 = str_replace(' ', '', $LTE_2);
$LTE_3 = str_replace(' ', '', $LTE_3);
$LTE_4 = str_replace(' ', '', $LTE_4);
$LTE_5 = str_replace(' ', '', $LTE_6);
$NR_1 = str_replace(' ', '', $NR_1);
$NR_2 = str_replace(' ', '', $NR_2);

?>
