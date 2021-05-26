<?php
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache'); ?>
<!DOCTYPE html>
<head>
<?php
$titleOverride = "true";
include "../functions.php"; ?>
<?php
if (isset($_GET['id_search'])) $id = $_GET['id_search'];
if (isset($_GET['back'])) $back_num = $_GET['back'];
if (isset($_GET['next'])) $next_num = $_GET['next'];
if (isset($_GET['id'])) $id = $_GET['id'];
if (isset($_POST['id'])) $id = $_POST['id'];
if (empty($id)) {
  ?>
  <form action="Edit.php" autocomplete="off" method="get">
  <input type="text" class="id_input" value="" placeholder="Any LTE/NR ID" name="id"><input
  type="submit" class="small-submitbutton" value="Submit">
  </form>
  <?php
  die();
}
if (isset($_POST['id'])) {
/// Database column names
$list_of_vars = array('id', 'date_added', 'cellsite_type', 'concealed', 'LTE_1', 'LTE_2', 'LTE_3', 'LTE_4', 'LTE_5', 'LTE_6', 'NR_1', 'NR_2', 'pci_match',
'id_pattern_match', 'sector_match', 'other_user_map_primary', 'carrier', 'latitude', 'longitude', 'city', 'zip', 'state', 'address', 'bio', 'tags', 'status',
'evidence_a', 'evidence_b', 'evidence_c', 'photo_a', 'photo_b', 'photo_c', 'photo_d', 'photo_e', 'photo_f','attached_a', 'attached_b', 'attached_c',
'permit_score', 'trails_match', 'carriers_dont_trail_match','antennas_match_carrier','cellmapper_triangulation',
'image_evidence', 'verified_by_visit', 'sector_split_match', 'archival_antenna_addition', 'only_reasonable_location',
'alt_carriers_here','street_view_url');

// Prefix for the Build-A-Query
$sql_edit = "UPDATE database_db SET ";

// Infix for the Build-A-Query
foreach ($list_of_vars as $value) {
      ${$value} = $_POST[$value];
      $sql_edit = $sql_edit . "$value = '".mysqli_real_escape_string($conn, ${$value})."', ";
    }
// Remove last comma for the Build-A-Query
$sql_edit = rtrim($sql_edit,', ');

// Add suffix for the Build-A-Query
$sql_edit = $sql_edit . " WHERE id = $id";

mysqli_query($conn, $sql_edit);
}

$database_get_list = "id,date_added,cellsite_type,concealed,LTE_1,LTE_2,LTE_3,LTE_4,LTE_5,LTE_6,NR_1,NR_2,
pci_match,id_pattern_match,sector_match,other_user_map_primary,carrier,latitude,longitude,city,zip,state,address,bio,tags,status,evidence_a,evidence_b,evidence_c,photo_a,
photo_b,photo_c,photo_d,photo_e,photo_f,attached_a,attached_b,attached_c,permit_score,trails_match,carriers_dont_trail_match,antennas_match_carrier,cellmapper_triangulation,
image_evidence,verified_by_visit,sector_split_match,archival_antenna_addition,only_reasonable_location,alt_carriers_here,edit_history,edit_lock,street_view_url";

// todo:// add edit_history, edit_lock(IPs, name?)

$counter=0;
$sql = "SELECT $database_get_list FROM database_db WHERE id = $id;";
// echo $sql;
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value)
        $$key = $value;
        $counter++;
}

// if (!empty($cellsite_type)) {
//   $id++;
//   redir("Edit.php?id=$id","0");
//   die();
// }

// Not found? Ok... let's try some things.

