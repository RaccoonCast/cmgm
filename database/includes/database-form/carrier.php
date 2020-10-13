<select class="fakeinput dropdown" autocomplete="on" name="carrier">
<?php
if('T-Mobile' == '' . $carrier . '') { echo '<option selected="selected" value="T-Mobile">T-Mobile</option>';} else { echo '<option value="T-Mobile">T-Mobile</option>';}
if('ATT' == '' . $carrier . '') { echo '<option selected="selected" value="ATT">AT&T</option>';} else { echo '<option value="ATT">AT&T</option>';}
if('Verizon' == '' . $carrier . '') { echo '<option selected="selected" value="Verizon">Verizon</option>';} else { echo '<option value="Verizon">Verizon</option>';}
if('Sprint' == '' . $carrier . '') { echo '<option selected="selected" value="Sprint">Sprint</option>';} else { echo '<option value="Sprint">Sprint</option>';}
?>
</select>

<!-- Multiple? -->

<div style="padding-top: 5px; padding-bottom: 5px;">
<input type="checkbox" id="multiple_carriers" name="carrier_multiple" value="true">
<label for="multiple_carriers">Multiple carriers</label><br>
</div>
