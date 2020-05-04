<!DOCTYPE html>
<html lang="en">
   <head>
      <?php include "functions.php"?>
   </head>
   <body class="flex">
     <?php
     if (empty($latitude)) {
       header('Location: https://cmgm.gq/');
     } else {
       hubLatLong("HubFindlater.php","#F80000","Findlater");
       hubLatLong("HubDatabase.php","#00ccff","Database");
       hubLatLong("cm.php","#5DC904","CellMapper");
       hubLatLong("gmaps.php","#4185FA","Google Maps");
       hubLatLong("HubPermits.php","#00e3e0","Permits");
     }
?>
<script src="js/copy.js"></script>
<form method="get">
   <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
   <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
   <input onclick="myFunction2()" style="color: #ffb700" type="submit" class="submitbutton" value="Copy">
</form>
<form action=\ method=get>
  <input type="hidden" name="latitude" value="<?php echo $latitude?>">
  <input type="hidden" name="longitude" value="<?php echo $longitude?>">
  <input type="hidden" name="carrier" value="<?php echo $carrier?>">
  <input type="submit" class="submitbutton" value='Back' >
</form>
   </body>
</html>