if ($counter==0 OR isset($_GET['id_search'])) {
  if (@$next_num < 15 & isset($next_num)) {
    $id++; $next_num++;
    redir("Edit.php?id=$id&next=$next_num","0");
    die();
  }
  if (@$back_num < 15 & isset($back_num)) {
    $id--; $back_num++;
    redir("Edit.php?id=$id&back=$back_num","0");
    die();
  }
  $new_id = @mysqli_fetch_array(mysqli_query($conn, "SELECT id FROM database_db WHERE LTE_1='$id' OR LTE_2='$id' OR LTE_3='$id' OR LTE_4='$id' OR LTE_5='$id' OR LTE_5='$id' OR LTE_6='$id' OR NR_1='$id' OR NR_2='$id'"))['id'];
  if (empty($new_id)) {
    echo "No results found."; ?>
    <br><br><a href="?id=<?php echo --$id; ?>&back=1">Prev</a>
    <a style="margin-bottom: 1.5cm" href="?id=<?php echo 2+$id; ?>&next=1">Next</a> <?php
    die();
  }
  redir("Edit.php?id=$new_id","0");
  die();
}
$result->close(); $conn->close();

// Generate Links for File Attaches

$foreachList = array('photo_a', 'photo_b', 'photo_c', 'photo_d', 'photo_e', 'photo_f', 'attached_a', 'attached_b', 'attached_c', 'evidence_a', 'evidence_b', 'evidence_c');

