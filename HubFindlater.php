<!DOCTYPE html>
<html lang="en">
   <head>
     <?php include "functions.php";
     if(isMobile()){
       echo '<link rel="stylesheet" href="styles/Hub/mobile.css">';
     } else {
       echo '<link rel="stylesheet" href="styles/Hub/desktop.css">';
     }
     ?>
   </head>
   <body class="flex">
     <?php
     if (empty($latitude)) {
       header('Location: https://cmgm.gq/');
     } else {
       hubLatLong("findlater/findlater-form.php","#F80000","Findlater","_blank");
       hubLatLong("findlater/Search.php","#e31bdc","Search","_self");
       hubLatLong("findlater/FindlaterMap.php","#E9A623","Findlater Map","_blank");
       hubLatLong("\Hub.php","#00000","Back","_self");
     }
?>
<script src="js/copy.js"></script>
   </body>
</html>
