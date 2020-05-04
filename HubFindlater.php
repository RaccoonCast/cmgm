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
       // hubLatLong("findlater/FindlaterDB.php","#e8db4a","FindlaterDB");
       hubLatLong("findlater/findlater-form.php","#F80000","Findlater");
       hubLatLong("findlater/Search.php","#e31bdc","Search");
       hubLatLong("findlater/FindlaterMap.php","#E9A623","Findlater Map");
     }
?>
<form action=\Hub.php method=get>
  <input type="hidden" name="latitude" value="<?php echo $latitude?>">
  <input type="hidden" name="longitude" value="<?php echo $longitude?>">
  <input type="hidden" name="carrier" value="<?php echo $carrier?>">
  <input type="submit" class="submitbutton" value='Back' >
</form>
<script src="js/copy.js"></script>
   </body>
</html>
