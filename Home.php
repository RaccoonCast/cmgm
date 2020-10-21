<!DOCTYPE html>
<html lang="en">
   <head>
   <link href='https://fonts.googleapis.com/css?family=Manrope' rel='stylesheet'>   
   <link rel="stylesheet" href="styles\home\desktop.css">
	  <?php include "functions.php"?>
   </head>
   <body>
      <form action="convert.php" method="get" autocomplete="off">
         <p>Convert cm/gm/latlong</p>
        <?php
        // Check if latitude & Longitude cookies exist - if they do don't require box to be filled - if they don't require box be filled
        if (isset($_COOKIE["latitude"]) | isset($_COOKIE["longitude"])) { ?>
          <input type="text" name="data" id="txtresult"> <?php
        } else { ?> <input type="text" name="data" id="txtresult" required> <?php
      } ?>
        <!--- Submit & Locate buttons -->
         <input type="submit" class="submitbutton" style="color: #000000;"  value="Submit">
		     <input type="button" class="submitbutton" value="Locate" style="color: #00000;" onclick="myFunction()">
      </form>
      <!-- Latitude & longitude get script -->
      <script src="js/index.js"></script>
<?php include "includes/home_footer.php"; ?>
   </body>
</html>
