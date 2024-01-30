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

     if (isset($pciplusIdent)) {
      $timestamp = time();

      $sql_query = "UPDATE userID SET pciplus_token = '$pciplusIdent', pciplus_timestamp='$timestamp' WHERE userID = '$userID'";
      echo $sql_query;
      mysqli_query($conn, $sql_query);
      ?>
            <h3>You're in!</h3>
      <?php
     } else {
      echo "<h3>Missing PCI+ plus session identifier.</h3>";
     }
     ?>
   </body>
</html>
