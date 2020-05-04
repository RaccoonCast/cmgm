<!DOCTYPE html>
<html lang="en">
   <head>
   </head>
   <body>
     <?php
     if (isset($_GET['key'])) $key = $_GET['key'];
     ?>
     <script>
     document.cookie = "api_key=<?php echo $key;?>; expires=Thu, 18 Dec 2020 12:00:00 UTC; path=/";
     </script>
     <?php echo "Cookie: $key"?>
     <br>
     <br>

     <a href="/">Home</a>
   </body>
</html>
