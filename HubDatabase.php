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
       hubLatLong("database/database-form.php","#F80000","Database");
       hubLatLong("database/Search.php","#e31bdc","Search");
       hubLatLong("database/DatabaseMap.php","#E9A623","Database Map");
     }
?>
<form action=\Hub.php method=get>
  <input type="hidden" name="latitude" value="<?php echo $latitude?>">
  <input type="hidden" name="longitude" value="<?php echo $longitude?>">
  <input type="hidden" name="carrier" value="<?php echo $carrier?>">
  <input type="submit" class="submitbutton" value='Back' >
</form>
<script src="js/copy.js"></script>

	        <!-- <form action="database.php" method="post" style="display: inline" class="flex-item">
         <input type="submit" style="color: #E9A623" value="Database">
      </form> -->
      <!-- FORMS -->
   </body>
</html>
