<!DOCTYPE html>
<html>
   <head>
      <title>Evil CM</title>
   </head>
   <body class="flex" style="zoom: 255%;">
     <?php include "functions.php"?>
      <form action="gmaps.php" method="post" class="flex-item">
        <form action="dbinfo.php" method="post" class="flex-item">
             <input type="submit" style="color: #e8db4a; height: 40px" value="Database Search">
          </form>
         <input type="submit" style="color: #4185FA; height: 40px" value="Google Maps">
      </form>
      <!-- FORMS -->
      <form action="cm.php" method="post" class="flex-item">
         <input type="submit" style="color: #5DC904; height: 40px" value="CellMapper">
      </form>
      <!-- FORMS -->
      <?php
      if (isset($_GET['lat'])) { ?>
        <?php

      } else {
        ?>
      <form action="findlater.php" method="post" class="flex-item">
         <input type="submit" style="color: #F80000; height: 40px" value="Find Later">
      </form>
      <script src="js\copy.js"></script>
      <form method="post" class="flex-item">
         <input onclick="myFunction2()" type="submit" id="style" style="color: #050400!important; height: 40px" value="Copy">
      </form> <?php
}
?>
	        <!-- <form action="database.php" method="post" style="display: inline" class="flex-item">
         <input type="submit" style="color: #E9A623" value="Database">
      </form> -->
      <!-- FORMS -->
   </body>
</html>
<?php
	$path = 'dustbin\data.txt';
	$latitude = 'dustbin\latitude.txt';
	$longitude = 'dustbin\longitude.txt';
	$carrier = 'dustbin\carrier.txt';
   if (file_exists($path)) { unlink($path) or die("Couldn't delete file"); }
   if (file_exists($latitude)) { unlink($latitude) or die("Couldn't delete file"); }
   if (file_exists($longitude)) { unlink($longitude) or die("Couldn't delete file"); }
   if (file_exists($carrier)) { unlink($carrier) or die("Couldn't delete file"); }

 if (isset($_GET['lat'])) {
   $lat = $_GET['lat'];
   $long = $_GET['long'];
   $fh = fopen($path,"a+");
   $string = "$lat,$long";
   fwrite($fh,$string); // Write information to the file
   fclose($fh); // Close the file
   $hide = true;
    system("cmd /c convert.bat");
 } else {
   $fh = fopen($path,"a+");
   $string = $_POST['field1'];
   fwrite($fh,$string); // Write information to the file
   fclose($fh); // Close the file
   system("cmd /c convert.bat");
 }
?>
