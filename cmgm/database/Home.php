<!DOCTYPE html>
<html lang="en">
   <head>
     <script src="/js/copyToClipboard.js"></script>
      <?php
      include "../functions.php";
      ?>
   </head>
   <body>
     <form class="flex" id="form" action="../goto.php" method="get" autocomplete="off">
         <?php include  "../includes/misc-functions/prettyInfoDisplay.php" ?>
         <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
         <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
         <input type="hidden" name="carrier" value="<?php if (!empty($_GET['carrier'])) echo $carrier;?>">
         <input type="hidden" name="address" value="<?php echo $address;?>">
         <input type="hidden" name="zip" value="<?php echo $zip;?>">
         <input type="hidden" name="city" value="<?php echo $city;?>">
         <input type="hidden" name="state" value="<?php echo $state;?>">
         <input type="submit" class="width-50 submitbutton" style="color:#F80000" name="goto_page" value="Form" /><input
         type="submit" class="submitbutton width-50" style="color:#E9A623" name="goto_page" value="Map" /><br>
         <input type="submit" class="submitbutton" style="color:#e31bdc" name="goto_page" value="Search" /><br>
         <input type="submit" onclick="copyToClipboard('<?php echo $address;?>')" class="submitbutton" style="color: #6be63e;" name="goto_page" value="Permits"><br>
         <input type="submit" class="submitbutton" style="color:#36a1ff" name="goto_page" value="Upload" /><br>
         <input type="submit" class="submitbutton" style="color:#000000" name="goto_page" value="Back" />
         <!-- <input type="submit" class="submitbutton" style="color:#B17DC9" name="goto_page" value="LA Permit Map" /><br> -->
       </form>
     <?php include "includes/footer.php"; ?>
   </body>
</html>
