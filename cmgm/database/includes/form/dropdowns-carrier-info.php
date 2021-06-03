<select onClick="lte_1Reqd()" id="status" class="custominput status-custom-width dropdown" autocomplete="on" name="status" required>
<option style="display:none" disabled selected="selected"></option>
<option value="verified">Verified</option>
<option value="unverified">Unverified</option>
<option value="unmapped">Unmapped</option>
<option value="special">Special</option>
<option value="weird">Weird</option>
</select><select class="custominput carrier-custom-width dropdown" autocomplete="on" name="carrier">
<?php if (!isset($carrier)) { $carrier = null; ?>
<option style="display:none" disabled selected="selected"></option> <?php } ?>
<option <?php if($carrier == "T-Mobile") echo 'selected="selected"';?> value="T-Mobile">T-Mobile</option>
<option <?php if($carrier == "ATT") echo 'selected="selected"';?> value="ATT">AT&T</option>
<option <?php if($carrier == "Verizon") echo 'selected="selected"';?> value="Verizon">Verizon</option>
<option <?php if($carrier == "Sprint") echo 'selected="selected"';?> value="Sprint">Sprint</option>
<option <?php if($carrier == "Unknown") echo 'selected="selected"';?> value="Unknown">Unknown</option>
</select><select class="custominput concealed-custom-width dropdown" autocomplete="on" name="concealed" required>
<option style="display:none" disabled selected="selected"></option>
<option value="true">Concealed</option>
<option value="false">Unconcealed</option>
</select><select class="custominput cellsite-type-custom-width dropdown" autocomplete="on" name="cellsite_type" required>
<option style="display:none" disabled selected="selected"></option>
<option value="tower">Tower</option>
<option value="rooftop">Rooftop</option>
<option value="tank">Tank</option>
<option value="utility_small">Utility Pole</option>
<option value="utility_big">Utility Tower</option>
<option value="monopalm">Monopalm</option>
<option value="monopine">Monopine</option>
<option value="misc-tree">Misc tree</option>
<option value="pole">Pole</option>
<option value="disguised">Disguised structure</option>
<option value="other">Other/Uknown</option>
</select>
<!-- Multiple? -->