foreach ($foreachList as &$value) {
$val = $value . "_label";
$link_suffix = ucfirst(substr($value,-1));

if (!empty($$value)) {
    if(substr($$value,0,4)=="http"){
      $$val = '<a class="pad-small-link" target="_blank" href=' . $$value . '>' . $link_suffix . '</a>';
    } elseif (file_exists("uploads/" . ($$value))){
      $$val = '<a class="pad-small-link" target="_blank" href=uploads/' . $$value . '>' . $link_suffix . '</a>';
    } else {
      $$val = '<a class="pad-small-link error" title="' . $value . ' is missing." target="_blank" href="#">' . $link_suffix . '</a>';
    }
  } else {
    $$val = null;
  }
}
?>
<title>EvilCM - Edit (<?php echo $LTE_1; ?>)</title>
</head>
<body>
<form action="Edit.php?id=<?php echo $id?>" autocomplete="off" id="form<?php echo $id; ?>" method="post">
  <div id="panel1">
    <input type="hidden" class="id" name="id" value="<?php echo $id?>">
    <input type="hidden" class="date_added" name="date_added" value="<?php echo $date_added?>">

    <label class="cellsite_type_label" for="cellsite_type">Type of cellsite</label><?php if ($isMobile =="true") { ?><br><?php } ?><select
    class="status_cw" autocomplete="on" name="status" required>
    <option style="display:none" disabled selected="selected"></option>
    <option <?php if($status == "verified") echo "selected"?> value="verified">Verified</option>
    <option <?php if($status == "unverified") echo "selected"?> value="unverified">Unverified</option>
    <option <?php if($status == "unmapped") echo "selected"?> value="unmapped">Unmapped</option>
    <option <?php if($status == "special") echo "selected"?> value="special">Special</option>
    <option <?php if($status == "weird") echo "selected"?> value="weird">Weird</option>
    </select><select class="concealed_cw" autocomplete="on" name="concealed" required>
    <option style="display:none" disabled selected="selected"></option>
    <option <?php if($concealed == "true") echo 'selected ';?>value="true">Concealed</option>
    <option <?php if($concealed == "false") echo 'selected ';?>value="false">Unconcealed</option>
    </select><select autocomplete="on" class="cellsite_type_cw" name="cellsite_type" required>
    <option style="display:none" disabled selected="selected"></option>
    <option <?php if($cellsite_type == "tower") echo "selected"?> value="tower">Tower</option>
    <option <?php if($cellsite_type == "rooftop") echo "selected"?> value="rooftop">Rooftop</option>
    <option <?php if($cellsite_type == "tank") echo "selected"?> value="tank">Tank</option>
    <option <?php if($cellsite_type == "utility_small") echo "selected"?> value="utility_small">Utility Pole</option>
    <option <?php if($cellsite_type == "utility_big") echo "selected"?> value="utility_big">Utility Tower</option>
    <option <?php if($cellsite_type == "monopalm") echo "selected"?> value="monopalm">Monopalm</option>
    <option <?php if($cellsite_type == "monopine") echo "selected"?> value="monopine">Monopine</option>
    <option <?php if($cellsite_type == "tree") echo "selected"?> value="misc-tree">Misc tree</option>
    <option <?php if($cellsite_type == "pole") echo "selected"?> value="pole">Pole</option>
    <option <?php if($cellsite_type == "disguised") echo "selected"?> value="disguised">Disguised structure</option>
    <option <?php if($cellsite_type == "other") echo "selected"?> value="other">Other/Uknown</option>
    </select><select class="carrier_cw" autocomplete="on" name="carrier">
    <option <?php if($carrier == "T-Mobile") echo "selected"?> value="T-Mobile">T-Mobile</option>
    <option <?php if($carrier == "ATT") echo "selected"?> value="ATT">AT&T</option>
    <option <?php if($carrier == "Verizon") echo "selected"?> value="Verizon">Verizon</option>
    <option <?php if($carrier == "Sprint") echo "selected"?> value="Sprint">Sprint</option>
    <option <?php if($carrier == "Unknown") echo "selected"?> value="Unknown">Unknown</option>
    </select>
    <label class="lte_nr_label" for="LTE_1"><a target="_blank" href="../goto.php?latitude=<?php echo $latitude?>&longitude=<?php echo $longitude?>&carrier=<?php echo $carrier?>&type=LTE&goto_page=CellMapper">LTE</a>/<a target="_blank" href="../goto.php?latitude=<?php echo $latitude?>&longitude=<?php echo $longitude?>&carrier=<?php echo $carrier?>&type=NR&goto_page=CellMapper">NR</a> IDs</label><?php if ($isMobile =="true") { ?><br><?php } ?><input
    type="text" class="lte_nr_cw" maxlength="7" id="LTE_1" value="<?php echo $LTE_1?>" placeholder="LTE_1" name="LTE_1"><input
    type="text" class="lte_nr_cw" maxlength="7" id="LTE_2" value="<?php echo $LTE_2?>" placeholder="LTE_2" name="LTE_2"><input
    type="text" class="lte_nr_cw" maxlength="7" id="LTE_3" value="<?php echo $LTE_3?>" placeholder="LTE_3" name="LTE_3"><input
    type="text" class="lte_nr_cw" maxlength="7" id="LTE_4" value="<?php echo $LTE_4?>" placeholder="LTE_4" name="LTE_4"><input
    type="text" class="lte_nr_cw" maxlength="7" id="LTE_5" value="<?php echo $LTE_5?>" placeholder="LTE_5" name="LTE_5"><input
    type="text" class="lte_nr_cw" maxlength="7" id="LTE_6" value="<?php echo $LTE_6?>" placeholder="LTE_6" name="LTE_6"><input
    type="text" class="lte_nr_cw" maxlength="7" id="NR_1" value="<?php echo $NR_1?>" placeholder="NR_1" name="NR_1"><input
    type="text" class="lte_nr_cw" maxlength="7" id="NR_2" value="<?php echo $NR_2?>" placeholder="NR_2" name="NR_2">


    <label class="id_params_label" for="pci_match">PCI match with all IDs</label><input type="text" class="id_params_cw pci_match" name="pci_match" value="<?php echo $pci_match?>">
    <label class="id_params_label" for="id_pattern_match">ID pattern with all IDs</label><input type="text" class="id_params_cw id_pattern_match" name="id_pattern_match" value="<?php echo $id_pattern_match?>">
    <label class="id_params_label" for="sector_match">Similiar sectors with all IDs</label><input type="text" class="id_params_cw sector_match" name="sector_match" value="<?php echo $sector_match?>">
    <label class="id_params_label" for="other_user_map_primary">Other user mapped primary</label><input type="text" class="id_params_cw other_user_map_primary" name="other_user_map_primary" value="<?php echo $other_user_map_primary?>">

    <label class="latitude_longitude_label" for="latitude"><a target="_blank" href="../goto.php?goto_page=CellMapper&latitude=<?php echo $latitude?>&longitude=<?php echo $longitude?>">Latitude/Longitude</a></label><?php if ($isMobile =="true") { ?><br><?php } ?><input
    type="text" class="inline-block latitude_longitude_cw" id="latitude" value="<?php echo $latitude?>" placeholder="Latitude" name="latitude"><input
    type="text" class="inline-block latitude_longitude_cw" id="longitude" value="<?php echo $longitude?>" placeholder="Longitude" name="longitude">

    <label class="addr_label" for="address"><a target="_blank" href="../goto.php?goto_page=Google Maps&latitude=<?php echo $latitude?>&longitude=<?php echo $longitude?>">Address</a></label><?php if ($isMobile =="true") { ?><br><?php } ?><input
    type="text" class="inline-block addr_address_cw" id="address" value="<?php echo $address?>" placeholder="Address" name="address"><input
    type="text" class="inline-block addr_city_cw" id="city" value="<?php echo $city?>" placeholder="City" name="city"><input
    type="text" class="inline-block addr_state_cw" id="state" value="<?php echo $state?>" placeholder="State" name="state"><input
    type="text" class="inline-block addr_zip_cw" id="zip" value="<?php echo $zip?>" placeholder="Zip" name="zip">
    <label class="ID street_view_label" for="street_view_url"><a target="_blank" href="<?php if (!empty($street_view_url)) { echo $street_view_url;} else { echo "https://www.google.com/maps?layer=c&cbll=$latitude,$longitude";  }?>">Street view URL</a></label><input type="text" class="street_view_cw" name="street_view_url" value="<?php echo $street_view_url?>">

      <label class="tags_label" for="tags">Tags/Bio</label><input placeholder="Tags" type="text" class="tags_cw" name="tags" value="<?php echo $tags?>">
    <?php if ($isMobile !="true") { ?>
    <textarea rows="10" cols="120" class="bio" placeholder="Bio" name="bio"><?php echo $bio?></textarea><br> <?php } else { ?>
    <textarea rows="6" cols="50" class="bio" placeholder="Bio" name="bio"><?php echo $bio?></textarea><br> <?php } ?>

    </div>
    <div id="panel2">
    <label class="evidence_label" for="evidence_a">Evidence <span style="float: right"><?php echo $evidence_a_label?><?php echo $evidence_b_label?><?php echo $evidence_c_label?></span></label><input
    type="text" class="evidence_cw" name="evidence_a" value="<?php echo $evidence_a?>"><input
    type="text" class="evidence_cw" name="evidence_b" value="<?php echo $evidence_b?>"><input
    type="text" class="evidence_cw" name="evidence_c" value="<?php echo $evidence_c?>">

    <label class="attached_label" for="attached_a">Extras <span style="float: right"><?php echo $attached_a_label;?><?php echo $attached_b_label;?><?php echo $attached_c_label;?></span></label><input
    type="text" class="attached_cw" name="attached_a" value="<?php echo $attached_a?>"><input
    type="text" class="attached_cw" name="attached_b" value="<?php echo $attached_b?>"><input
    type="text" class="attached_cw" name="attached_c" value="<?php echo $attached_c?>">

    <label class="photo_label" for="photo">Photos <span style="float: right"><?php echo $photo_a_label; echo $photo_b_label; echo $photo_c_label;?></span></label><input
    type="text" class="photo_cw" name="photo_a" value="<?php echo $photo_a?>"><input
    type="text" class="photo_cw" name="photo_b" value="<?php echo $photo_b?>"><input
    type="text" class="photo_cw" name="photo_c" value="<?php echo $photo_c?>">
    <label class="photo_label" for="photo">Photos <span style="float: right"><?php echo $photo_d_label; echo $photo_e_label; echo $photo_f_label;?></span></label><input
    type="text" class="photo_cw" name="photo_d" value="<?php echo $photo_d?>"><input
    type="text" class="photo_cw" name="photo_e" value="<?php echo $photo_e?>"><input
    type="text" class="photo_cw" name="photo_f" value="<?php echo $photo_f?>">

    <br><label class="evidence_scores_label" for="permit_score">Permit Score</label><input type="text" class="evidence_scores_cw" name="permit_score" value="<?php echo $permit_score?>">
    <br><label class="evidence_scores_label" for="trails_match">Trails Match</label><input type="text" class="evidence_scores_cw" name="trails_match" value="<?php echo $trails_match?>">
    <br><label class="evidence_scores_label" for="carriers_dont_trail_match">Number of carriers CellMapper data rules out</label><input type="text" class="evidence_scores_cw" name="carriers_dont_trail_match" value="<?php echo $carriers_dont_trail_match?>">
    <br><label class="evidence_scores_label" for="antennas_match_carrier"><abbr title="(1-100)&#013;0 being not at all&#013;100 being perfectly">Antennas match carrier</abbr></label><input type="text" class="evidence_scores_cw" name="antennas_match_carrier" value="<?php echo $antennas_match_carrier?>">
    <br><label class="evidence_scores_label" for="cellmapper_triangulation"><abbr title="CellMapper Triangulation: how close the CellMapper estimated location is to the actual location.&#013;&#013;(1-100)&#013;0 being very far away&#013;100 being very closely">CellMapper Triangulation</abbr></label><input type="text" class="evidence_scores_cw" name="cellmapper_triangulation" value="<?php echo $cellmapper_triangulation?>">
    <br><label class="evidence_scores_label" for="image_evidence"><abbr title="On-site image evidence: a piece of equipment with a sticker on it that has the carrier name.&#013;&#013;(1-100)&#013;0 being none&#013;100 being perfect)">On-site image evidence</abbr></label><input type="text" class="evidence_scores_cw" name="image_evidence" value="<?php echo $image_evidence?>">
    <br><label class="evidence_scores_label" for="verified_by_visit"><abbr title="(1-100)&#013;0 being not at all&#013;100 very thorough">Verified by visit</abbr></label><input type="text" class="evidence_scores_cw verified_by_visit" name="verified_by_visit" value="<?php echo $verified_by_visit?>">
    <br><label class="evidence_scores_label" for="sector_split_match"><abbr title="(1-100)&#013;0 being not at all&#013;100 being perfectly">Sector split match</abbr></label><input type="text" class="evidence_scores_cw sector_split_match" name="sector_split_match" value="<?php echo $sector_split_match?>">
    <br><label class="evidence_scores_label" for="archival_antenna_addition">Number of visible antenna modifications</abbr></label><input type="text" class="evidence_scores_cw archival_antenna_addition" name="archival_antenna_addition" value="<?php echo $archival_antenna_addition?>">
    <br><label class="evidence_scores_label" for="only_reasonable_location"><abbr title="(1-100)&#013;0 being not at all&#013;100 being perfectly">Only reasonable location</abbr></label><input type="text" class="evidence_scores_cw only_reasonable_location" name="only_reasonable_location" value="<?php echo $only_reasonable_location?>">
    <br><label class="evidence_scores_label" for="alt_carriers_here">Number of other carriers here</label><input type="text" class="evidence_scores_cw alt_carriers_here" name="alt_carriers_here" value="<?php echo $alt_carriers_here?>">
    </div>
</tbody>
</table>
<input style="margin-bottom: 0.25cm" type="submit" class="submitbutton" value="Submit">
<a href="?id=<?php echo --$id; ?>&back=1">Prev</a>
<a style="padding-bottom: 2.5cm" href="?id=<?php echo 2+$id; ?>&next=1">Next</a>
<?php
$id++;
if (!isset($carrier)) $carrier = null;
$db_map_link = "Map.php?latitude=" . $latitude . "&longitude=" . $longitude . "&zoom=18&carrier=" . $carrier;
echo '<a class="widget" title="View all info" href="Reader.php?back_url=Edit&id='.$id.'">🔍</a>';
echo '<a class="widget" title="Delete" href="Delete.php?id='.$id.'">✂️</a>';
echo '<a target="_blank" title="View on Database Map" class="widget" href="' . $db_map_link . '">🌎</a>';
?>
</form>
<script> if ( window.history.replaceState ) { window.history.replaceState( null, null, window.location.href );}</script>
<?php include "includes/footer.php"; ?>
</body>
</html>
