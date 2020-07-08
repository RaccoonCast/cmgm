<?php
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

$sql = "INSERT INTO database_db (`id`,`carrier`,`latitude`,`longitude`,`city`,`zip`,`state`,`address`,`bio`,`evidence_score`,`evidence_link`,`permit_cellsite`,`permit_suspected_carrier`,
      `trails_match`,`other_carriers_dont`,`antennas_match_carrier`,`cellmapper_triangulation`,`image_evidence`,`verified_by_visit`,`carrier_multiple`)
                      VALUES (
                        '".mysqli_real_escape_string($conn, $id)."',
                        '".mysqli_real_escape_string($conn, $carrier)."',
                        '".mysqli_real_escape_string($conn, $latitude)."',
                        '".mysqli_real_escape_string($conn, $longitude)."',
                        '".mysqli_real_escape_string($conn, $city)."',
                        '".mysqli_real_escape_string($conn, $zip)."',
                        '".mysqli_real_escape_string($conn, $state)."',
                        '".mysqli_real_escape_string($conn, $address)."',
                        '".mysqli_real_escape_string($conn, $bio)."',
                        '".mysqli_real_escape_string($conn, $evidence_score)."',
                        '".mysqli_real_escape_string($conn, $evidence_text)."',
                        '".mysqli_real_escape_string($conn, $permit_cellsite)."',
                        '".mysqli_real_escape_string($conn, $permit_suspected_carrier)."',
                        '".mysqli_real_escape_string($conn, $trails_match)."',
                        '".mysqli_real_escape_string($conn, $other_carriers_dont)."',
                        '".mysqli_real_escape_string($conn, $antennas_match_carrier)."',
                        '".mysqli_real_escape_string($conn, $cellmapper_triangulation)."',
                        '".mysqli_real_escape_string($conn, $image_evidence)."',
                        '".mysqli_real_escape_string($conn, $verified_by_visit)."',
                        '".mysqli_real_escape_string($conn, $carrier_multiple)."');  ";

if($dont_create == 'false') {
  mysqli_query($conn, $sql);
  mysqli_close($conn);
  }

//echo $sql;

echo '<meta http-equiv="refresh" content="2;URL=../" /> ';
?>
