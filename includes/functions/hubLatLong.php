<?php
  function hubLatLong($file,$color,$text,$target) {
  if (!empty($_GET['latitude'])) { $latitude = $_GET['latitude']; }
  if (!empty($_GET['longitude'])) { $longitude = $_GET['longitude']; }
  if (!empty($_GET['carrier'])) { $carrier = $_GET['carrier']; }
  if (!empty($_GET['zip'])) { $zip = $_GET['zip']; }
  if (!empty($_GET['state'])) { $state = $_GET['state']; }
  if (!empty($_GET['city'])) { $city = $_GET['city']; }
  if (!empty($_GET['address'])) { $address = $_GET['address']; }
  echo "<form target=" . $target . " action=" . $file . " " . "method=" . "get" . ">
  "; ?>
<input type="hidden" name="latitude" value="<?php echo $latitude;?>">
  <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
  <input type="hidden" name="zip" value="<?php echo $zip;?>">
  <input type="hidden" name="city" value="<?php echo $city;?>">
  <input type="hidden" name="state" value="<?php echo $state;?>">
  <input type="hidden" name="carrier" value="<?php if (isset($_GET['carrier'])) echo $carrier;?>">
  <input type="submit" class="submitbutton" style="color: <?php echo $color; ?>;" value='<?php echo $text; ?>' >
</form> <?php } ?>
