<?php
$date_added = date("Y-m-d");

if(empty($pci_match)) $pci_match = null;
if(empty($id_pattern_match)) $id_pattern_match = null;
if(empty($sector_split_match)) $sector_split_match = null;
if(empty($photo_link)) $photo_link = null;
if(empty($sector_match)) $sector_match = null;
if(empty($attached_file_link)) $attached_file_link = null;

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
$sql = "INSERT INTO database_db (`date_added`,`cellsite_type`,`lte_1`,`lte_2`,`lte_3`,`lte_4`,`lte_5`,`lte_6`,`nr_1`,`nr_2`,`pci_match`,
      `id_pattern_match`,`sector_match`,`carrier`,`latitude`,`longitude`,`city`,`zip`,`state`,`address`,`bio`,`status`,`evidence_score`,`evidence_link`,
      `photo_link`,`attached_file_link`,`permit_cellsite`,`permit_suspected_carrier`,`trails_match`,`other_carriers_dont`,`antennas_match_carrier`,
      `cellmapper_triangulation`,`image_evidence`,`verified_by_visit`,`sector_split_match`,`contact_permit_carrier`,`archival_antenna_addition`,
      `only_reasonable_location`,`carrier_multiple`)
                      VALUES (
                        '".mysqli_real_escape_string($conn, $date_added)."',
                        '".mysqli_real_escape_string($conn, $cellsite_type)."',
                        '".mysqli_real_escape_string($conn, $LTE_1)."',
                        '".mysqli_real_escape_string($conn, $LTE_2)."',
                        '".mysqli_real_escape_string($conn, $LTE_3)."',
                        '".mysqli_real_escape_string($conn, $LTE_4)."',
                        '".mysqli_real_escape_string($conn, $LTE_5)."',
                        '".mysqli_real_escape_string($conn, $LTE_6)."',
                        '".mysqli_real_escape_string($conn, $NR_1)."',
                        '".mysqli_real_escape_string($conn, $NR_2)."',
                        '".mysqli_real_escape_string($conn, $pci_match)."',
                        '".mysqli_real_escape_string($conn, $id_pattern_match)."',
                        '".mysqli_real_escape_string($conn, $sector_match)."',
                        '".mysqli_real_escape_string($conn, $carrier)."',
                        '".mysqli_real_escape_string($conn, $latitude)."',
                        '".mysqli_real_escape_string($conn, $longitude)."',
                        '".mysqli_real_escape_string($conn, $city)."',
                        '".mysqli_real_escape_string($conn, $zip)."',
                        '".mysqli_real_escape_string($conn, $state)."',
                        '".mysqli_real_escape_string($conn, $address)."',
                        '".mysqli_real_escape_string($conn, $bio)."',
                        '".mysqli_real_escape_string($conn, $status)."',
                        '".mysqli_real_escape_string($conn, $evidence_score)."',
                        '".mysqli_real_escape_string($conn, $evidence_link)."',
                        '".mysqli_real_escape_string($conn, $photo_link)."',
                        '".mysqli_real_escape_string($conn, $attached_file_link)."',
                        '".mysqli_real_escape_string($conn, $permit_cellsite)."',
                        '".mysqli_real_escape_string($conn, $permit_suspected_carrier)."',
                        '".mysqli_real_escape_string($conn, $trails_match)."',
                        '".mysqli_real_escape_string($conn, $other_carriers_dont)."',
                        '".mysqli_real_escape_string($conn, $antennas_match_carrier)."',
                        '".mysqli_real_escape_string($conn, $cellmapper_triangulation)."',
                        '".mysqli_real_escape_string($conn, $image_evidence)."',
                        '".mysqli_real_escape_string($conn, $verified_by_visit)."',
                        '".mysqli_real_escape_string($conn, $sector_split_match)."',
                        '".mysqli_real_escape_string($conn, $contact_permit_carrier)."',
                        '".mysqli_real_escape_string($conn, $archival_antenna_addition)."',
                        '".mysqli_real_escape_string($conn, $only_reasonable_location)."',
                        '".mysqli_real_escape_string($conn, $carrier_multiple)."');  ";

mysqli_query($conn, $sql);
mysqli_close($conn);

echo '<meta http-equiv="refresh" content="0;URL=../" /> ';
?>
