<!DOCTYPE html>
<html lang="en">
   <head>
      <?php include "functions.php"?>
   </head>
   <body class="flex">
     <?php

     echo ' Latitude: ';
     echo $latitude;
     echo '<br> Longitude: ';
     echo $longitude;
       hubLatLong("HubFindlater.php","#F80000","Findlater","_self");
       hubLatLong("HubDatabase.php","#00ccff","Database","_self");
       hubLatLong("cm.php","#5DC904","CellMapper","_blank");
       hubLatLong("gmaps.php","#4185FA","Google Maps","_blank");
       ?>
       <form target="_blank" action="Hub.php" method="get">
         <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
         <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
         <input type="hidden" name="permit_redirect" value="true">
         <input type="submit" onclick="copyToClipboard(copy)" class="submitbutton" style="color: #00e3e0;" value="Permits">
       </form>
       <?php
       include "permits.php";
       hubLatLong("\Home.php","#00000","Back","_self");

?>
<script src="js/copy.js"></script>
   </body>
</html>
