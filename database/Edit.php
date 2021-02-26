<!DOCTYPE html>
<head>
<?php include "../functions.php"; ?>
</head>
<?php
$id = $_GET['id'];

if (isset($latitude)) {
// Database column names
$list = array('date_added', 'LTE_1', 'LTE_2', 'LTE_3', 'LTE_4', 'LTE_5', 'LTE_6', 'nr_1', 'nr_2', 'carrier', 'latitude', 'longitude', 'city', 'zip', 'state',
'address', 'bio', 'evidence_score', 'evidence_link', 'photo_link', 'attached_file_link', 'permit_cellsite', 'permit_suspected_carrier',
'trails_match', 'other_carriers_dont', ' antennas_match_carrier', 'cellmapper_triangulation', 'image_evidence', 'verified_by_visit',
'sector_split_match', 'contact_permit_carrier', 'archival_antenna_addition', 'only_reasonable_location', 'carrier_multiple');

// Prefix for the Build-A-Query
$sql_edit = "UPDATE database_db SET ";

// Infix for the Build-A-Query
foreach ($list as $value) {
    if (isset($_GET[$value])) {
      ${$value} = $_GET[$value];
      $sql_edit = $sql_edit . "$value = '".mysqli_real_escape_string($conn, ${$value})."', ";
    }
}
// Remove last comma for the Build-A-Query
$sql_edit = rtrim($sql_edit,', ');

// Add suffix for the Build-A-Query
$sql_edit = $sql_edit . " WHERE id = $id";

mysqli_query($conn, $sql_edit);
redir("Edit.php?id=" . $id . "","0");
}

$database_get_list = "id,date_added,LTE_1,LTE_2,LTE_3,LTE_4,LTE_5,carrier,latitude,longitude,city,zip,state,address,bio,evidence_score,
evidence_link,photo_link,attached_file_link,permit_cellsite,permit_suspected_carrier,trails_match,other_carriers_dont, antennas_match_carrier,
cellmapper_triangulation,image_evidence,verified_by_visit,sector_split_match,
contact_permit_carrier,archival_antenna_addition,only_reasonable_location,carrier_multiple";

$sql = "SELECT $database_get_list FROM database_db WHERE id = $id;";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
  $colCount = 1;
    if (isMobile()) echo "<tr>";

    foreach ($row as $field => $value) {
      $sepCount = ($colCount++);
                  switch ($sepCount) {
                    case 1:  $id = $value; break;
                    case 2:  $date_added = $value; break;
                    case 3:  $LTE_1 = $value; break;
                    case 4:  $LTE_2 = $value; break;
                    case 5:  $LTE_3 = $value; break;
                    case 6:  $LTE_4 = $value; break;
                    case 7:  $LTE_5 = $value; break;
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
<form action="Edit.php" id="form<?php echo $id; ?>" method="get">
  <div id="panel1">
      <label for="id">Row ID</label><input type="text" class="id" name="id" value="<?php echo $id?>">
      <br><label for="date_added">Date Added</label><input type="text" class="date_added" name="date_added" value="<?php echo $date_added?>">
      <br><label for="LTE_1">ID 1</label><input type="text" class="id" name="LTE_1" value="<?php echo $LTE_1?>">
      <br><label for="LTE_2">ID 2</label><input type="text" class="id" name="LTE_2" value="<?php echo $LTE_2?>">
      <br><label for="LTE_3">ID 3</label><input type="text" class="id" name="LTE_3" value="<?php echo $LTE_3?>">
      <br><label for="LTE_4">ID 4</label><input type="text" class="id" name="LTE_4" value="<?php echo $LTE_4?>">
      <br><label for="LTE_5">ID 5</label><input type="text" class="id" name="LTE_5" value="<?php echo $LTE_5?>">
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
