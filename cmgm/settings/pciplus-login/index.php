<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="en">
   <head>
     <script src="../../js/copyToClipboard.js"></script>
     <title>CMGM - PCI+ Login</title>
   </head>
   <body>
   <?php
     $titleOverride = "true";
     include "../../functions.php";
     $pciplusIdent = mysqli_real_escape_string($conn, $_GET['pciplusIdent']);

     if (!empty($pciplusIdent)) {
      $timestamp = time();

      $sql_query = "UPDATE userID SET pciplus_token = '$pciplusIdent', pciplus_timestamp='$timestamp' WHERE userID = '$userID'";
      
      mysqli_query($conn, $sql_query);
      ?>
            <script>
              window.close()
            </script>
      <?php
     } else {
      echo "<h3>Missing PCI+ plus session identifier.</h3>";
     }
     ?>
   </body>
</html>
