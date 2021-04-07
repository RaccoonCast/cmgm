<!DOCTYPE html>
<html lang="en">
   <head>
      <?php
      include "js/index.js.php";
      include "functions.php";
      if (!isset($_GET['latitude']) OR !isset($_GET['longitude'])) {
        echo '<script>locateMe()</script>';
        die();
      }
      ?>
   </head>
   <body class="flex">
     <?php include "includes/functions/prettyInfoDisplay.php"; ?>
     <form id="form" action="goto.php" method="get" autocomplete="off">
         <input type="textbox" name="data" oninput="changeFormAction();" id="txtresult" class="textbox"><br>
         <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
         <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
         <input type="hidden" name="carrier" value="<?php if (!empty($_GET['carrier'])) echo $carrier;?>">
         <input type="hidden" name="address" value="<?php if (!empty($_GET['address'])) echo $address;?>">
         <input type="hidden" name="zip" value="<?php if (!empty($_GET['zip'])) echo $zip;?>">
         <input type="hidden" name="city" value="<?php if (!empty($_GET['city'])) echo $city;?>">
         <input type="hidden" name="state" value="<?php if (!empty($_GET['state'])) echo $state;?>">
         <input type="submit" class="submitbutton" style="color:#D93A6C" name="goto_page" value="Database"> <br>
         <input type="submit" class="submitbutton" style="color:#33D333" name="goto_page" value="CellMapper"><br>
         <input type="submit" class="submitbutton" style="color:#5695F6" name="goto_page" value="Google Maps"><br>
     </form>
     <form target="_blank" action="Permits.php" method="get">
       <input type="hidden" name="city" value="<?php echo $city;?>">
       <input type="submit" onclick="copyToClipboard('<?php echo $address;?>')" class="submitbutton" style="color: #00e3e0;" value="Permits"><br>
     </form>
     <form id="form" action="goto.php" method="get" autocomplete="off">
         <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
         <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
         <input type="submit" class="submitbutton" style="color:#BA03FC" name="goto_page" value="Settings">
     </form>
   </body>
</html>
