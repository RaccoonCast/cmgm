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
       //The buttons
       hubLatLong("database/database-form.php","#F80000","Database","_self");
       hubLatLong("database/Search.php","#e31bdc","Search","_self");
       hubLatLong("database/DatabaseMap.php","#E9A623","Database Map","_blank");
       hubLatLong("comparison-mode/index.php","#6aa3a3","Comparison Mode","_self");
       hubLatLong("\Home.php","#00000","Back","_self");
     }
?>
<!-- Put latitude & longitude & carrier & zoom from URL bar into next form automatically -->
<script src="js/copy.js"></script>
   </body>
</html>
