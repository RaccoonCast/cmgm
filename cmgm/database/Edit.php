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
verified_by_visit,sector_split_match,archival_antenna_addition,only_reasonable_location,alt_carriers_here,edit_history,edit_lock,street_view_url";

// todo:// add edit_history, edit_lock(IPs, name?)

$counter=0;
$sql = "SELECT $database_get_list FROM database_db WHERE id = $id;";
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value)
        $$key = $value;
        $counter++;
}

if ($counter==0) {
  $id = mysqli_fetch_array(mysqli_query($conn, "SELECT id FROM database_db WHERE LTE_1='$id' OR LTE_2='$id' OR LTE_3='$id' OR LTE_4='$id' OR LTE_5='$id' OR LTE_5='$id' OR LTE_6='$id' OR NR_1='$id' OR NR_2='$id'"))['id'];
  redir("Edit.php?id=$id","0");
  die();
}
$result->close(); $conn->close();
?>
<form action="Edit.php" autocomplete="off" id="form<?php echo $id; ?>" method="get">
  <div id="panel1">
    <input type="hidden" class="id" name="id" value="<?php echo $id?>">
    <input type="hidden" class="date_added" name="date_added" value="<?php echo $date_added?>">
    <label for="cellsite_type">Type of cellsite</label><br>
    <select class="status-custom-width" autocomplete="on" name="status" required>
    <option style="display:none" disabled selected="selected"></option>
    <option <?php if($status == "verified") echo 'selected="selected"';?>value="verified">Verified</option>
    <option <?php if($status == "unverified") echo 'selected="selected"';?>value="unverified">Unverified</option>
    <option <?php if($status == "Unmapped") echo 'selected="selected"';?>value="unmapped">Unmapped</option>
    <option <?php if($status == "special") echo 'selected="selected"';?>value="special">Special</option>
    <option <?php if($status == "weird") echo 'selected="selected"';?>value="weird">Weird</option>
    </select><select autocomplete="on" class="type-custom-width" name="cellsite_type" required>
    <option style="display:none" disabled selected="selected"></option>
    <option <?php if($cellsite_type == "macro") echo 'selected="selected"';?> value="macro">Macro tower</option>
    <option <?php if($cellsite_type == "micro") echo 'selected="selected"';?> value="micro">Micro tower</option>
    <option <?php if($cellsite_type == "conc_rooftop") echo 'selected="selected"';?> value="conc_rooftop">Concealed Rooftop</option>
    <option <?php if($cellsite_type == "unconc_rooftop") echo 'selected="selected"';?> value="unconc_rooftop">Unconcealed Rooftop</option>
    <option <?php if($cellsite_type == "monopalm") echo 'selected="selected"';?> value="monopalm">Monopalm</option>
    <option <?php if($cellsite_type == "monopine") echo 'selected="selected"';?> value="monopine">Monopine</option>
    <option <?php if($cellsite_type == "pole") echo 'selected="selected"';?> value="pole">Pole</option>
    <option <?php if($cellsite_type == "water_tower") echo 'selected="selected"';?> value="water_tower">Water tower</option>
    <option <?php if($cellsite_type == "guyed_tower") echo 'selected="selected"';?> value="guyed_tower">Guyed tower</option>
    <option <?php if($cellsite_type == "utility") echo 'selected="selected"';?> value="utility">Large power line structure</option>
    <option <?php if($cellsite_type == "clock") echo 'selected="selected"';?> value="clock">Clock tower</option>
    <option <?php if($cellsite_type == "disguised") echo 'selected="selected"';?> value="disguised">Disguised structure</option>
    <option <?php if($cellsite_type == "other") echo 'selected="selected"';?> value="other">Other</option>
    <option <?php if($cellsite_type == "unknown") echo 'selected="selected"';?> value="unknown">Unknown</option>
    </select><select class="carrier-custom-width" autocomplete="on" name="carrier">
    <option <?php if($carrier == "T-Mobile") echo 'selected="selected"';?> value="T-Mobile">T-Mobile</option>
    <option <?php if($carrier == "ATT") echo 'selected="selected"';?> value="ATT">AT&T</option>
    <option <?php if($carrier == "Verizon") echo 'selected="selected"';?> value="Verizon">Verizon</option>
    <option <?php if($carrier == "Sprint") echo 'selected="selected"';?> value="Sprint">Sprint</option>
    <option <?php if($carrier == "Unknown") echo 'selected="selected"';?> value="Unknown">Unknown</option>
    </select>
    <label class="lte-nr1" for="LTE_1">LTE/NR IDs</label><?php if ($isMobile =="true") { ?><br><?php } ?><input
    type="text" class="lte-nr2" id="LTE_1" value="<?php echo $LTE_1?>" placeholder="LTE_1" name="LTE_1"><input
    type="text" class="lte-nr2" id="LTE_2" value="<?php echo $LTE_2?>" placeholder="LTE_2" name="LTE_2"><input
    type="text" class="lte-nr2" id="LTE_3" value="<?php echo $LTE_3?>" placeholder="LTE_3" name="LTE_3"><input
    type="text" class="lte-nr2" id="LTE_4" value="<?php echo $LTE_4?>" placeholder="LTE_4" name="LTE_4"><input
    type="text" class="lte-nr2" id="LTE_5" value="<?php echo $LTE_5?>" placeholder="LTE_5" name="LTE_5"><input
    type="text" class="lte-nr2" id="LTE_6" value="<?php echo $LTE_6?>" placeholder="LTE_6" name="LTE_6"><input
    type="text" class="lte-nr2" id="NR_1" value="<?php echo $NR_1?>" placeholder="NR_1" name="NR_1"><input
    type="text" class="lte-nr2" id="NR_2" value="<?php echo $NR_2?>" placeholder="NR_2" name="NR_2">


    <br><label class="mutli-id-params1" for="pci_match">PCI match with all IDs</label><input type="text" class="mutli-id-params2 pci_match" name="pci_match" value="<?php echo $pci_match?>">
    <br><label class="mutli-id-params1" for="id_pattern_match">ID pattern with all IDs</label><input type="text" class="mutli-id-params2 id_pattern_match" name="id_pattern_match" value="<?php echo $id_pattern_match?>">
    <br><label class="mutli-id-params1" for="sector_match">Similiar sectors with all IDs</label><input type="text" class="mutli-id-params2 sector_match" name="sector_match" value="<?php echo $sector_match?>">
    <br><label class="mutli-id-params1" for="other_user_map_primary">Other user mapped primary</label><input type="text" class="mutli-id-params2 other_user_map_primary" name="other_user_map_primary" value="<?php echo $sector_match?>">

    <br><label class="latlong1" for="latitude">Latitude/Longitude</label><?php if ($isMobile =="true") { ?><br><?php } ?><input
    type="text" class="inline-block latlong2 latlongList" id="latitude" value="<?php echo $latitude?>" placeholder="Latitude" name="latitude"><input
    type="text" class="inline-block latlong2 latlongList" id="longitude" value="<?php echo $longitude?>" placeholder="Longitude" name="longitude">

    <label class="addr1" for="address">Address</label><?php if ($isMobile =="true") { ?><br><?php } ?><input
    type="text" class="inline-block addresslistA" id="address" value="<?php echo $address?>" placeholder="Address" name="address"><input
    type="text" class="inline-block addresslistB" id="city" value="<?php echo $city?>" placeholder="City" name="city"><input
    type="text" class="inline-block addresslistC" id="state" value="<?php echo $state?>" placeholder="State" name="state"><input
    type="text" class="inline-block addresslistD" id="zip" value="<?php echo $zip?>" placeholder="Zip" name="zip">
    <label class="ID street_view_url_label" for="street_view_url">Street view URL</label><input type="text" class="street_view_url_box" name="street_view_url" value="<?php echo $street_view_url?>">

    <br><label for="bio">Bio</label><br>
    <?php if ($isMobile !="true") { ?>
    <textarea rows="13" cols="120" class="bio" name="bio"><?php echo $bio?></textarea><br> <?php } else { ?>
    <textarea rows="6" cols="50" class="bio" name="bio"><?php echo $bio?></textarea><br> <?php } ?>

    <label class="tags1" for="tags">Tags</label><input type="text" class="tags2" name="tags" value="<?php echo $tags?>">
  </div>
  <div id="panel2">
    <label for="evidence_link">Evidence Link</label><input type="text" class="evidence_link" name="evidence_link" value="<?php echo $evidence_link?>">
    <label for="photo_link">Photo link</label><input type="text" class="photo_link" name="photo_link" value="<?php echo $photo_link?>">
    <label for="attached_file_link">Attached file link</label><input type="text" class="attached_file_link" name="attached_file_link" value="<?php echo $attached_file_link?>">
    <br><label class="ev_data1" for="permit_score">Permit Score</label><input type="text" class="ev_data2 permit_score" name="permit_score" value="<?php echo $permit_score?>">
    <br><label class="ev_data1" for="trails_match">Trails Match</label><input type="text" class="ev_data2 trails_match" name="trails_match" value="<?php echo $trails_match?>">
    <br><label class="ev_data1" for="carriers_dont_trail_match">Number of carriers CellMapper data rules out</label><input type="text" class="ev_data2 carriers_dont_trail_match" name="carriers_dont_trail_match" value="<?php echo $carriers_dont_trail_match?>">
    <br><label class="ev_data1" for="antennas_match_carrier"><abbr title="(1-100)&#013;0 being not at all&#013;100 being perfectly)">Antennas match carrier</abbr></label><input type="text" class="ev_data2 antennas_match_carrier" name="antennas_match_carrier" value="<?php echo $antennas_match_carrier?>">
    <br><label class="ev_data1" for="cellmapper_triangulation"><abbr title="(1-100)&#013;0 being not at all&#013;100 being perfectly)">CellMapper Triangulation</abbr></label><input type="text" class="ev_data2 cellmapper_triangulation" name="cellmapper_triangulation" value="<?php echo $cellmapper_triangulation?>">
    <br><label class="ev_data1" for="image_evidence"><abbr title="(1-100)&#013;0 being none&#013;100 being perfect)">Image evidence</abbr></label><input type="text" class="ev_data2 image_evidence" name="image_evidence" value="<?php echo $image_evidence?>">
    <br><label class="ev_data1" for="verified_by_visit"><abbr title="(1-100)&#013;0 being not at all&#013;100 very thorough)">Verified by visit</abbr></label><input type="text" class="ev_data2 verified_by_visit" name="verified_by_visit" value="<?php echo $verified_by_visit?>">
    <br><label class="ev_data1" for="sector_split_match"><abbr title="(1-100)&#013;0 being not at all&#013;100 being perfectly)">Sector split match</abbr></label><input type="text" class="ev_data2 sector_split_match" name="sector_split_match" value="<?php echo $sector_split_match?>">
    <br><label class="ev_data1" for="archival_antenna_addition">Number of visinline-blockile antenna modifications</abbr></label><input type="text" class="ev_data2 archival_antenna_addition" name="archival_antenna_addition" value="<?php echo $archival_antenna_addition?>">
    <br><label class="ev_data1" for="only_reasonable_location"><abbr title="(1-100)&#013;0 being not at all&#013;100 being perfectly)">Only reasonable location</abbr></label><input type="text" class="ev_data2 only_reasonable_location" name="only_reasonable_location" value="<?php echo $only_reasonable_location?>">
    <br><label class="ev_data1" for="alt_carriers_here">Number of other carriers here</label><input type="text" class="ev_data2 alt_carriers_here" name="alt_carriers_here" value="<?php echo $alt_carriers_here?>">
    </div>
</tbody>
</table>
<input style="margin-bottom: 1.5cm" type="submit" class="submitbutton" value="Submit">
</form>
<?php include "includes/footer.php"; ?>
</body>
</html>
