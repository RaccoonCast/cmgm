<!DOCTYPE html>
<html lang="en">
   <head>
     <script src="/js/copyToClipboard.js"></script>
      <?php
      $titleOverride = "true";
      include "functions.php";
      include "js/index.js.php";
      if (!isset($_GET['latitude']) OR !isset($_GET['longitude'])) include "includes/home-functions/page-handler.php";


      if (empty($address) OR $address == " ") echo "<title>Home</title>";
      if (!empty($address)) echo "<title>Home - ($address)</title>";
      ?>
   </head>
   <body class="flex">
     <?php include "includes/misc-functions/prettyInfoDisplay.php"; ?>
     <form id="form" action="/" method="post" autocomplete="off">
       <div class="buttons">
         <input type="text" name="data" oninput="changeFormAction();" id="txtresult" class="textbox width-100"><br>
         <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
         <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
         <input type="hidden" name="carrier" value="<?php echo $carrier;?>">
         <input type="hidden" name="address" value="<?php echo $address;?>">
         <input type="hidden" name="zip" value="<?php echo $zip;?>">
         <input type="hidden" name="city" value="<?php echo $city;?>">
         <input type="hidden" name="state" value="<?php echo $state;?>">
         <input type="hidden" id="rerunData" name="rerunData" value="false">

         <input type="submit" class="submitbutton width-50" style="color:#D93A6C" onclick="changeF('database/Home.php');" name="goto" value="Database"><input
         type="submit" class="submitbutton width-25" style="color:#D93A6C" name="goto" value="Form"><input
         type="submit" class="submitbutton width-25" style="color:#D93A6C" name="goto" value="Map"><br>
         <input type="submit" class="submitbutton width-75" style="color:#33D333" name="goto" value="CellMapper"><input
         type="submit" class="submitbutton width-25" style="color:#33D333" name="goto" value="Beta"><br>
         <input type="submit" class="submitbutton width-50" style="color:#5695F6" name="goto" value="Google Maps"><input
         type="submit" class="submitbutton width-50" style="color:#5695F6" name="goto" value="Street View"><br>
     </form>
     <form id="form" action="Home.php" method="post" autocomplete="off">
         <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
         <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
         <input type="hidden" id="rerunData" name="rerunData" value="false">
         <input type="submit" class="submitbutton width-100" style="color:#BA03FC" name="goto" value="Settings">
       </div>
     </form>
   </body>
</html>
