<form action="Edit.php?id=<?php echo $id?>" autocomplete="off" id="form<?php echo $id; ?>" method="post">
  <?php if (@$padlock == "true") echo '<fieldset disabled="disabled">'; ?>
  <div class="panel1">
    <?php if (isset($_GET['new'])) { ?><input type="hidden" class="id" name="new" value="true"> <?php } ?>
    <input type="hidden" class="id" name="id" value="<?php echo $id?>">
    <input type="hidden" class="date_added" name="date_added" value="<?php echo @$date_added?>">

    <label class="cellsite_type_label">Type of cellsite</label><select
    class="status_cw" autocomplete="on" name="status" required>
    <option style="display:none" value=""></option>
    <option <?php if(@$status == "verified") echo "selected"?> value="verified">Verified</option>
    <option <?php if(@$status == "unverified") echo "selected"?> value="unverified">Unverified</option>
    <option <?php if(@$status == "unmapped") echo "selected"?> value="unmapped">Unmapped</option>
    <option <?php if(@$status == "special") echo "selected"?> value="special">Special</option>
    <option <?php if(@$status == "weird") echo "selected"?> value="weird">Weird</option>
    </select><select class="concealed_cw" autocomplete="on" name="concealed" required>
    <option style="display:none" value=""></option>
    <option <?php if(@$concealed == "true") echo 'selected ';?>value="true">Concealed</option>
    <option <?php if(@$concealed == "false") echo 'selected ';?>value="false">Unconcealed</option>
    </select><select autocomplete="on" class="cellsite_type_cw" name="cellsite_type" required>
    <option style="display:none" value=""></option>
    <option <?php if(@$cellsite_type == "tower") echo "selected"?> value="tower">Tower</option>
    <option <?php if(@$cellsite_type == "rooftop") echo "selected"?> value="rooftop">Rooftop</option>
    <option <?php if(@$cellsite_type == "tank") echo "selected"?> value="tank">Tank</option>
    <option <?php if(@$cellsite_type == "utility_small") echo "selected"?> value="utility_small">Utility Pole</option>
    <option <?php if(@$cellsite_type == "utility_big") echo "selected"?> value="utility_big">Utility Tower</option>
    <option <?php if(@$cellsite_type == "monopalm") echo "selected"?> value="monopalm">Monopalm</option>
    <option <?php if(@$cellsite_type == "monopine") echo "selected"?> value="monopine">Monopine</option>
    <option <?php if(@$cellsite_type == "misc-tree") echo "selected"?> value="misc-tree">Misc tree</option>
    <option <?php if(@$cellsite_type == "pole") echo "selected"?> value="pole">Pole</option>
    <option <?php if(@$cellsite_type == "disguised") echo "selected"?> value="disguised">Disguised structure</option>
    <option <?php if(@$cellsite_type == "other") echo "selected"?> value="other">Other/Uknown</option>
    </select><select class="carrier_cw" autocomplete="on" name="carrier">
    <option <?php if(@$carrier == "T-Mobile") echo "selected"?> value="T-Mobile">T-Mobile</option>
    <option <?php if(@$carrier == "ATT") echo "selected"?> value="ATT">AT&T</option>
    <option <?php if(@$carrier == "Verizon") echo "selected"?> value="Verizon">Verizon</option>
    <option <?php if(@$carrier == "Sprint") echo "selected"?> value="Sprint">Sprint</option>
    <option <?php if(@$carrier == "Unknown") echo "selected"?> value="Unknown">Unknown</option>
    </select>

    <?php
      if (isset($_GET['new'])) $beginning = null;
      if (@$carrier == "T-Mobile") $beginning = "MCC=310&MNC=260&";
      if (@$carrier == "Sprint") $beginning = "MCC=310&MNC=120&";
      if (@$carrier == "ATT") $beginning = "MCC=310&MNC=410&";
      if (@$carrier == "Verizon") $beginning = "MCC=311&MNC=480&";
      if (!empty($latitude) && !empty($longitude)) $cellmapper_link_lte = "https://www.cellmapper.net/map?$beginning"  . "type=LTE&latitude=$latitude&longitude=$longitude&zoom=18&showTowerLabels=false";
      if (!empty($latitude) && !empty($longitude)) $cellmapper_link_nr = "https://www.cellmapper.net/map?$beginning"  . "type=NR&latitude=$latitude&longitude=$longitude&zoom=18&showTowerLabels=false";
    ?>
    <label class="lte_nr_label" for="LTE_1"><a target="_blank" href="<?php echo @$cellmapper_link_lte;?>">LTE</a>/<a target="_blank" href="<?php echo @$cellmapper_link_nr;?>">NR</a> IDs</label><?php if ($isMobile =="true") { ?><br><?php } ?><input
    type="number" class="lte_nr_cw" maxlength="7" id="LTE_1" value="<?php echo @$LTE_1?>" placeholder="LTE_1" name="LTE_1"><input
    type="number" class="lte_nr_cw" maxlength="7" id="LTE_2" value="<?php echo @$LTE_2?>" placeholder="LTE_2" name="LTE_2"><input
    type="number" class="lte_nr_cw" maxlength="7" id="LTE_3" value="<?php echo @$LTE_3?>" placeholder="LTE_3" name="LTE_3"><input
    type="number" class="lte_nr_cw" maxlength="7" id="LTE_4" value="<?php echo @$LTE_4?>" placeholder="LTE_4" name="LTE_4"><input
    type="number" class="lte_nr_cw" maxlength="7" id="LTE_5" value="<?php echo @$LTE_5?>" placeholder="LTE_5" name="LTE_5"><input
    type="number" class="lte_nr_cw" maxlength="7" id="LTE_6" value="<?php echo @$LTE_6?>" placeholder="LTE_6" name="LTE_6"><input
    type="number" class="lte_nr_cw" maxlength="7" id="NR_1" value="<?php echo @$NR_1?>" placeholder="NR_1" name="NR_1"><input
    type="number" class="lte_nr_cw" maxlength="7" id="NR_2" value="<?php echo @$NR_2?>" placeholder="NR_2" name="NR_2">

    <div class="panel1">
    <label class="id_params_label">PCI match with all IDs</label><select class="id_params_cw" name="pci_match"><option style="display:none" value=""></option>
    <option <?php if(@$pci_match == "true") echo "selected"?> value="true">true</option>
    <option <?php if(@$pci_match == "false") echo "selected"?> value="false">false</option>
    <option <?php if(@$pci_match == "partial") echo "selected"?> value="partial">partial</option></select>
    <label class="id_params_label">ID pattern with all IDs</label><select class="id_params_cw" name="id_pattern_match"><option style="display:none" value=""></option>
    <option <?php if(@$id_pattern_match == "true") echo "selected"?> value="true">true</option>
    <option <?php if(@$id_pattern_match == "false") echo "selected"?> value="false">false</option>
    <option <?php if(@$id_pattern_match == "partial") echo "selected"?> value="partial">partial</option></select>

    </div><div class="panel2">
    <label class="id_params_label">Sector matches</label><select class="id_params_cw" name="sector_match"><option style="display:none" value=""></option>
    <option <?php if(@$sector_match == "true") echo "selected"?> value="true">true</option>
    <option <?php if(@$sector_match == "false") echo "selected"?> value="false">false</option>
    <option <?php if(@$sector_match == "partial") echo "selected"?> value="partial">partial</option></select>
    <label class="id_params_label">Primary already located</label><select class="id_params_cw" name="other_user_map_primary"><option style="display:none" value=""></option>
    <option <?php if(@$other_user_map_primary == "true") echo "selected"?> value="true">true</option>
    <option <?php if(@$other_user_map_primary == "false") echo "selected"?> value="false">false</option>
    <option <?php if(@$other_user_map_primary == "partial") echo "selected"?> value="partial">partial</option></select>
    </div>

    <label class="latitude_longitude_label" for="latitude"><span style="float: right"><a href="#" class="pad-small-link" onclick="copyToClipboard('<?php echo $latitude . ","; ?><?php echo $longitude; ?>')">Copy</a></span><a target="_blank" href="<?php echo @$cellmapper_link_lte;?>">Latitude/Longitude</a></label><input
    type="text" class="inline-block latitude_longitude_cw" id="latitude" value="<?php echo @$latitude?>" placeholder="Latitude" name="latitude"><input
    type="text" class="inline-block latitude_longitude_cw" id="longitude" value="<?php echo @$longitude?>" placeholder="Longitude" name="longitude">

    <label class="addr_label" for="address"><a target="_blank" href="https://www.google.com/maps/@?api=1&map_action=map&center=<?php echo @$latitude; ?>,<?php echo @$longitude; ?>&zoom=20&basemap=satellite">Address</a>
    <span style="float: right"><a href="#" class="pad-small-link" onclick="copyToClipboard('<?php echo $address . ", " . $city . ", " . $state . " " . $zip; ?>')">Copy</a></label><?php if ($isMobile =="true") { ?><br><?php } ?><input
    type="text" autocomplete="chrome-off" class="inline-block addr_address_cw" id="address" value="<?php echo @$address?>" placeholder="Address" name="address"><input
    type="text" autocomplete="chrome-off" class="inline-block addr_city_cw" id="city" value="<?php echo @$city?>" placeholder="City" name="city"><input
    type="text" autocomplete="chrome-off" class="inline-block addr_state_cw" id="state" value="<?php echo @$state?>" placeholder="State" name="state"><input
    type="text" autocomplete="chrome-off" class="inline-block addr_zip_cw" id="zip" value="<?php echo @$zip?>" placeholder="Zip" name="zip">

    <label class="street_view_url_label">Street view URL <span style="float: right"><?php echo @$street_view_url_a_label; echo @$street_view_url_b_label; echo @$street_view_url_c_label; echo @$street_view_url_d_label; ?></span></label><input
    type="text" class="inline-block street_view_url_cw" autocomplete="chrome-off" name="street_view_url_a" placeholder="STREET_VIEW_URL_A" value="<?php echo str_replace("https://", "",@$street_view_url_a); ?>"><input
    type="text" class="inline-block street_view_url_cw" autocomplete="chrome-off" name="street_view_url_b" placeholder="STREET_VIEW_URL_B" value="<?php echo str_replace("https://", "",@$street_view_url_b); ?>"><input
    type="text" class="inline-block street_view_url_cw" autocomplete="chrome-off" name="street_view_url_c" placeholder="STREET_VIEW_URL_C" value="<?php echo str_replace("https://", "",@$street_view_url_c); ?>"><input
    type="text" class="inline-block street_view_url_cw" autocomplete="chrome-off" name="street_view_url_d" placeholder="STREET_VIEW_URL_D" value="<?php echo str_replace("https://", "",@$street_view_url_d); ?>">

      <label class="tags_label">Tags/Bio</label><input placeholder="Tags" type="text" class="tags_cw" name="tags" value="<?php echo @$tags?>">
    <?php if ($isMobile !="true") { ?>
    <textarea rows="10" cols="120" class="bio" placeholder="Bio" name="bio"><?php echo @$bio?></textarea> <?php } else { ?>
    <textarea rows="6" cols="50" class="bio" placeholder="Bio" name="bio"><?php echo @$bio?></textarea> <?php } ?>

    </div>
    <div class="panel2">
    <label class="evidence_label">Evidence <span style="float: right"><?php echo @$evidence_a_label?><?php echo @$evidence_b_label?><?php echo @$evidence_c_label?></span></label><input
    type="text" class="evidence_cw" name="evidence_a" placeholder="EVIDENCE_A" value="<?php echo @$evidence_a?>"><input
    type="text" class="evidence_cw" name="evidence_b" placeholder="EVIDENCE_B" value="<?php echo @$evidence_b?>"><input
    type="text" class="evidence_cw" name="evidence_c" placeholder="EVIDENCE_C" value="<?php echo @$evidence_c?>">

    <label class="extra_label">Extras <span style="float: right"><?php echo @$extra_a_label;?><?php echo @$extra_b_label;?><?php echo @$extra_c_label;?></span></label><input
    type="text" class="extra_cw" name="extra_a" placeholder="EXTRA_A" placeholder="PHOTO_A"value="<?php echo @$extra_a?>"><input
    type="text" class="extra_cw" name="extra_b" placeholder="EXTRA_B" value="<?php echo @$extra_b?>"><input
    type="text" class="extra_cw" name="extra_c" placeholder="EXTRA_C" value="<?php echo @$extra_c?>">

    <label class="photo_label">Photos <?php echo @$photo_link_linklabel_a; ?></label><input
    type="text" class="photo_cw" name="photo_a" placeholder="PHOTO_A" value="<?php echo @$photo_a?>"><input
    type="text" class="photo_cw" name="photo_b" placeholder="PHOTO_B" value="<?php echo @$photo_b?>"><input
    type="text" class="photo_cw" name="photo_c" placeholder="PHOTO_C" value="<?php echo @$photo_c?>">
    <?php if($isMobile == "false") {?><label class="photo_label">Photos <?php echo @$photo_link_linklabel_b; ?></span></label><?php } ?><input
    type="text" class="photo_cw" name="photo_d" placeholder="PHOTO_D" value="<?php echo @$photo_d?>"><input
    type="text" class="photo_cw" name="photo_e" placeholder="PHOTO_E" value="<?php echo @$photo_e?>"><input
    type="text" class="photo_cw" name="photo_f" placeholder="PHOTO_F" value="<?php echo @$photo_f?>">
    <div class="panel1">
    <label class="evidence_scores_label">Permit Score</label><input
    type="text" class="evidence_scores_cw" name="permit_score" value="<?php echo @$permit_score?>">

    <br><label class="evidence_scores_label">Trails Match</label><input
    type="text" class="evidence_scores_cw" name="trails_match" value="<?php echo @$trails_match?>">


    <br><label title="(1-100)&#10;0 being not at all&#10;100 being perfectly" class="evidence_scores_label">Antennas match carrier</label><input
    type="text" class="evidence_scores_cw" name="antennas_match_carrier" value="<?php echo @$antennas_match_carrier?>">

    <br><label title="CellMapper Triangulation: how close the CellMapper estimated location is to the actual location.&#10;&#10;(1-100)&#10;0 being very far away&#10;100 being very closely" class="evidence_scores_label">CellMapper Triangulation</label><input
    type="text" class="evidence_scores_cw" name="cellmapper_triangulation" value="<?php echo @$cellmapper_triangulation?>">

    <br><label title="On-site image evidence: a piece of equipment with a sticker on it that has the carrier name.&#10;&#10;(1-100)&#10;0 being none&#10;100 being perfect)" class="evidence_scores_label">On-site image evidence</label><input
    type="text" class="evidence_scores_cw" name="image_evidence" value="<?php echo @$image_evidence?>">

    <label title="(1-100)&#10;0 being not at all&#10;100 very thorough" class="evidence_scores_label">Verified by visit</label><input
    type="text" class="evidence_scores_cw verified_by_visit" name="verified_by_visit" value="<?php echo @$verified_by_visit?>">
    </div>
    <div class="panel2">

    <label title="(1-100)&#10;0 being not at all&#10;100 being perfectly" class="evidence_scores_label">Sector split match</label><input
    type="text" class="evidence_scores_cw sector_split_match" name="sector_split_match" value="<?php echo @$sector_split_match?>">

    <br><label class="evidence_scores_label">Number of visible antenna modifications</label><input
    type="text" class="evidence_scores_cw archival_antenna_addition" name="archival_antenna_addition" value="<?php echo @$archival_antenna_addition?>">

    <br><label title="(1-100)&#10;0 being not at all&#10;100 being perfectly" class="evidence_scores_label">Only reasonable location</label><input
    type="text" class="evidence_scores_cw only_reasonable_location" name="only_reasonable_location" value="<?php echo @$only_reasonable_location?>">

    <br><label title="(0-3)&#10;CellMapper trails for other carriers far weaker&#10;RootMetrics coverage data shows other carriers weak&#10;Other carrier(s) towers near here already located" class="evidence_scores_label">Number of carriers that data rules out.</label><input
    type="text" class="evidence_scores_cw" name="carriers_dont_trail_match" value="<?php echo @$carriers_dont_trail_match?>">

    <br><label class="evidence_scores_label">Number of other carriers here</label><input
    type="text" class="evidence_scores_cw alt_carriers_here" name="alt_carriers_here" value="<?php echo @$alt_carriers_here?>">

    <label title="Evidence score is calculated by the permit score/trails_match/etc" class="evidence_scores_label">Evidence Score</label><input
    type="text" class="evidence_scores_cw evidence_score" name="evidence_score" value="<?php include "../includes/functions/calculateEV-math.php"; echo $ev;?>" readonly>
    </div>
    <?php if ($isMobile !="true") { ?>
    <textarea rows="10" cols="120" class="edit_history" placeholder="Edit History: " name="edit_history" readonly><?php echo @$edit_history; ?></textarea><br> <?php } else { ?>
    <textarea rows="6" cols="50" class="edit_history" placeholder="Edit History: " name="edit_history" readonly><?php echo @$edit_history; ?></textarea><br> <?php } ?>
    </div>
    <?php if (isset($_GET['new'])) { $submit_label = "Create";} else {$submit_label = "Save";}  ?>
<?php if (!isset($delete) && $padlock == "false") { ?><input style="margin-bottom: 0.25cm" type="submit" class="submitbutton" value="<?php echo $submit_label?>"> <?php } ?>
<?php if (@$padlock == "true") echo '</fieldset>'; ?>
</form>
