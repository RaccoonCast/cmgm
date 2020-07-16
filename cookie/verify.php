<!DOCTYPE html>
<html lang="en">
   <head>
	  <?php
     if (isset($_GET['usercheck_cookie'])) $usercheck_cookie = $_GET['usercheck_cookie'];
     include "../functions.php";
     ?>
   </head>
   <body>
      <form action="verify.php" method="get" autocomplete="off">
         <p>Passkey? </p>
         <input type="text" name="usercheck_cookie" id="txtresult" required>
         <input type="submit" class="submitbutton" style="color: #00000;"  value="Submit">
      </form>
      <?php if (isset($_GET['usercheck_cookie'])) { ?>
      <script>
      document.cookie = "usercheck_cookie=<?php echo $usercheck_cookie;?>; expires=Thu, 18 Dec 2030 12:00:00 UTC; path=/";
      </script>
    <?php
    echo '<meta http-equiv="refresh" content="0;URL=../" /> ';
  } ?>
   </body>
</html>
