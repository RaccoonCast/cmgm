<!DOCTYPE html>
<html lang="en">
   <head>
	  <?php
     if (isset($_GET['key'])) $key = $_GET['key'];
     include "../functions.php"?>
   </head>
   <body>
      <form action="gm-cookie.php" method="get" autocomplete="off">
         <p>Google Maps API Key: </p>
         <input type="text" name="key" id="txtresult" required>
         <input type="submit" class="submitbutton" style="color: #00000;"  value="Submit">
      </form>
      <?php if (isset($_GET['key'])) { ?>
      <script>
      document.cookie = "api_key=<?php echo $key;?>; expires=Thu, 18 Dec 2030 12:00:00 UTC; path=/";
      </script>
       <?php } ?>
   </body>
</html>
