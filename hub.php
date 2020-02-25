<?php
   $latitude = $_GET['latitude'];
   $longitude = $_GET['longitude'];
?>
<!DOCTYPE html>
<html>
   <head>
      <title>Evil CM</title>
   </head>
   <body class="flex">
     <?php include "functions.php"?>
<?php hubLatLong("dbinfo.php","#e8db4a","Database Search");?>
<?php hubLatLong("gmaps.php","#4185FA","Google Maps");?>
<?php hubLatLong("cm.php","#5DC904","CellMapper");?>
<?php hubLatLong("findlater.php","#F80000","Find later");?>
<?php hubLatLong("copy.php","#000","Copy");?>
	        <!-- <form action="database.php" method="post" style="display: inline" class="flex-item">
         <input type="submit" style="color: #E9A623" value="Database">
      </form> -->
      <!-- FORMS -->
   </body>
</html>
