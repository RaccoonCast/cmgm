<!DOCTYPE html>
<html lang="en">
   <head>
	  <?php
     if (isset($_GET['key'])) $key = $_GET['key'];
     include "functions.php"?>
   </head>
   <body>
      <form action="cookie.php" method="get" autocomplete="off">
         <p>Google Maps API Key: </p>
         <input type="text" name="key" id="txtresult" required>
         <input type="submit" class="submitbutton" style="color: #A8B2B1;"  value="Submit">
      </form>
      <script>
      document.cookie = "api_key=<?php echo $key;?>; expires=Thu, 18 Dec 2020 12:00:00 UTC; path=/";
      </script>
   </body>
</html>
