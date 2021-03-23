<?php
// SQL Login Information
include "../includes/functions/basic-functions.php";
if (isset($_GET['id'])) {
  $id = $_GET['id'];
} else {
  echo "The ID variable has not been specified."
}

// Database column names
$list = array('id', 'date_added', 'cellsite_type', 'LTE_1', 'LTE_2', 'LTE_3', 'LTE_4', 'LTE_5', 'LTE_6', 'NR_1', 'NR_2', 'pci_match',
'id_pattern_match', 'sector_match', 'carrier', 'latitude', 'longitude', 'city', 'zip', 'state', 'address', 'bio', 'tags', 'status',
'evidence_score', 'evidence_link', 'photo_link', 'attached_file_link', 'permit_score', 'trails_match', 'carriers_dont_trail_match',
'antennas_match_carrier', 'cellmapper_triangulation', 'image_evidence', 'verified_by_visit', 'sector_split_match', 'archival_antenna_addition',
'only_reasonable_location', 'alt_carriers_here', 'edit_history', 'edit_lock');

// Prefix for the Build-A-Query
$sql_edit = "UPDATE database_db SET ";

// Infix for the Build-A-Query
foreach ($list as $value) {
    if (!empty($_GET[$value])) {
      ${$value} = $_GET[$value];
      $sql_edit = $sql_edit . "$value = '".mysqli_real_escape_string($conn, ${$value})."', ";
    }
}
// Remove last comma for the Build-A-Query
$sql_edit = rtrim($sql_edit,', ');

// Add suffix for the Build-A-Query
$sql_edit = $sql_edit . " WHERE id = $id";

// Echo the SQL query (not execute)
mysqli_query($conn, $sql_edit);
redir("Edit.php?id=" . $id . "","0");
?>
