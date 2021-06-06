<!DOCTYPE html>
<html lang="en">
   <head>
     <script src="/js/copyToClipboard.js"></script>
      <?php
      $titleOverride = "true";
      include "functions.php";
      include "js/index.js.php";
      if (!isset($_GET['latitude']) OR !isset($_GET['longitude'])) {
      include "convert.php";

      // IF DATA HAS BEEN ENTERED IN THE FIELD - we need to A) convert & redirect to page OR B) convert & stay here.
      if (@$_POST['rerunData'] == "true" && $goto != "HomeSmart") {
        if ($debug_flag != "off") echo "Page handler type: 1";
        redir(convert($data,$goto,$default_latitude,$default_longitude,$maps_api_key,$userIP,$default_carrier),"0");
      }

      // IF DATA HAS NOT BEEN ENTERED IN THE FIELD - we need to A) find the proper redirect page and go to it with whatever location data we already have/had
      if (@$_POST['rerunData'] == "false" && $goto != "HomeSmart") {
        if ($debug_flag != "off") echo "Page handler type: 2";
        include SITE_ROOT . "/includes/misc-functions/goto.php";
        redir(function_goto($latitude,$longitude,$carrier,@$address,@$zip,@$city,@$state,@$goto,NULL),"0");
      }

      // Has data & goto been specified in the URL Bar via GET?
      if (isset($data) && isset($goto) && !isset($latitude)) {
        if ($debug_flag != "off") echo "Page handler type: 3";
        [$latitude,$longitude,$carrier,$address,$zip,$city,$state,$goto,$conv_type] = convert("$data","HomeSmart",$default_latitude,$default_longitude,$maps_api_key,$userIP,$default_carrier);
      }

      // No location specifeid? Use GPS
      if ($prefLocType == "gps" && !isset($latitude)) {
        if ($debug_flag != "off")echo "Page handler type: 4";
        include "js/locationNotKnown.js.php";
      }

      // No location specifeid? Use default lat,long.
      if ($prefLocType == "settings") if (!isset($data) && !isset($goto)) {
        if ($debug_flag != "off") echo "Page handler type: 5";
        [$latitude,$longitude,$carrier,$address,$zip,$city,$state,$goto,$conv_type] = convert("defaultLoc","HomeSmart",$default_latitude,$default_longitude,$maps_api_key,$userIP,$default_carrier);
      }
    }

      // echo $latitude . "<br>";
      // echo $longitude . "<br>";
      // echo $carrier . "<br>";
      // echo $address . "<br>";
      // echo $zip . "<br>";
      // echo $city . "<br>";
      // echo $goto . "<br>";

      ?>
   </head>
   <body class="flex">
     <?php include "includes/misc-functions/prettyInfoDisplay.php"; ?>
     <form id="form" action="Home.php" method="post" autocomplete="off">
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

         <input type="submit" class="submitbutton width-50" style="color:#D93A6C" name="goto" value="Database"><input
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
