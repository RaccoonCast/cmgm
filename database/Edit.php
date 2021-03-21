<!DOCTYPE html>
<head>
<?php include "../functions.php"; ?>
</head>
<?php
$id = $_GET['id'];
$list_of_vars = array('id', 'date_added', 'cellsite_type', 'LTE_1', 'LTE_2', 'LTE_3', 'LTE_4', 'LTE_5', 'LTE_6', 'NR_1', 'NR_2', 'pci_match',
'id_pattern_match', 'sector_match', 'carrier', 'latitude', 'longitude', 'city', 'zip', 'state', 'address', 'bio', 'tags', 'status',
'evidence_link', 'photo_link', 'attached_file_link', 'permit_score', 'trails_match', 'carriers_dont_trail_match','antennas_match_carrier',
'cellmapper_triangulation', 'image_evidence', 'verified_by_visit', 'sector_split_match', 'archival_antenna_addition', 'only_reasonable_location',
'alt_carriers_here');

if (isset($latitude)) {
/// Database column names

// Prefix for the Build-A-Query
$sql_edit = "UPDATE database_db SET ";

// Infix for the Build-A-Query
foreach ($list_of_vars as $value) {
      ${$value} = $_GET[$value];
      $sql_edit = $sql_edit . "$value = '".mysqli_real_escape_string($conn, ${$value})."', ";
    }
// Remove last comma for the Build-A-Query
$sql_edit = rtrim($sql_edit,', ');

// Add suffix for the Build-A-Query
$sql_edit = $sql_edit . " WHERE id = $id";

mysqli_query($conn, $sql_edit);
redir("Edit.php?id=" . $id . "","0");
}

$database_get_list = "id,date_added,cellsite_type,LTE_1,LTE_2,LTE_3,LTE_4,LTE_5,LTE_6,NR_1,NR_2,
pci_match,id_pattern_match,sector_match,other_user_map_primary,carrier,latitude,longitude,city,zip,state,address,bio,tags,status,evidence_link,photo_link,
attached_file_link,permit_score,trails_match,carriers_dont_trail_match,antennas_match_carrier,cellmapper_triangulation,image_evidence,
verified_by_visit,sector_split_match,archival_antenna_addition,only_reasonable_location,alt_carriers_here,edit_history,edit_lock";

