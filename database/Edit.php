<!DOCTYPE html>
<head>
<?php include "../functions.php"; ?>
</head>
<?php
$conn = mysqli_connect($servername, $username, $password, $dbname);
$row_id = $_GET['row_id'];

if (isset($_GET['row_id'])) $row_id = $_GET['row_id'];
if (isset($_GET['date_added'])) $date_added = $_GET['date_added'];
if (isset($_GET['id_1'])) $id_1 = $_GET['id_1'];
if (isset($_GET['id_2'])) $id_2 = $_GET['id_2'];
if (isset($_GET['id_3'])) $id_3 = $_GET['id_3'];
if (isset($_GET['id_4'])) $id_4 = $_GET['id_4'];
if (isset($_GET['id_5'])) $id_5 = $_GET['id_5'];
if (isset($_GET['carrier'])) $carrier = $_GET['carrier'];
if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];
if (isset($_GET['city'])) $city = $_GET['city'];
if (isset($_GET['zip'])) $zip = $_GET['zip'];
if (isset($_GET['state'])) $state = $_GET['state'];
if (isset($_GET['address'])) $address = $_GET['address'];
if (isset($_GET['bio'])) $bio = $_GET['bio'];
if (isset($_GET['evidence_score'])) $evidence_score = $_GET['evidence_score'];
if (isset($_GET['evidence_link'])) $evidence_link = $_GET['evidence_link'];
if (isset($_GET['photo_link'])) $photo_link = $_GET['photo_link'];
if (isset($_GET['attached_file_link'])) $attached_file_link = $_GET['attached_file_link'];
if (isset($_GET['permit_cellsite'])) $permit_cellsite = $_GET['permit_cellsite'];
if (isset($_GET['permit_suspected_carrier'])) $permit_suspected_carrier = $_GET['permit_suspected_carrier'];
if (isset($_GET['trails_match'])) $trails_match = $_GET['trails_match'];
if (isset($_GET['other_carriers_dont'])) $other_carriers_dont = $_GET['other_carriers_dont'];
if (isset($_GET['antennas_match_carrier'])) $antennas_match_carrier = $_GET['antennas_match_carrier'];
if (isset($_GET['cellmapper_triangulation'])) $cellmapper_triangulation = $_GET['cellmapper_triangulation'];
if (isset($_GET['image_evidence'])) $image_evidence = $_GET['image_evidence'];
if (isset($_GET['verified_by_visit'])) $verified_by_visit = $_GET['verified_by_visit'];
if (isset($_GET['sector_split_match'])) $sector_split_match = $_GET['sector_split_match'];
if (isset($_GET['contact_permit_carrier'])) $contact_permit_carrier = $_GET['contact_permit_carrier'];
if (isset($_GET['archival_antenna_addition'])) $archival_antenna_addition = $_GET['archival_antenna_addition'];
if (isset($_GET['only_reasonable_location'])) $only_reasonable_location = $_GET['only_reasonable_location'];
if (isset($_GET['carrier_multiple'])) {
$carrier_multiple  = $_GET['carrier_multiple'];
$sql_edit = "UPDATE `cmgm`.`database_db`
SET `row_id` = '".mysqli_real_escape_string($conn, $row_id)."',
`date_added` = '".mysqli_real_escape_string($conn, $date_added)."',
`id_1` = '".mysqli_real_escape_string($conn, $id_1)."',
`id_2` = '".mysqli_real_escape_string($conn, $id_2)."',
`id_3` = '".mysqli_real_escape_string($conn, $id_3)."',
`id_4` = '".mysqli_real_escape_string($conn, $id_4)."',
`id_5` = '".mysqli_real_escape_string($conn, $id_5)."',
`carrier` = '".mysqli_real_escape_string($conn, $carrier)."',
`latitude` = '".mysqli_real_escape_string($conn, $latitude)."',
`longitude` = '".mysqli_real_escape_string($conn, $longitude)."',
`city` = '".mysqli_real_escape_string($conn, $city)."',
`zip` = '".mysqli_real_escape_string($conn, $zip)."',
`state` = '".mysqli_real_escape_string($conn, $state)."',
`address` = '".mysqli_real_escape_string($conn, $address)."',
`bio` = '".mysqli_real_escape_string($conn, $bio)."',
`evidence_score` = '".mysqli_real_escape_string($conn, $evidence_score)."',
`evidence_link` = '".mysqli_real_escape_string($conn, $evidence_link)."',
`photo_link` = '".mysqli_real_escape_string($conn, $photo_link)."',
`attached_file_link` = '".mysqli_real_escape_string($conn, $attached_file_link)."',
`permit_cellsite` = '".mysqli_real_escape_string($conn, $permit_cellsite)."',
`permit_suspected_carrier` = '".mysqli_real_escape_string($conn, $permit_suspected_carrier)."',
`trails_match` = '".mysqli_real_escape_string($conn, $trails_match)."',
`other_carriers_dont` = '".mysqli_real_escape_string($conn, $other_carriers_dont)."',
`antennas_match_carrier` = '".mysqli_real_escape_string($conn, $antennas_match_carrier)."',
`cellmapper_triangulation` = '".mysqli_real_escape_string($conn, $cellmapper_triangulation)."',
`image_evidence` = '".mysqli_real_escape_string($conn, $image_evidence)."',
`verified_by_visit` = '".mysqli_real_escape_string($conn, $verified_by_visit)."',
`sector_split_match` = '".mysqli_real_escape_string($conn, $sector_split_match)."',
`contact_permit_carrier` = '".mysqli_real_escape_string($conn, $contact_permit_carrier)."',
`archival_antenna_addition` = '".mysqli_real_escape_string($conn, $archival_antenna_addition)."',
`only_reasonable_location` = '".mysqli_real_escape_string($conn, $only_reasonable_location)."',
`carrier_multiple` = '".mysqli_real_escape_string($conn, $carrier_multiple)."'
WHERE row_id = $row_id";
mysqli_query($conn, $sql_edit);
}

