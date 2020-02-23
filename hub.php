<!DOCTYPE html>
<html>
   <head>
	  <link rel="stylesheet" href="styles/style.css">
      <title>Evil CM</title>
      <link rel="icon" type="image/png" href="/logo.png">
   </head>
   <body class="flex" style="zoom: 255%;">
      <form action="gmaps.php" method="post" class="flex-item">
         <input type="submit" style="color: #4185FA; height: 40px" value="Google Maps">
      </form>
	  <br>
      <!-- FORMS -->
      <form action="cm.php" method="post" class="flex-item">
         <input type="submit" style="color: #5DC904; height: 40px" value="CellMapper">
      </form>
	  <br>
      <!-- FORMS -->
      <?php
      if (isset($_GET['lat'])) {
      } else {
        ?>
      <form action="findlater.php" method="post" class="flex-item">
         <input type="submit" style="color: #F80000; height: 40px" value="Find Later">
      </form>   <br>
	  <form action="dbinfo.php" method="post" class="flex-item">
         <input type="submit" style="color: #e8db4a; height: 40px" value="Database Lookup">
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

 if (isset($_POST['field1'])) {
    $fh = fopen($path,"a+");
    $string = $_POST['field1'];
    fwrite($fh,$string); // Write information to the file
    fclose($fh); // Close the file
 } else {
   $lat = $_GET['lat'];
   $long = $_GET['long'];
   $fh = fopen($path,"a+");
   $string = "$lat,$long";
   fwrite($fh,$string); // Write information to the file
   fclose($fh); // Close the file
   $hide = true;
 }

 system("cmd /c convert.bat");
?>
