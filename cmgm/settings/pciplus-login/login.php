<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="en">
   <head>
     <title>CMGM - Settings</title>
     <?php
     include "../functions.php";

    $temporary_token = rand(0, 9999);
    $pciplusIdent = $_POST['pciplusIdent'];
    $sql_query = "UPDATE userID SET pciplus_token '$pciplusIdent' AND pciplus_temp_token = '$temporary_token' WHERE userID = '$userID'";

     ?>
   </head>
   <body>
    <h3>You're in!</h3>
    <br>
    <p>Your token is <a onclick="copyToClipboard('<?php echo $temporary_token ?>')" href="#"><?php echo $temporary_token?></p>
   </body>
</html>
