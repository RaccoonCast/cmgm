<!DOCTYPE html>
<html lang="en">
   <head>
	  <?php
     include "functions.php"?>
   </head>
   <body>
      <form action="convert.php" method="get" autocomplete="off">
         <p>Convert cm/gm/latlong</p>
        <!-- <input class="fakeinput" type="text" name="data" value="<?php echo $latitude . ',' . $longitude . ',' . $carrier . ' '?>" id="txtresult" required> -->
         <input type="text" name="data" id="txtresult" required>
         <input type="submit" class="submitbutton" style="color: #A8B2B1;"  value="Submit">
		     <input type="button" class="submitbutton" value="Locate" style="color: #A8B2B1;" onclick="myFunction()">
      </form>
      <script src="js/index.js"></script>
	 <a id="findlater">FindlaterDB</a>
   </body>
</html>
