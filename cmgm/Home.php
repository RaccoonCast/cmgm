<!DOCTYPE html>
<html lang="en">
   <head>
     <script src="/js/copyToClipboard.js"></script>
      <?php
      include "functions.php";
      include "js/index.js.php";
      if (!isset($_GET['latitude']) OR !isset($_GET['longitude'])) {
        include "convert-func.php";
        include "js/locationNotKnown.js.php";
        die();
      }
      ?>
   </head>
   <body class="flex">
     <?php include "includes/misc-functions/prettyInfoDisplay.php"; ?>

     <form id="form" action="goto.php" method="get" autocomplete="off">
         <input type="textbox" name="data" oninput="changeFormAction();" id="txtresult" class="textbox"><br>
         <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
         <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
         <input type="hidden" name="carrier" value="<?php if (!empty($_GET['carrier'])) echo $carrier;?>">
         <input type="hidden" name="address" value="<?php if (!empty($_GET['address'])) echo $address;?>">
         <input type="hidden" name="zip" value="<?php if (!empty($_GET['zip'])) echo $zip;?>">
         <input type="hidden" name="city" value="<?php if (!empty($_GET['city'])) echo $city;?>">
         <input type="hidden" name="state" value="<?php if (!empty($_GET['state'])) echo $state;?>">
         <input type="submit" class="submitbutton width-50" style="color:#D93A6C" name="goto_page" value="Database"><input
         type="submit" class="submitbutton width-25" style="color:#D93A6C" name="goto_page" value="Form"><input
         type="submit" class="submitbutton width-25" style="color:#D93A6C" name="goto_page" value="Map"> <br>
         <input type="submit" class="submitbutton" style="color:#33D333" name="goto_page" value="CellMapper"><br>
         <input type="submit" class="submitbutton width-50" style="color:#5695F6" name="goto_page" value="Google Maps"><input
         type="submit" class="submitbutton width-50" style="color:#5695F6" name="goto_page" value="Street View"><br>
     </form>
     <form id="form" action="goto.php" method="get" autocomplete="off">
         <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
         <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
         <input type="submit" class="submitbutton" style="color:#BA03FC" name="goto_page" value="Settings">
     </form>
   </body>
</html>
