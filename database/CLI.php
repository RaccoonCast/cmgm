<?php
include '../functions.php';
if (!empty($_GET['string'])) {
  redir("CLI.php","0");
  $data = $_GET['string'];
}
?>
<select class="custominput carrier-custom-width dropdown" autocomplete="on" name="carrier">
<option <?php if($carrier == "T-Mobile") echo 'selected="selected"';?> value="T-Mobile">T-Mobile</option>
<option <?php if($carrier == "ATT") echo 'selected="selected"';?> value="ATT">AT&T</option>
<option <?php if($carrier == "Verizon") echo 'selected="selected"';?> value="Verizon">Verizon</option>
<option <?php if($carrier == "Sprint") echo 'selected="selected"';?> value="Sprint">Sprint</option>
<option <?php if($carrier == "Unknown") echo 'selected="selected"';?> value="Unknown">Unknown</option>
</select>
<form action="CLI.php" method="get">
  <input type="text" name="string">
  <input type="text" name="string">
  <input type="text" name="string">
  <input type="submit">
</form>
