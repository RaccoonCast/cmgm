<!DOCTYPE html>
<html lang="en">
   <head>
	  <?php include "functions.php"?>
      <title>Evil CM</title>
   </head>
   <body>
      <form action="hub.php" method="post" autocomplete="off">
         <p>Convert cm/gm/latlong</p>
         <input class="fakeinput" type="text" name="field1" id="txtresult" required>
         <input type="submit" class="submitbutton" style="color: #A8B2B1;"  value="Submit">
		     <input type="button" class="submitbutton" value="Locate" style="color: #A8B2B1;" onclick="myFunction()">
         <input type="hidden" name="redirect" value="index.html">
      </form>
      <script src="index.js"></script>
	  <a href="dbinfo.php">FindlaterDB</a>
   </body>
</html>
