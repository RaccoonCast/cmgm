<!DOCTYPE html>
<html lang="en">
   <head>
   <link href='https://fonts.googleapis.com/css?family=Manrope' rel='stylesheet'>
	  <?php include "functions.php"?>
   </head>
   <body>
      <form action="convert.php" method="get" autocomplete="off">
         <p class="same-line">Enter Location:<p class="small-text same-line" onclick="alert('What can you enter?\n\nCellMapper.NET URLs\n\Latitude,Longitude \(38.896,-77.036)\nGoogle Maps URLS\n\Building Address\nBuilding Name\nDMS Coordinates')">?</p></p>
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
