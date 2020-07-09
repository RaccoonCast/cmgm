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
       hubLatLong("Permits.php","#00e3e0","Permits","_blank");
       hubLatLong("\Home.php","#00000","Back","_self");
?>
<script src="js/copy.js"></script>
   </body>
</html>
