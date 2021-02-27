<!DOCTYPE html>
<head>
<?php include "../functions.php"; ?>
</head>
<?php
$id = $_GET['id'];
$list_of_vars = array('id', 'date_added', 'cellsite_type', 'LTE_1', 'LTE_2', 'LTE_3', 'LTE_4', 'LTE_5', 'LTE_6', 'NR_1', 'NR_2', 'pci_match',
'id_pattern_match', 'sector_match', 'carrier', 'latitude', 'longitude', 'city', 'zip', 'state', 'address', 'bio', 'tags', 'status',
'evidence_score', 'evidence_link', 'photo_link', 'attached_file_link', 'permit_score', 'trails_match', 'carriers_dont_trail_match',
'antennas_match_carrier', 'cellmapper_triangulation', 'image_evidence', 'verified_by_visit', 'sector_split_match', 'archival_antenna_addition',
'only_reasonable_location', 'alt_carriers_here', 'edit_history', 'edit_lock');

if (isset($latitude)) {
/// Database column names

// Prefix for the Build-A-Query
$sql_edit = "UPDATE database_db SET ";

// Infix for the Build-A-Query
foreach ($list_of_vars as $value) {
    if (!empty($_GET[$value])) {
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

$database_get_list = "id,date_added,cellsite_type,LTE_1,LTE_2,LTE_3,LTE_4,LTE_5,LTE_6,NR_1,NR_2,
pci_match,id_pattern_match,sector_match,carrier,latitude,longitude,city,zip,state,address,bio,tags,status,evidence_score,
evidence_link,photo_link,attached_file_link,permit_score,trails_match,carriers_dont_trail_match,antennas_match_carrier,cellmapper_triangulation,
image_evidence,verified_by_visit,sector_split_match,archival_antenna_addition,only_reasonable_location,alt_carriers_here,edit_history,edit_lock";

$sql = "SELECT $database_get_list FROM database_db WHERE id = $id;";
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value)
        $$key = $value;
}

$result->close(); $conn->close();
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
      <br><label for="alt_carriers_here">Multiple carriers?</label><input type="text" class="alt_carriers_here" name="alt_carriers_here" value="<?php echo $alt_carriers_here?>">
    </div>
    <div id="panel2">
      <label for="evidence_score">Evidence Score</label><input type="text" class="evidence_score" name="evidence_score" value="<?php echo $evidence_score?>">
      <br><label for="evidence_link">Evidence Link</label><input type="text" class="evidence_link" name="evidence_link" value="<?php echo $evidence_link?>">
      <br><label for="photo_link">Photo link</label><input type="text" class="photo_link" name="photo_link" value="<?php echo $photo_link?>">
      <br><label for="attached_file_link">Attached file link</label><input type="text" class="attached_file_link" name="attached_file_link" value="<?php echo $attached_file_link?>">
      <br><label for="permit_score">Permit Suspected Carrier</label><input type="text" class="permit_score" name="permit_score" value="<?php echo $permit_score?>">
      <br><label for="trails_match">Trails match</label><input type="text" class="trails_match" name="trails_match" value="<?php echo $trails_match?>">
      <br><label for="carriers_dont_trail_match">Other carriers don't</label><input type="text" class="carriers_dont_trail_match" name="carriers_dont_trail_match" value="<?php echo $carriers_dont_trail_match?>">
      <br><label for="antennas_match_carrier">Antenna configuartion match carrier</label><input type="text" class="antennas_match_carrier" name="antennas_match_carrier" value="<?php echo $antennas_match_carrier?>">
      <br><label for="cellmapper_triangulation">CellMapper Triangulates near the location</label><input type="text" class="cellmapper_triangulation" name="cellmapper_triangulation" value="<?php echo $cellmapper_triangulation?>">
      <br><label for="image_evidence">Picture with carrier name at the base station</label><input type="text" class="image_evidence" name="image_evidence" value="<?php echo $image_evidence?>">
      <br><label for="verified_by_visit">Verified by visiting the area</label><input type="text" class="verified_by_visit" name="verified_by_visit" value="<?php echo $verified_by_visit?>">
      <br><label for="sector_split_match">Sector split matches the suspected location</label><input type="text" class="sector_split_match" name="sector_split_match" value="<?php echo $sector_split_match?>">
      <br><label for="contact_permit_carrier">Contact info on permit match other permits with carrier name</label><input type="text" class="contact_permit_carrier" name="contact_permit_carrier" value="<?php echo $contact_permit_carrier?>">
      <br><label for="archival_antenna_addition">New antenna additions to the site match carrier's deployment date</label><input type="text" class="archival_antenna_addition" name="archival_antenna_addition" value="<?php echo $archival_antenna_addition?>">
      <br><label for="only_reasonable_location">It's the only site within the expected area of the site that makes sense</label><input type="text" class="only_reasonable_location" name="only_reasonable_location" value="<?php echo $only_reasonable_location?>">
    </div>
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