$sql = "SELECT * FROM database_db WHERE row_id = $row_id;";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
  $colCount = 1;
    if (isMobile()) echo "<tr>";

    foreach ($row as $field => $value) {
      $sepCount = ($colCount++);
                  switch ($sepCount) {
                    case 1:  $row_id = $value; break;
                    case 2:  $date_added = $value; break;
                    case 3:  $id_1 = $value; break;
                    case 4:  $id_2 = $value; break;
                    case 5:  $id_3 = $value; break;
                    case 6:  $id_4 = $value; break;
                    case 7:  $id_5 = $value; break;
                    case 8:  $carrier = $value; break;
                    case 9:  $latitude = $value; break;
                    case 10:  $longitude = $value; break;
                    case 11:  $city = $value; break;
                    case 12:  $zip = $value; break;
                    case 13:  $state = $value; break;
                    case 14:  $address = $value; break;
                    case 15:  $bio = $value; break;
                    case 16:  $evidence_score = $value; break;
                    case 17:  $evidence_link = $value; break;
                    case 18:  $photo_link = $value; break;
                    case 19:  $attached_file_link = $value; break;
                    case 20:  $permit_cellsite = $value; break;
                    case 21:  $permit_suspected_carrier = $value; break;
                    case 22:  $trails_match = $value; break;
                    case 23:  $other_carriers_dont = $value; break;
                    case 24:  $antennas_match_carrier = $value; break;
                    case 25:  $cellmapper_triangulation = $value; break;
                    case 26:  $image_evidence = $value; break;
                    case 27:  $verified_by_visit = $value; break;
                    case 28:  $sector_split_match = $value; break;
                    case 29:  $contact_permit_carrier = $value; break;
                    case 30:  $archival_antenna_addition = $value; break;
                    case 31:  $only_reasonable_location = $value; break;
                    case 32:  $carrier_multiple = $value;
?>
<form action="Edit.php" id="form<?php echo $row_id; ?>" method="get">
  <div id="panel1">
      <label for="row_id">Row ID</label><input type="text" class="row_id" name="row_id" value="<?php echo $row_id?>">
      <br><label for="date_added">Date Added</label><input type="text" class="date_added" name="date_added" value="<?php echo $date_added?>">
      <br><label for="id_1">ID 1</label><input type="text" class="id" name="id_1" value="<?php echo $id_1?>">
      <br><label for="id_2">ID 2</label><input type="text" class="id" name="id_2" value="<?php echo $id_2?>">
      <br><label for="id_3">ID 3</label><input type="text" class="id" name="id_3" value="<?php echo $id_3?>">
      <br><label for="id_4">ID 4</label><input type="text" class="id" name="id_4" value="<?php echo $id_4?>">
      <br><label for="id_5">ID 5</label><input type="text" class="id" name="id_5" value="<?php echo $id_5?>">
      <br><label for="carrier">Carrier</label><input type="text" class="carrier" name="carrier" value="<?php echo $carrier?>">
      <br><label for="latitude">Latitude</label><input type="text" class="latitude" name="latitude" value="<?php echo $latitude?>">
      <br><label for="longitude">Longitude</label><input type="text" class="longitude" name="longitude" value="<?php echo $longitude?>">
      <br><label for="city">City</label><input type="text" class="city" name="city" value="<?php echo $city?>">
      <br><label for="zip">Zip</label><input type="text" class="zip" name="zip" value="<?php echo $zip?>">
      <br><label for="state">State</label><input type="text" class="state" name="state" value="<?php echo $state?>">
      <br><label for="address">Address</label><input type="text" class="address" name="address" value="<?php echo $address?>">
      <br><label for="bio">Bio</label><input type="text" class="bio" name="bio" value="<?php echo $bio?>">
      <br><label for="carrier_multiple">Multiple carriers?</label><input type="text" class="carrier_multiple" name="carrier_multiple" value="<?php echo $carrier_multiple?>">
    </div>
    <div id="panel2">
      <label for="evidence_score">Evidence Score</label><input type="text" class="evidence_score" name="evidence_score" value="<?php echo $evidence_score?>">
      <br><label for="evidence_link">Evidence Link</label><input type="text" class="evidence_link" name="evidence_link" value="<?php echo $evidence_link?>">
      <br><label for="photo_link">Photo link</label><input type="text" class="photo_link" name="photo_link" value="<?php echo $photo_link?>">
      <br><label for="attached_file_link">Attached file link</label><input type="text" class="attached_file_link" name="attached_file_link" value="<?php echo $attached_file_link?>">
      <br><label for="permit_cellsite">Permit Cellsite</label><input type="text" class="permit_cellsite" name="permit_cellsite" value="<?php echo $permit_cellsite?>">
      <br><label for="permit_suspected_carrier">Permit Suspected Carrier</label><input type="text" class="permit_suspected_carrier" name="permit_suspected_carrier" value="<?php echo $permit_suspected_carrier?>">
      <br><label for="trails_match">Trails match</label><input type="text" class="trails_match" name="trails_match" value="<?php echo $trails_match?>">
      <br><label for="other_carriers_dont">Other carriers don't</label><input type="text" class="other_carriers_dont" name="other_carriers_dont" value="<?php echo $other_carriers_dont?>">
      <br><label for="antennas_match_carrier">Antenna configuartion match carrier</label><input type="text" class="antennas_match_carrier" name="antennas_match_carrier" value="<?php echo $antennas_match_carrier?>">
      <br><label for="cellmapper_triangulation">CellMapper Triangulates near the location</label><input type="text" class="cellmapper_triangulation" name="cellmapper_triangulation" value="<?php echo $cellmapper_triangulation?>">
      <br><label for="image_evidence">Picture with carrier name at the base station</label><input type="text" class="image_evidence" name="image_evidence" value="<?php echo $image_evidence?>">
      <br><label for="verified_by_visit">Verified by visiting the area</label><input type="text" class="verified_by_visit" name="verified_by_visit" value="<?php echo $verified_by_visit?>">
      <br><label for="sector_split_match">Sector split matches the suspected location</label><input type="text" class="sector_split_match" name="sector_split_match" value="<?php echo $sector_split_match?>">
      <br><label for="contact_permit_carrier">Contact info on permit match other permits with carrier name</label><input type="text" class="contact_permit_carrier" name="contact_permit_carrier" value="<?php echo $contact_permit_carrier?>">
      <br><label for="archival_antenna_addition">New antenna additions to the site match carrier's deployment date</label><input type="text" class="archival_antenna_addition" name="archival_antenna_addition" value="<?php echo $archival_antenna_addition?>">
      <br><label for="only_reasonable_location">It's the only site within the expected area of the site that makes sense</label><input type="text" class="only_reasonable_location" name="only_reasonable_location" value="<?php echo $only_reasonable_location?>">
    </div> <?php
    }
    }
                                break;


            }
?>
</tbody>
</table>
<br>
<div id="panel3"><br>
  <div class="container">
  <div class="child">    <input type="submit" class="submitbutton" value="Submit"></div>
</div>
<div>
</form>
</body>
</html>
