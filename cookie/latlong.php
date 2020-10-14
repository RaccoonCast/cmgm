<!DOCTYPE html>
<html lang="en">
   <head>
	  <?php
    // if latitude & longitude & carrier are set in URL bar create PHP variable with data
     if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
     if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];
     if (isset($_GET['carrier'])) $carrier = $_GET['carrier'];
     include "../functions.php"?>
   </head>
   <body>
     <script src="..\js\latlong-cookie.js"></script>
      <form action="latlong.php" method="get" autocomplete="off">
         <p>Latitude: </p>
         <input type="text" name="latitude" id="latitude" required>
         <p>Longitude: </p>
         <input type="text" name="longitude" id="longitude" required>
         <p>Carrier: </p>
         <select class="fakeinput dropdown" autocomplete="on" name="carrier">
        <option value="T-Mobile">T-Mobile</option>
        <option value="ATT">AT&T</option>
        <option value="Verizon">Verizon</option>
        <option value="Sprint">Sprint</option>
         </select>
         <input type="button" class="submitbutton" onclick="myFunction();" style="color: #00000;"  value="Locate">
         <input type="submit" class="submitbutton" style="color: #00000;"  value="Submit">
      </form>
      <?php if (isset($_GET['latitude']) && isset($_GET['longitude']) && isset($_GET['carrier'])) { ?>
      <script>
      // Create the coookies
      document.cookie = "latitude=<?php echo $latitude;?>; expires=Thu, 18 Dec 2030 12:00:00 UTC; path=/";
      document.cookie = "longitude=<?php echo $longitude;?>; expires=Thu, 18 Dec 2030 12:00:00 UTC; path=/";
      document.cookie = "carrier=<?php echo $carrier;?>; expires=Thu, 18 Dec 2030 12:00:00 UTC; path=/";
      </script>
    <?php
  echo '<meta http-equiv="refresh" content="0;URL=../?refresh" /> ';
} ?>
   </body>
</html>
