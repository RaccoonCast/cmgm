<!DOCTYPE html>
<html lang="en">
   <head>
      <?php
      include "functions.php";
      include "permits.php";
      include "js/index.js.php";
      ?>
   </head>
   <body class="flex">
     <?php
  if (!isset($latitude) OR !isset($longitude)) {
    echo '<script>locateMe()</script>';
    die();
  }
     if (!isset($_GET['permit_redirect'])) {
     include "includes/functions/prettyInfoDisplay.php"
 ?>
     <form id="form" action="goto.php" method="get" autocomplete="off">
         <input type="textbox" name="data" onClick="changeFormAction();" onfocusout="submit()" id="txtresult" class="textbox"><br>
         <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
         <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
         <input type="hidden" name="carrier" value="<?php if (isset($_GET['carrier'])) echo $carrier;?>">
         <input type="hidden" name="address" value="<?php echo $address;?>">
         <input type="hidden" name="zip" value="<?php echo $zip;?>">
         <input type="hidden" name="city" value="<?php echo $city;?>">
         <input type="hidden" name="state" value="<?php echo $state;?>">
         <input type="submit" class="submitbutton" style="color:#00ccff" name="goto_page" value="Database" /> <br>
         <input type="submit" class="submitbutton" style="color:#5DC904" name="goto_page" value="CellMapper" /><br>
         <input type="submit" class="submitbutton" style="color:#4185FA" name="goto_page" value="Google Maps" /><br>
         <input type="submit" class="submitbutton" style="color:#B17DC9" name="goto_page" value="LA Permit Map" /><br>
     </form>
     <form target="_blank" action="Home.php" method="get">
       <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
       <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
       <input type="hidden" name="city" value="<?php echo $city;?>">
       <input type="hidden" name="permit_redirect" value="true">
       <input type="submit" onclick="copyToClipboard('<?php echo $address;?>')" class="submitbutton" style="color: #00e3e0;" value="Permits">
     </form>
     <form>
       <input onclick="copyToClipboard('<?php echo $latitude?>,<?php echo $longitude?>')" style="color: #ffb700" type="submit" class="submitbutton" value="Copy">
     </form>
    <?php }?>
<script src="js/copy.js"></script>
   </body>
</html>
