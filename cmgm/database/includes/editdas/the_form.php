<form action="Edit.php?id=<?php if(isset($id)) {echo $id; } else { if (isset($new)) {echo "new";}}  ?>" autofill="off" autocomplete="off" method="post">
  <?php if (@$padlock == "true") echo '<fieldset disabled="disabled">'; ?>

    <?php if (isset($new)) { ?><input type="hidden" class="id" name="new" value="true"> <?php } ?>
    <input type="hidden" class="id" name="id" value="<?php echo $id?>">
    <input type="hidden" class="date_added" name="date_added" value="<?php echo @$date_added?>">

    <select
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
    <option <?php if(@$cellsite_type == "oDAS") echo "selected"?> value="oDAS">oDAS</option>
    <option <?php if(@$cellsite_type == "iDAS") echo "selected"?> value="iDAS">iDAS</option>
    </select>

    <?php
      if ($carrier == "Unknown") { $cm_carrier = $default_carrier; } else { $cm_carrier = $carrier; }
      // include "$SITE_ROOT/includes/misc-functions/cm_linkgen.php";

      // if (!empty($latitude) && !empty($longitude)) $cellmapper_link_lte = cellmapperLink($latitude,$longitude,$cm_zoom,$cm_carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc);
      // if (!empty($latitude) && !empty($longitude)) $cellmapper_link_nr = cellmapperLink($latitude,$longitude,$cm_zoom,$cm_carrier,"NR",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc);
      $cmgm_map_search = $domain_with_http . "/api/getTowers.php?latitude=" . $latitude . "&longitude=" . $longitude . "&carrier=" . $carrier . "&limit=5"
    ?>
 <input type="number" class="lte_cw" inputmode="numeric" pattern="[0-9]*" id="LTE_1" value="<?php echo @$LTE_1?>" placeholder="LTE_1" name="LTE_1"><input
 type="number" class="region_cw" id="region_lte" value="<?php echo @$region_lte?>" placeholder="REGION_LTE" name="region_lte">


    <?php
    // value fill-in
    $gmaps_url = "https://www.google.com/maps/place/$latitude,$longitude/@$latitude,$longitude,20z/data=!3m1!1e3";
    if (!empty($other_user_map_primary)) { $tmp_other_user_map_primary = $other_user_map_primary; } else { $tmp_other_user_map_primary = "false"; $tmp_idparam_4 = "true"; }
    ?>
    <div class="content-body">
      <?php
      for ($x = 1; $x <= 12; $x++) {
        if (isset($sv_cg1_a)) $sv_cg1_a = str_replace("https://", "",@$sv_cg1_a);
        if (isset($sv_cg1_b)) $sv_cg1_b = str_replace("https://", "",@$sv_cg1_b);
        echo  '<label class="cellgroup_label">Cell(s) for node '.$x.'</label>';
        echo  '<input type="text" class="cell_cw" value="'.@$cell1_1.'" placeholder="CELL" name="cell1_1">';
        echo  '<input type="text" class="cell_cw" value="'.@$cell2_1.'" placeholder="CELL" name="cell2_1">';
        echo  '<input type="text" class="cell_cw" value="'.@$cell3_1.'" placeholder="CELL" name="cell3_1">';
        echo  '<input type="text" class="cell_cw" value="'.@$cell4_1.'" placeholder="CELL" name="cell4_1">';
        echo  '<input type="text" class="latitude_cw" value="'.@$latitude_cg1.'" placeholder="Latitude" name="cg'.${$x}.'_latitude" required>';
        echo  '<input type="text" class="longitude_cw" value="'.@$longitude_cg1.'" placeholder="Longitude" name="cg'.${$x}.'_longitude" required>';
        echo  '<input type="text" autocomplete="new-street-address" class="ib sv_cw" name="sv_cg1_a" placeholder="STREET_VIEW_A" value="'.@$sv_cg1_a.'">';
        echo  '<input type="text" autocomplete="new-street-address" class="ib sv_cw" name="sv_cg1_b" placeholder="STREET_VIEW_B" value="'.@$sv_cg1_a.'">';
        echo  '<input type="text" class="attachment_cw" name="attachment_cg1_a" placeholder="ATTACHMENT_A" value="'.@$attachment_cg1_a.'">';
        echo  '<input type="text" class="attachment_cw" name="attachment_cg1_b" placeholder="ATTACHMENT_B" value="'.@$attachment_cg1_b.'">';
      }
  ?>
    </div>

    <label class="tags_label"><?php if (!isMobile()) { echo "Tags/Notes"; } else { echo "Pin:";} if (isMobile()) { include "latLongMod/lte.php"; include "latLongMod/nr.php"; }?></label><input
    placeholder="Tags" type="text" class="tags_cw" name="tags" value="<?php echo @$tags?>">

    <?php if ($isMobile !="true") { ?>
    <textarea rows="10" cols="120" class="notes" placeholder="Notes" name="notes"><?php echo @$notes?></textarea> <?php } else { ?>
    <textarea rows="6" cols="50" class="notes" placeholder="Notes" name="notes"><?php echo @$notes?></textarea> <?php } ?>


    <?php if ($isMobile !="true") { ?>
    <textarea rows="10" cols="120" class="edit_history" placeholder="Edit History: " name="edit_history" readonly><?php echo @$edit_history; ?></textarea><br> <?php } else { ?>
    <textarea rows="6" cols="50" class="edit_history" placeholder="Edit History: " name="edit_history" readonly><?php echo @$edit_history; ?></textarea><br> <?php } ?>

    <?php if (!empty($latitude)) include "includes/edit/MapWithPin.php"; ?>
    <?php if (isset($new)) { $submit_label = "Create";} else {$submit_label = "Save";} // && $padlock == "false") ?>
<?php if (!isset($delete)) { ?><input style="margin-bottom: 0.25cm" onClick="lte_1Reqd()" name="edittag" type="submit" class="sb cmgm-btn" value="<?php echo $submit_label?>"><?php }
if (@$padlock == "true") echo '</fieldset>'; ?>

</form>
