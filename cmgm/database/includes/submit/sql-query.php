<?php
$date_added = date("Y-m-d");
$conn = mysqli_connect($servername, $db_username, $password, $dbname);

// Create connection
$sql = "INSERT INTO database_db (`date_added`,`cellsite_type`,`LTE_1`,`LTE_2`,`LTE_3`,`LTE_4`,`LTE_5`,`LTE_6`,`nr_1`,`nr_2`,`pci_match`,
      `id_pattern_match`,`sector_match`,`other_user_map_primary`,`carrier`,`latitude`,`longitude`,`city`,`zip`,`state`,`address`,`bio`,`status`,`evidence_link`,
      `photo_link`,`attached_file_link`,`permit_score`,`trails_match`,`carriers_dont_trail_match`,`antennas_match_carrier`,
      `cellmapper_triangulation`,`image_evidence`,`verified_by_visit`,`sector_split_match`,`archival_antenna_addition`,
      `only_reasonable_location`,`alt_carriers_here`)
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
                        '".mysqli_real_escape_string($conn, $other_user_map_primary)."',
                        '".mysqli_real_escape_string($conn, $carrier)."',
                        '".mysqli_real_escape_string($conn, $latitude)."',
                        '".mysqli_real_escape_string($conn, $longitude)."',
                        '".mysqli_real_escape_string($conn, $city)."',
                        '".mysqli_real_escape_string($conn, $zip)."',
                        '".mysqli_real_escape_string($conn, $state)."',
                        '".mysqli_real_escape_string($conn, $address)."',
                        '".mysqli_real_escape_string($conn, $bio)."',
                        '".mysqli_real_escape_string($conn, $status)."',
                        '".mysqli_real_escape_string($conn, $evidence_link)."',
                        '".mysqli_real_escape_string($conn, $photo_link)."',
                        '".mysqli_real_escape_string($conn, $attached_file_link)."',
                        '".mysqli_real_escape_string($conn, $permit_score)."',
                        '".mysqli_real_escape_string($conn, $trails_match)."',
                        '".mysqli_real_escape_string($conn, $carriers_dont_trail_match)."',
                        '".mysqli_real_escape_string($conn, $antennas_match_carrier)."',
                        '".mysqli_real_escape_string($conn, $cellmapper_triangulation)."',
                        '".mysqli_real_escape_string($conn, $image_evidence)."',
                        '".mysqli_real_escape_string($conn, $verified_by_visit)."',
                        '".mysqli_real_escape_string($conn, $sector_split_match)."',
                        '".mysqli_real_escape_string($conn, $archival_antenna_addition)."',
                        '".mysqli_real_escape_string($conn, $only_reasonable_location)."',
                        '".mysqli_real_escape_string($conn, $alt_carriers_here)."');  ";

mysqli_query($conn, $sql);
mysqli_close($conn);

echo '<meta http-equiv="refresh" content="0;URL=../" /> ';
?>
