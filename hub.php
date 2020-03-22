<?php
   $latitude = $_GET['latitude'];
   $longitude = $_GET['longitude'];
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <?php include "functions.php"?>
   </head>
   <body class="flex">
     <?php
     if (empty($latitude)) {
       header('Location: https://cmgm.gq/cm/');
     } else {
       hubLatLong("findlaterdb.php","#e8db4a","FindlaterDB");
       hubLatLong("cm.php","#5DC904","CellMapper");
       hubLatLong("gmaps.php","#4185FA","Google Maps");
       hubLatLong("findlater.php","#F80000","Findlater");
     }
?>
<script src="js/copy.js"></script>
<form method="get">
   <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
   <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
   <input onclick="myFunction2()" type="submit" class="submitbutton" value="Copy">
</form>
<?php hubLatLong("findlatermap.php","#E9A623","Map");?>
	        <!-- <form action="database.php" method="post" style="display: inline" class="flex-item">
         <input type="submit" style="color: #E9A623" value="Database">
      </form> -->
      <!-- FORMS -->
   </body>
</html>
