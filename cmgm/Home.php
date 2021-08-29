<!DOCTYPE html>
<html lang="en">
   <head>
     <script src="/js/copyToClipboard.js"></script>
      <?php
      $titleOverride = "true";
      include "functions.php";
      include "js/index.js.php";
      if (!isset($_GET['latitude']) OR !isset($_GET['longitude'])) include "includes/home-functions/page-handler.php";
      include_once SITE_ROOT . "/includes/home-functions/goto.php";

      if (empty($address) OR $address == " ") echo "<title>Home</title>";
      if (!empty($address)) echo "<title>Home - ($address)</title>";
      ?>
   </head>
   <body class="flex">
     <?php include "includes/misc-functions/prettyInfoDisplay.php"; ?>
     <form id="form" action="/" method="post" autocomplete="off">
       <div class="buttons">
         <input type="text" name="data" oninput="changeFormAction();" id="txtresult" class="textbox w-100">
         <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
         <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
         <input type="hidden" name="carrier" value="<?php echo $carrier;?>">
         <input type="hidden" name="address" value="<?php echo $address;?>">
         <input type="hidden" name="zip" value="<?php echo $zip;?>">
         <input type="hidden" name="city" value="<?php echo $city;?>">
         <input type="hidden" name="state" value="<?php echo $state;?>">
         <input type="hidden" id="rerunData" name="rerunData" value="false"><input
         type="submit" class="sb w-33" style="color:#D93A6C" id="link01" name="goto" onclick="changeF('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"DB-Form",NULL) ?>');"value="DB-Form"><input
         type="submit" class="sb w-33" style="color:#D93A6C" id="link02" name="goto" onclick="changeF('/database/Edit.php?id=<?php echo $latitude . "," . $longitude;?>);" value="DB-Edit"><input
         type="submit" class="sb w-33" style="color:#D93A6C" id="link03" name="goto" onclick="changeF('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"DB-Map",NULL) ?>');" value="DB-Map"><input
         type="submit" class="sb w-50" style="color:#E31BDC" id="link04" name="goto" onclick="changeF('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"Search",NULL) ?>'');" value="Search"><input
         type="submit" class="sb w-50" style="color:#E9A623" id="link05" name="goto" onclick="changeF('database/Upload.php');" name="Upload" value="Upload"><input
         type="submit" class="sb w-50" style="color:#33D333" id="link06" name="goto" onclick="changeF('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"CellMapper",NULL) ?>');" value="CellMapper"><input
         type="submit" class="sb w-50" style="color:#33D333" id="link07" name="goto" onclick="changeF('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"Beta",NULL) ?>');" value="Beta"><input
         type="submit" class="sb w-50" style="color:#5695F6" id="link08" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"Google Maps",NULL) ?>');" value="Google Maps"><input
         type="submit" class="sb w-50" style="color:#5695F6" id="link09" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"Street View",NULL) ?>');" value="Street View"><input
         type="submit" class="sb w-50" style="color:#6BE63E" id="link10" name="goto" onclick="changeF('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"Permits",NULL) ?>');" value="Permits"><input
         type="submit" class="sb w-50" style="color:#6BE63E" id="link11" name="goto" onclick="changeF('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"AntennaSearch",NULL) ?>');" value="AntennaSearch"><input
         type="submit" class="sb w-100" style="color:#BA03FC" id="link12" name="goto" onclick="changeF('includes/useridsys/Settings.php');" value="Settings">
     </form>
   </body>
</html>
