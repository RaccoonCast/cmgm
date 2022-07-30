<form action="Edit.php?id=<?php echo $id; if (isset($_GET['multiplier'])) echo "&multiplier=" . $_GET['multiplier']; ?>" id="form<?php echo $id; ?>" autofill="off" autocomplete="off" method="post">
  <?php if (@$padlock == "true") echo '<fieldset disabled="disabled">'; ?>
  <div class="panel1">
    <?php if (isset($_GET['new'])) { ?><input type="hidden" class="id" name="new" value="true"> <?php } ?>
    <input type="hidden" class="id" name="id" value="<?php echo $id?>">
    <input type="hidden" class="date_added" name="date_added" value="<?php echo @$date_added?>">

    <label class="cellsite_type_label">Type of cellsite</label><select
    class="status_cw" onClick="lte_1Reqd()" id="status" name="status" required>
    <option style="display:none" value="">&nbsp;</option>
    <option <?php if(@$status == "verified") echo "selected"?> value="verified">Verified</option>
    <option <?php if(@$status == "unverified") echo "selected"?> value="unverified">Unverified</option>
    </select><select class="concealed_cw" name="concealed" required>
    <option style="display:none" value="">&nbsp;</option>
    <option <?php if(@$concealed == "true") echo 'selected ';?>value="true">Concealed</option>
    <option <?php if(@$concealed == "false") echo 'selected ';?>value="false">Unconcealed</option>
    </select><select class="carrier_cw" name="carrier">
    <option <?php if(@$carrier == "T-Mobile") echo "selected"?> value="T-Mobile">T-Mobile</option>
    <option <?php if(@$carrier == "ATT") echo "selected"?> value="ATT">AT&T</option>
    <option <?php if(@$carrier == "Verizon") echo "selected"?> value="Verizon">Verizon</option>
    <option <?php if(@$carrier == "Sprint") echo "selected"?> value="Sprint">Sprint</option>
    <option <?php if(@$carrier == "Unknown") echo "selected"?> value="Unknown">Unknown</option>
    </select><select class="cellsite_type_cw" name="cellsite_type" required>
    <option style="display:none" value="">&nbsp;</option>
    <option <?php if(@$cellsite_type == "tower") echo "selected"?> value="tower">Tower</option>
    <option <?php if(@$cellsite_type == "rooftop") echo "selected"?> value="rooftop">Rooftop</option>
    <option <?php if(@$cellsite_type == "tank") echo "selected"?> value="tank">Tank</option>
    <option <?php if(@$cellsite_type == "utility_small") echo "selected"?> value="utility_small">Utility Pole</option>
    <option <?php if(@$cellsite_type == "utility_big") echo "selected"?> value="utility_big">Utility Tower</option>
    <option <?php if(@$cellsite_type == "monopalm") echo "selected"?> value="monopalm">Monopalm</option>
    <option <?php if(@$cellsite_type == "monopine") echo "selected"?> value="monopine">Monopine</option>
    <option <?php if(@$cellsite_type == "misc-tree") echo "selected"?> value="misc-tree">Misc tree</option>
    <option <?php if(@$cellsite_type == "pole") echo "selected"?> value="pole">Pole</option>
    <option <?php if(@$cellsite_type == "structure") echo "selected"?> value="structure">Structure mount</option>
    <option <?php if(@$cellsite_type == "other") echo "selected"?> value="other">Other/Uknown</option>
    </select>

    <?php
      if ($carrier == "Unknown") { $cm_carrier = $default_carrier; } else { $cm_carrier = $carrier; }
      if (!empty($latitude) && !empty($longitude)) $cellmapper_link_lte = cellmapperLink($latitude,$longitude,$cm_zoom,$cm_carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc);
      if (!empty($latitude) && !empty($longitude)) $cellmapper_link_nr = cellmapperLink($latitude,$longitude,$cm_zoom,$cm_carrier,"NR",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc);
      $cmgm_map_search = "https://cmgm.us/api/getTowers.php?latitude=" . $latitude . "&longitude=" . $longitude . "&carrier=" . $carrier . "&limit=5"
    ?>
    <label class="lte_nr_label" for="LTE_1"><a target="_blank" href="<?php echo @$cellmapper_link_lte;?>">LTE</a>/<a target="_blank" href="<?php echo @$cellmapper_link_nr;?>">NR</a> <a target="_blank" href="<?php echo @$cmgm_map_search;?>">IDs</a><span class="floatright"><?php include "json/lte.php"; include "json/nr.php"; ?></span></label><?php if ($isMobile =="true") { ?><br><?php } ?><input
    type="number" class="lte_cw" inputmode="numeric" pattern="[0-9]*" id="LTE_1" value="<?php echo @$LTE_1?>" placeholder="LTE_1" name="LTE_1"><input
    type="number" class="lte_cw" inputmode="numeric" pattern="[0-9]*" id="LTE_2" value="<?php echo @$LTE_2?>" placeholder="LTE_2" name="LTE_2"><input
    type="number" class="lte_cw" inputmode="numeric" pattern="[0-9]*" id="LTE_3" value="<?php echo @$LTE_3?>" placeholder="LTE_3" name="LTE_3"><input
    type="number" class="lte_cw" inputmode="numeric" pattern="[0-9]*" id="LTE_4" value="<?php echo @$LTE_4?>" placeholder="LTE_4" name="LTE_4"><input
    type="number" class="lte_cw" inputmode="numeric" pattern="[0-9]*" id="LTE_5" value="<?php echo @$LTE_5?>" placeholder="LTE_5" name="LTE_5"><input
    type="number" class="lte_cw" inputmode="numeric" pattern="[0-9]*" id="LTE_6" value="<?php echo @$LTE_6?>" placeholder="LTE_6" name="LTE_6"><input
    type="number" class="nr_cw" inputmode="numeric" pattern="[0-9]*" id="NR_1" value="<?php echo @$NR_1?>" placeholder="NR_1" name="NR_1"><input
    type="number" class="nr_cw" inputmode="numeric" pattern="[0-9]*" id="NR_2" value="<?php echo @$NR_2?>" placeholder="NR_2" name="NR_2"><input
    type="number" class="region_cw" id="region_lte" value="<?php echo @$region_lte?>" placeholder="REGION_LTE" name="region_lte"><input
    type="number" class="region_cw" id="region_nr" value="<?php echo @$region_nr?>" placeholder="REGION_NR" name="region_nr">

    <label class="pci_label" for="PCI_1">PCIs</label><?php if ($isMobile =="true") { ?><br><?php } ?><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_1" value="<?php echo @$PCI_1?>" placeholder="PCI_1" name="PCI_1"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_2" value="<?php echo @$PCI_2?>" placeholder="PCI_2" name="PCI_2"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_3" value="<?php echo @$PCI_3?>" placeholder="PCI_3" name="PCI_3"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_4" value="<?php echo @$PCI_4?>" placeholder="PCI_4" name="PCI_4"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_5" value="<?php echo @$PCI_5?>" placeholder="PCI_5" name="PCI_5"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_6" value="<?php echo @$PCI_6?>" placeholder="PCI_6" name="PCI_6"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_7" value="<?php echo @$PCI_7?>" placeholder="PCI_7" name="PCI_7"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_8" value="<?php echo @$PCI_8?>" placeholder="PCI_8" name="PCI_8"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_9" value="<?php echo @$PCI_9?>" placeholder="PCI_9" name="PCI_9"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_10" value="<?php echo @$PCI_10?>" placeholder="PCI_10" name="PCI_10"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_11" value="<?php echo @$PCI_11?>" placeholder="PCI_11" name="PCI_11"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_12" value="<?php echo @$PCI_12?>" placeholder="PCI_12" name="PCI_12"><?php if(!isMobile()) {?><label
    class="pci_label" for="PCI_1">PCIs</label><?php } ?><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_13" value="<?php echo @$PCI_13?>" placeholder="PCI_13" name="PCI_13"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_14" value="<?php echo @$PCI_14?>" placeholder="PCI_14" name="PCI_14"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_15" value="<?php echo @$PCI_15?>" placeholder="PCI_15" name="PCI_15"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_16" value="<?php echo @$PCI_16?>" placeholder="PCI_16" name="PCI_16"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_17" value="<?php echo @$PCI_17?>" placeholder="PCI_17" name="PCI_17"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_18" value="<?php echo @$PCI_18?>" placeholder="PCI_18" name="PCI_18"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_19" value="<?php echo @$PCI_19?>" placeholder="PCI_19" name="PCI_19"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_20" value="<?php echo @$PCI_20?>" placeholder="PCI_20" name="PCI_20"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_21" value="<?php echo @$PCI_21?>" placeholder="PCI_21" name="PCI_21"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_22" value="<?php echo @$PCI_22?>" placeholder="PCI_22" name="PCI_22"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_23" value="<?php echo @$PCI_23?>" placeholder="PCI_23" name="PCI_23"><input
    type="number" class="pci_cw" inputmode="numeric" pattern="[0-9]*" id="PCI_24" value="<?php echo @$PCI_24?>" placeholder="PCI_24" name="PCI_24">

    <?php
    // value fill-in

    if (!empty($other_user_map_primary)) { $tmp_other_user_map_primary = $other_user_map_primary; } else { $tmp_other_user_map_primary = "false"; $tmp_idparam_4 = "true"; }
    ?>
    <label class="id_params_label">Multi ID Parameters</label><select title="PCIs match" class="id_params_cw" name="pci_match">
    <option style="display: none" value="<?php echo @$pci_match;?>" selected>PCIs match: <?php echo @$pci_match;?></option>
    <option value="true">true</option>
    <option value="false">false</option>
    <option value="partial">partial</option>
    </select><select class="id_params_cw" title="ID Pattern Match" name="id_pattern_match">
    <option style="display: none" value="<?php echo @$id_pattern_match;?>" selected>ID Pattern Match: <?php echo @$id_pattern_match;?></option>
    <option value="true">true</option>
    <option value="false">false</option>
    <option value="partial">partial</option>
    </select><select class="id_params_cw" title="Sectors match" name="sector_match">
    <option style="display: none" value="<?php echo @$sector_match;?>" selected>Sectors match: <?php echo @$sector_match;?></option>
    <option value="true">true</option>
    <option value="false">false</option>
    <option value="partial">partial</option>
    </select><select class="id_params_cw <?php if (isset($tmp_idparam_4)) echo 'warning2';?>"  title="Primary already located" name="other_user_map_primary">
    <option style="display: none" value="<?php echo @$tmp_other_user_map_primary;?>" <selected>Primary already located: <?php echo @$tmp_other_user_map_primary;?></option>
    <option value="true">true</option>
    <option value="false">false</option>
    </select>


    <label class="latitude_longitude_label"><a id="addr_gmaps" target="_blank" href="https://www.google.com/maps/place/<?php echo $latitude.",".$longitude;?>/@<?php echo $latitude.",".$longitude;?>,20z/data=!3m1!1e3">Lat/Lon</a><span class="floatright"><?php include "latLongMod/copy.php"; ?></span></label><input
    type="text" class="latitude_cw" id="latitude" value="<?php echo @$latitude?>" placeholder="Latitude" name="latitude" required><input
    type="text" class="longitude_cw" id="longitude" value="<?php echo @$longitude?>" placeholder="Longitude" name="longitude" required><input
    type="text" autocomplete="new-street-address" class="addr_address_cw" value="<?php echo @$address?>" placeholder="Address" name="address"><input
    type="text" autocomplete="new-street-address" class="addr_city_cw" value="<?php echo @$city?>" placeholder="City" name="city"><input
    type="text" autocomplete="new-street-address" class="addr_state_cw" value="<?php echo @$state?>" placeholder="State" name="state"><input
    type="text" autocomplete="new-street-address" class="addr_zip_cw" value="<?php echo @$zip?>" placeholder="Zip" name="zip">

    <label class="sv_label">Street View<span class="floatright"><?php echo @$sv_linklabel_a; ?></span></label><input
    type="text" autocomplete="new-street-address" id="sv_a" class="inline-block sv_cw sv_a" name="sv_a" placeholder="STREET_VIEW_A" value="<?php if (isset($sv_a)) echo str_replace("https://", "",@$sv_a); ?>"><input
    type="text" autocomplete="new-street-address" title="Street View date for STREET_VIEW_A" class="inline-block sv_date_cw sv_a" name="sv_a_date" placeholder="DATE" value="<?php echo @$sv_a_date ?>"><input
    type="text" autocomplete="new-street-address" id="sv_b" class="inline-block sv_cw sv_b" name="sv_b" placeholder="STREET_VIEW_B" value="<?php if (isset($sv_b)) echo str_replace("https://", "",@$sv_b); ?>"><input
    type="text" autocomplete="new-street-address" title="Street View date for STREET_VIEW_B" class="inline-block sv_date_cw sv_b" name="sv_b_date" placeholder="DATE" value="<?php echo @$sv_b_date ?>"><input
    type="text" autocomplete="new-street-address" id="sv_c" class="inline-block sv_cw sv_c" name="sv_c" placeholder="STREET_VIEW_C" value="<?php if (isset($sv_c)) echo str_replace("https://", "",@$sv_c); ?>"><input
    type="text" autocomplete="new-street-address" title="Street View date for STREET_VIEW_C" class="inline-block sv_date_cw sv_c" name="sv_c_date" placeholder="DATE" value="<?php echo @$sv_c_date ?>">
    <?php if($isMobile == "false") {?><div style="display: inline-block" class="sv_label"><label class="sv1_label" title="Street View does not show the most recent antenna upgrade.&#10;This only applies to sites with antennas that aren't concealed."><input type="hidden" name="old_street_view" value="false"><input
    type="checkbox" name="old_street_view" value="true" <?php if(@$old_street_view == "true") echo "checked";?>>Old SV</label><label class="sv2_label" class="floatright"><?php echo @$sv_linklabel_b; ?></label></div><?php } ?><input
    type="text" autocomplete="new-street-address" id="sv_d" class="inline-block sv_cw sv_d" name="sv_d" placeholder="STREET_VIEW_D" value="<?php if (isset($sv_d)) echo str_replace("https://", "",@$sv_d); ?>"><input
    type="text" autocomplete="new-street-address" title="Street View date for STREET_VIEW_D" class="inline-block sv_date_cw sv_d" name="sv_d_date" placeholder="DATE" value="<?php echo @$sv_d_date ?>"><input
    type="text" autocomplete="new-street-address" id="sv_e" class="inline-block sv_cw sv_e" name="sv_e" placeholder="STREET_VIEW_E" value="<?php if (isset($sv_e)) echo str_replace("https://", "",@$sv_e); ?>"><input
    type="text" autocomplete="new-street-address" title="Street View date for STREET_VIEW_E" class="inline-block sv_date_cw sv_e" name="sv_e_date" placeholder="DATE" value="<?php echo @$sv_e_date ?>"><input
    type="text" autocomplete="new-street-address" id="sv_f" class="inline-block sv_cw sv_f" name="sv_f" placeholder="STREET_VIEW_F" value="<?php if (isset($sv_f)) echo str_replace("https://", "",@$sv_f); ?>"><input
    type="text" autocomplete="new-street-address" title="Street View date for STREET_VIEW_F" class="inline-block sv_date_cw sv_f" name="sv_f_date" placeholder="DATE" value="<?php echo @$sv_f_date ?>">

    <?php
    // value fill-in

    if (!empty($cm_pin_distance)) { $tmp_cm_pin_distance = $cm_pin_distance; } else { $tmp_cm_pin_distance = "1.0x"; $tmp_misc_1 = "true"; }
    if (!empty($cm_pin_inverted)) { $tmp_cm_pin_inverted = $cm_pin_inverted; } else { $tmp_cm_pin_inverted = "false"; $tmp_misc_2 = "true";}
    if (!empty($sector_configuration)) { $tmp_sector_configuration = $sector_configuration; } else { $tmp_sector_configuration = "3-sector"; $tmp_misc_3 = "true";}
    if (!empty($split_sector)) { $tmp_split_sector = $split_sector; } else { $tmp_split_sector = "false"; $tmp_misc_4 = "true";}
    if (!empty($special_setup)) { $tmp_special_setup = $special_setup; } else { $tmp_special_setup = "false"; $tmp_misc_5 = "true";}
    ?>
    <label class="misc_label">Misc <span class="floatright-desktop"><?php if (!isMobile()) { include "latLongMod/lte.php"; include "latLongMod/nr.php"; }?></span></label><select class="misc_50_cw <?php if (isset($tmp_misc_1)) echo 'warning2';?>" title="lorem ipsum" name="cm_pin_distance">
    <option style="display: none" value="<?php echo @$tmp_cm_pin_distance ?>" selected>CM Pin Distance: <?php echo @$tmp_cm_pin_distance ?></option>
    <option value="0.4x">0.4x</option>
    <option value="0.5x">0.5x</option>
    <option value="0.6x">0.6x</option>
    <option value="0.7x">0.7x</option>
    <option value="0.8x">0.8x</option>
    <option value="0.9x">0.9x</option>
    <option value="1.0x">1.0x</option>
    <option value="1.1x">1.1x</option>
    <option value="1.2x">1.2x</option>
    <option value="1.3x">1.3x</option>
    <option value="1.4x">1.4x</option>
    <option value="1.5x">1.5x</option>
    <option value="1.6x">1.6x</option>
    <option value="1.7x">1.7x</option>
    <option value="1.8x">1.8x</option>
    <option value="1.9x">1.9x</option>
    <option value="2.0x">2.0x</option>
    <option value="2.1x">2.1x</option>
    <option value="2.2x">2.2x</option>
    <option value="2.3x">2.3x</option>
    </select><select class="misc_50_cw <?php if (isset($tmp_misc_2)) echo 'warning2';?>" title="lorem ipsum" name="cm_pin_inverted">
    <option style="display: none" value="<?php echo @$tmp_cm_pin_inverted ?>" selected>Inverted pins: <?php echo @$tmp_cm_pin_inverted ?></option>
    <option value="true">true</option>
    <option value="false">false</option>
    </select><select class="misc_50_cw misc_cw <?php if (isset($tmp_misc_3)) echo 'warning2';?>" title="lorem ipsum" name="sector_configuration">
    <option style="display: none"  value="<?php echo $tmp_sector_configuration?>" selected>Sector Config: <?php echo @$tmp_sector_configuration ?></option>
    <option value="2-sector">2-sector</option>
    <option value="2-sector + 1">2-sector + 1</option>
    <option value="2-sector + 1">2-sector + 2</option>
    <option disabled value="">---</option>
    <option value="3-sector">3-sector</option>
    <option value="3-sector + 1">3-sector + 1</option>
    <option value="3-sector + 1">3-sector + 2</option>
    <option disabled value="">---</option>
    <option value="4-sector">4-sector</option>
    <option value="4-sector + 1">4-sector + 1</option>
    <option value="4-sector + 1">4-sector + 2</option>
    </select><select class="misc_cw <?php if (isset($tmp_misc_4)) echo 'warning2';?>" title="lorem ipsum" name="split_sector">
    <option style="display: none" value="<?php echo @$tmp_split_sector ?>" selected>Split-sector: <?php echo @$tmp_split_sector ?></option>
    <option value="true">true</option>
    <option value="false">false</option>
    </select><select class="misc_cw <?php if (isset($tmp_misc_5)) echo 'warning2';?>" title="lorem ipsum" name="special_setup">
    <option style="display: none" value="<?php echo @$tmp_special_setup ?>" selected>Special setup: <?php echo @$tmp_special_setup ?></option>
    <option value="true">true</option>
    <option value="false">false</option>
    </select>

    <label class="tags_label"><?php if (!isMobile()) { echo "Tags/Notes"; } else { echo "Pin:";} if (isMobile()) { include "latLongMod/lte.php"; include "latLongMod/nr.php"; }?></label><input
    placeholder="Tags" type="text" class="tags_cw" name="tags" value="<?php echo @$tags?>">

    <?php if ($isMobile !="true") { ?>
    <textarea rows="10" cols="120" class="notes" placeholder="Notes" name="notes"><?php echo @$notes?></textarea> <?php } else { ?>
    <textarea rows="6" cols="50" class="notes" placeholder="Notes" name="notes"><?php echo @$notes?></textarea> <?php } ?>


    </div>
    <div class="panel2">
    <label class="evidence_label">Evidence <span class="floatright"><?php echo @$evidence_a_label?><?php echo @$evidence_b_label?><?php echo @$evidence_c_label?></span></label><input
    type="text" id="ev_a" class="evidence_cw" name="evidence_a" placeholder="EVIDENCE_A" value="<?php echo @$evidence_a?>"><input
    type="text" id="ev_b" class="evidence_cw" name="evidence_b" placeholder="EVIDENCE_B" value="<?php echo @$evidence_b?>"><input
    type="text" id="ev_c" class="evidence_cw" name="evidence_c" placeholder="EVIDENCE_C" value="<?php echo @$evidence_c?>">

    <label class="bingmaps_label">Bing Maps <span class="floatright"><?php echo @$bingmaps_a_label?><?php echo @$bingmaps_b_label?><?php echo @$bingmaps_c_label?></span></label><input
    type="text" id="bm_a" class="bingmaps_cw" name="bingmaps_a" placeholder="BINGMAPS_A" value="<?php echo @$bingmaps_a?>"><input
    type="text" id="bm_b" class="bingmaps_cw" name="bingmaps_b" placeholder="BINGMAPS_B" value="<?php echo @$bingmaps_b?>"><input
    type="text" id="bm_c" class="bingmaps_cw" name="bingmaps_c" placeholder="BINGMAPS_C" value="<?php echo @$bingmaps_c?>">

    <label class="photo_label">Photos <span class="floatright"><?php echo @$photo_link_linklabel_a; ?></span></label><input
    type="text" id="ph_a" class="photo_cw" name="photo_a" placeholder="PHOTO_A" value="<?php echo @$photo_a?>"><input
    type="text" id="ph_b" class="photo_cw" name="photo_b" placeholder="PHOTO_B" value="<?php echo @$photo_b?>"><input
    type="text" id="ph_c" class="photo_cw" name="photo_c" placeholder="PHOTO_C" value="<?php echo @$photo_c?>">
    <?php if($isMobile == "false") {?><label class="photo_label">Photos <span class="floatright"><?php echo @$photo_link_linklabel_b; ?></span></label><?php } ?><input
    type="text" id="ph_d" class="photo_cw" name="photo_d" placeholder="PHOTO_D" value="<?php echo @$photo_d?>"><input
    type="text" id="ph_e" class="photo_cw" name="photo_e" placeholder="PHOTO_E" value="<?php echo @$photo_e?>"><input
    type="text" id="ph_f" class="photo_cw" name="photo_f" placeholder="PHOTO_F" value="<?php echo @$photo_f?>">

    <label class="extra_label">Extras <span class="floatright"><?php echo @$extra_linklabel_a ?></span></label><input
    type="text" id="ex_a" class="extra_cw" name="extra_a" placeholder="EXTRA_A" value="<?php echo @$extra_a?>"><input
    type="text" id="ex_b" class="extra_cw" name="extra_b" placeholder="EXTRA_B" value="<?php echo @$extra_b?>"><input
    type="text" id="ex_c" class="extra_cw" name="extra_c" placeholder="EXTRA_C" value="<?php echo @$extra_c?>">
    <?php if($isMobile == "false") {?><label class="extra_label">Extras <span class="floatright"><?php echo @$extra_linklabel_b; ?></span></label><?php } ?><input
    type="text" id="ex_d" class="extra_cw" name="extra_d" placeholder="EXTRA_D" value="<?php echo @$extra_d?>"><input
    type="text" id="ex_e" class="extra_cw" name="extra_e" placeholder="EXTRA_E" value="<?php echo @$extra_e?>"><input
    type="text" id="ex_f" class="extra_cw" name="extra_f" placeholder="EXTRA_F" value="<?php echo @$extra_f?>">



    <div class="_panel1">
    <label class="evidence_scores_label">Permit Score</label><input
    type="number" max="100" class="evidence_scores_cw" name="permit_score" value="<?php echo @$permit_score?>">

    <br><label title="(1-100)&#10;Consistent bright green trails?&#10;Dense trails?&#10;Fade to dark green with distance as expected?&#10;All of this should affect the number." class="evidence_scores_label">Trails Match</label><input
    type="number" max="100" class="evidence_scores_cw" name="trails_match" value="<?php echo @$trails_match?>">

    <br><label title="Antennas look like other <?php echo $carrier;?> setups?&#10;(1-100)&#10;0 being not at all&#10;100 being perfectly" class="evidence_scores_label">Antennas match carrier</label><input
    type="number" max="100" class="evidence_scores_cw" name="antennas_match_carrier" value="<?php echo @$antennas_match_carrier?>">

    <br><label title="How close the CellMapper estimated location is to the actual location.&#10;&#10;(1-100)&#10;0 being very far away&#10;100 being very close" class="evidence_scores_label">CellMapper Triangulation</label><input
    type="number" max="100" class="evidence_scores_cw" name="cellmapper_triangulation" value="<?php echo @$cellmapper_triangulation?>">

    </div><div class="_panel2">
    <label title="A piece of equipment with a sticker on it that has the carrier name or carrier-specific contact info.&#10;&#10;(1-100)&#10;0 being none&#10;100 being perfect)" class="evidence_scores_label">On-site image evidence</label><input
    type="number" max="100" class="evidence_scores_cw" name="image_evidence" value="<?php echo @$image_evidence?>">

    <br><label title="(1-100)&#10;0 being not at all&#10;100 very thorough" class="evidence_scores_label">Verified by visit</label><input
    type="number" max="100" class="evidence_scores_cw verified_by_visit" name="verified_by_visit" value="<?php echo @$verified_by_visit?>">

    <br><label title="(1-100)&#10;0 being not at all&#10;100 being perfectly" class="evidence_scores_label">Sector split match</label><input
    type="number" max="100" class="evidence_scores_cw sector_split_match" name="sector_split_match" value="<?php echo @$sector_split_match?>">

    <label title="Most of the surrounding buildings residential?&#10;All other towers for this carrier in the area already located?&#10;&#10;(1-100)&#10;0 being not reasonable at all to assume this location&#10;100 being very reasonable to assume this location" class="evidence_scores_label">Only reasonable location</label><input
    type="number" max="100" class="evidence_scores_cw only_reasonable_location" name="only_reasonable_location" value="<?php echo @$only_reasonable_location?>">

    </div><div class="_panel3">
    <label title="&#10;Number of antenna modifications that can be recongized as pertaining to a specific carrier.&#10;" class="evidence_scores_label"># of recongizable modifactions</label><input
    type="number" max="5" class="evidence_scores_cw archival_antenna_addition" name="archival_antenna_addition" value="<?php echo @$archival_antenna_addition?>">

    <br><label title="(0-3)&#10;CellMapper trails for other carriers far weaker&#10;RootMetrics coverage data shows other carriers weak&#10;Other carrier(s) towers near here already located" class="evidence_scores_label"># of carriers data rules out</label><input
    type="number" max="3" class="evidence_scores_cw" name="carriers_ruled_out" value="<?php echo @$carriers_ruled_out?>">

    <br><label title="(0-3)&#10;Number of other carriers at this location/address&#10;&#10;This does not affect evidence score." class="evidence_scores_label"># of other carriers here</label><input
    type="number" max="3" class="evidence_scores_cw alt_carriers_here" name="alt_carriers_here" value="<?php echo @$alt_carriers_here?>">

    <label title="Evidence score is calculated by the permit score/trails_match/etc" class="evidence_scores_label">Evidence Score</label><input
    type="number" class="evidence_scores_cw evidence_score" name="evidence_score" value="<?php include "../includes/functions/calculateEV-math.php"; echo $ev;?>" readonly>
    </div>
    <?php if ($isMobile !="true") { ?>
    <textarea rows="10" cols="120" class="edit_history" placeholder="Edit History: " name="edit_history" readonly><?php echo @$edit_history; ?></textarea><br> <?php } else { ?>
    <textarea rows="6" cols="50" class="edit_history" placeholder="Edit History: " name="edit_history" readonly><?php echo @$edit_history; ?></textarea><br> <?php } ?>
    </div>
    <?php if (!empty($latitude)) include "includes/edit/MapWithPin.php"; ?>
    <?php if (isset($_GET['new'])) { $submit_label = "Create";} else {$submit_label = "Save";}  ?>
<?php if (!isset($delete) && $padlock == "false") { ?><input style="margin-bottom: 0.25cm" onClick="lte_1Reqd()" name="edittag" type="submit" class="sb cmgm-btn" value="<?php echo $submit_label?>"><?php }
if (@$padlock == "true") echo '</fieldset>'; ?>

</form>