// todo:// add edit_history, edit_lock(IPs, name?)

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
    <input type="hidden" class="id" name="id" value="<?php echo $id?>">
    <label for="date_added">Creation Date</label><input type="text" class="date_added" name="date_added" value="<?php echo $date_added?>">
    <br><label for="cellsite_type">Type of cellsite</label>
    <input type="text" class="cellsite_type" name="cellsite_type" value="<?php echo $cellsite_type?>">
    <br><label for="LTE_1">eNB ID (1)</label><input type="text" class="LTE_1" name="LTE_1" value="<?php echo $LTE_1?>">
    <br><label for="LTE_2">eNB ID (2)</label><input type="text" class="LTE_2" name="LTE_2" value="<?php echo $LTE_2?>">
    <br><label for="LTE_3">eNB ID (3)</label><input type="text" class="LTE_3" name="LTE_3" value="<?php echo $LTE_3?>">
    <br><label for="LTE_4">eNB ID (4)</label><input type="text" class="LTE_4" name="LTE_4" value="<?php echo $LTE_4?>">
    <br><label for="LTE_5">eNB ID (5)</label><input type="text" class="LTE_5" name="LTE_5" value="<?php echo $LTE_5?>">
    <br><label for="LTE_6">eNB ID (6)</label><input type="text" class="LTE_6" name="LTE_6" value="<?php echo $LTE_6?>">
    <br><label for="NR_1">gNB ID (1)</label><input type="text" class="NR_1" name="NR_1" value="<?php echo $NR_1?>">
    <br><label for="NR_2">gNB ID (2)</label><input type="text" class="NR_2" name="NR_2" value="<?php echo $NR_2?>">
    <br><label for="pci_match">PCI match with all IDs</label><input type="text" class="pci_match" name="pci_match" value="<?php echo $pci_match?>">
    <br><label for="id_pattern_match">ID pattern with all IDs</label><input type="text" class="id_pattern_match" name="id_pattern_match" value="<?php echo $id_pattern_match?>">
    <br><label for="sector_match">Similiar sectors with all IDs</label><input type="text" class="sector_match" name="sector_match" value="<?php echo $sector_match?>">
    <br><label for="carrier">Carrier</label><input type="text" class="carrier" name="carrier" value="<?php echo $carrier?>">
    <br><label for="latitude">Latitude</label><input type="text" class="latitude" name="latitude" value="<?php echo $latitude?>">
    <br><label for="longitude">Longitude</label><input type="text" class="longitude" name="longitude" value="<?php echo $longitude?>">
    <br><label for="city">City</label><input type="text" class="city" name="city" value="<?php echo $city?>">
    <br><label for="zip">Zip</label><input type="text" class="zip" name="zip" value="<?php echo $zip?>">
    <br><label for="state">State</label><input type="text" class="state" name="state" value="<?php echo $state?>">
  </div>
  <div id="panel2">
    <label for="address">Address</label><input type="text" class="address" name="address" value="<?php echo $address?>">
    <br><label for="bio">Bio</label><input type="text" class="bio" name="bio" value="<?php echo $bio?>">
    <br><label for="tags">Tags</label><input type="text" class="tags" name="tags" value="<?php echo $tags?>">
    <br><label for="status">Status</label><input type="text" class="status" name="status" value="<?php echo $status?>">
    <br><label for="evidence_link">Evidence Link</label><input type="text" class="evidence_link" name="evidence_link" value="<?php echo $evidence_link?>">
    <br><label for="photo_link">Photo link</label><input type="text" class="photo_link" name="photo_link" value="<?php echo $photo_link?>">
    <br><label for="attached_file_link">Attached file link</label><input type="text" class="attached_file_link" name="attached_file_link" value="<?php echo $attached_file_link?>">
    <br><label for="permit_score">Permit Score</label><input type="text" class="permit_score" name="permit_score" value="<?php echo $permit_score?>">
    <br><label for="trails_match">Trails Match</label><input type="text" class="trails_match" name="trails_match" value="<?php echo $trails_match?>">
    <br><label for="carriers_dont_trail_match">Number of carriers without trails that match</label><input type="text" class="carriers_dont_trail_match" name="carriers_dont_trail_match" value="<?php echo $carriers_dont_trail_match?>">
    <br><label for="antennas_match_carrier"><abbr title="(1-100)&#013;0 being not at all&#013;100 being perfectly)">Antennas match carrier</abbr></label><input type="text" class="antennas_match_carrier" name="antennas_match_carrier" value="<?php echo $antennas_match_carrier?>">
    <br><label for="cellmapper_triangulation"><abbr title="(1-100)&#013;0 being not at all&#013;100 being perfectly)">CellMapper Triangulation</abbr></label><input type="text" class="cellmapper_triangulation" name="cellmapper_triangulation" value="<?php echo $cellmapper_triangulation?>">
    <br><label for="image_evidence"><abbr title="(1-100)&#013;0 being none&#013;100 being perfect)">Image evidence</abbr></label><input type="text" class="image_evidence" name="image_evidence" value="<?php echo $image_evidence?>">
    <br><label for="verified_by_visit"><abbr title="(1-100)&#013;0 being not at all&#013;100 very thorough)">Verified by visit</abbr></label><input type="text" class="verified_by_visit" name="verified_by_visit" value="<?php echo $verified_by_visit?>">
    <br><label for="sector_split_match"><abbr title="(1-100)&#013;0 being not at all&#013;100 being perfectly)">Sector split match</abbr></label><input type="text" class="sector_split_match" name="sector_split_match" value="<?php echo $sector_split_match?>">
    <br><label for="archival_antenna_addition">Number of recognizable antenna modifications that can be seen.</abbr></label><input type="text" class="archival_antenna_addition" name="archival_antenna_addition" value="<?php echo $archival_antenna_addition?>">
    <br><label for="only_reasonable_location"><abbr title="(1-100)&#013;0 being not at all&#013;100 being perfectly)">Only reasonable location</abbr></label><input type="text" class="only_reasonable_location" name="only_reasonable_location" value="<?php echo $only_reasonable_location?>">
    <br><label for="alt_carriers_here">Number of carriers that also have equipment here</label><input type="text" class="alt_carriers_here" name="alt_carriers_here" value="<?php echo $alt_carriers_here?>">
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
