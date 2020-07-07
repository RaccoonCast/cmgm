<!DOCTYPE html>
<html lang="en">
   <head>
     <?php include "functions.php";
     if(isMobile()){
       echo '<link rel="stylesheet" href="styles/Hub/mobile.css">';
     } else {
       echo '<link rel="stylesheet" href="styles/Hub/desktop.css">';
     }
     ?>
   </head>
   <body class="flex">
     <?php
     if (empty($latitude)) {
       header('Location: https://cmgm.gq/');
     } else {
       //The buttons
       hubLatLong("database/database-form.php","#F80000","Database");
       hubLatLong("database/Search.php","#e31bdc","Search");
       hubLatLong("database/DatabaseMap.php","#E9A623","Database Map");
       hubLatLong("comparison-mode/index.php","#6aa3a3","Comparison Mode");
     }
?>
<!-- Put latitude & longitude & carrier & zoom from URL bar into next form automatically -->
<form action=\Hub.php method=get>
  <input type="hidden" name="latitude" value="<?php echo $latitude?>">
  <input type="hidden" name="longitude" value="<?php echo $longitude?>">
  <input type="hidden" name="carrier" value="<?php echo $carrier?>">
  <input type="hidden" name="zoom" value="<?php echo $zoom?>">
  <input type="submit" class="submitbutton" value='Back' >
</form>
<script src="js/copy.js"></script>
   </body>
</html>
