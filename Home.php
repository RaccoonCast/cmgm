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
        <?php
        if (isset($_COOKIE["latitude"]) | isset($_COOKIE["longitude"])) { ?>
          <input type="text" name="data" id="txtresult"> <?php
        } else { ?> <input type="text" name="data" id="txtresult" required> <?php
      } ?>


         <input type="submit" class="submitbutton" style="color: #000000;"  value="Submit">
		     <input type="button" class="submitbutton" value="Locate" style="color: #00000;" onclick="myFunction()">
      </form>
      <script src="js/index.js"></script>
	 <a id="findlater">FindlaterDB</a>
   </body>
</html>
