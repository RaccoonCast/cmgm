<!DOCTYPE html>
<html lang="en">
   <head>
     <script src="/js/copyToClipboard.js"></script>
      <?php
      $titleOverride = "true";
      include "../functions.php";
      include "../js/index.js.php";
      include SITE_ROOT . "/includes/home-functions/convert.php";
      if (isset($_POST['no_home_smart'])) {
        include SITE_ROOT . "/includes/home-functions/goto.php";
        redir(function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,@$_POST['goto'],NULL),"0");
      }
      if (!empty($_POST['data'])) [$latitude,$longitude,$carrier,$address,$zip,$city,$state,$goto,$conv_type] = convert($_POST['data'],"HomeSmart",$default_latitude,$default_longitude,$maps_api_key,$userIP,$default_carrier);
      if (empty($address) OR $address == " ") echo "<title>Home</title>";
      if (!empty($address)) echo "<title>Home - ($address)</title>";
      ?>
   </head>
   <body>
     <form class="flex" id="form" action="Home.php" method="post" autocomplete="off">
         <?php include  "../includes/misc-functions/prettyInfoDisplay.php" ?>
         <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
         <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
         <input type="hidden" name="carrier" value="<?php echo $carrier;?>">
         <input type="hidden" name="address" value="<?php echo $address;?>">
         <input type="hidden" name="zip" value="<?php echo $zip;?>">
         <input type="hidden" name="city" value="<?php echo $city;?>">
         <input type="hidden" name="state" value="<?php echo $state;?>">
         <input type="hidden" name="no_home_smart" value="true">
         <div class="buttons">
         <input type="submit" class="width-50 submitbutton" style="color:#F80000" name="goto" value="Form" /><input
         type="submit" class="submitbutton width-50" style="color:#E9A623" name="goto" value="Map" /><br>
         <input type="submit" class="submitbutton width-100" style="color:#e31bdc" name="goto" value="Search" /><br>
         <input type="submit" onclick="copyToClipboard('<?php echo $address;?>')" class="submitbutton width-100" style="color: #6be63e;" name="goto_page" value="Permits"><br>
         <input type="submit" class="submitbutton width-100" style="color:#36a1ff" name="goto" value="Upload" /><br>
         <input type="submit" class="submitbutton width-100" style="color:#000000" onclick="changeF('../');" name="goto" value="Back" />
       </div>
         <!-- <input type="submit" class="submitbutton" style="color:#B17DC9" name="goto_page" value="LA Permit Map" /><br> -->
       </form>
     <?php include "includes/footer.php"; ?>
   </body>
</html>
