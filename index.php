<!DOCTYPE html>
<html lang="en">
   <head>
	  <?php include "functions.php"?>
      <title>Evil CM</title>
	  <link rel="apple-touch-icon" href="images/icons-192.png">
	  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
	  <meta name="theme-color" content="#fff"/>
	  <link rel="manifest" href="pwa-manifest.json">
      <link rel="icon" type="image/png" href="/logo.png">
	  <link rel="stylesheet" href="styles/style.css">
   </head>
   <body>
		<?php
if(isMobile()){
	?>
	<div>
<?php }
else {
	?>
   <div>
<?php
}
?>
      <form action="hub.php" method="post" autocomplete="off">
         <p style="font-size: 20px;">Convert cm/gm/latlong</p>
         <input type="text" style="height: 30px" name="field1" id="txtresult" required>
         <input type="submit" style="color: #A8B2B1; height: 35px"  value="Submit">
		 <input type="button" value="Locate" style="color: #A8B2B1; height: 35px" onclick="myFunction()">
         <input type="hidden" name="redirect" value="index.html">
      </form>
	  <script src="index.js"></script>
	  </div>
	  <a style="text-align: center" href="dbinfo.php">FindlaterDB</a>
   </body>
</html>
