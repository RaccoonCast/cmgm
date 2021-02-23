<!DOCTYPE html>
<html lang="en">
   <head>
     <?php include "functions.php"; ?>
   </head>
   <body class="flex">
   <?php
     include "includes/functions/prettyInfoDisplay.php";
     //The buttons
     hubLatLong("database/Form.php","#F80000","Database","_self");
     hubLatLong("database/Search.php","#e31bdc","Search","_self");
     hubLatLong("database/Map.php","#E9A623","Database Map","_blank");
     hubLatLong("comparison-mode/index.php","#6aa3a3","Comparison Mode","_self");
     hubLatLong("..\Home.php","#00000","Back","_self");
?>
   </body>
</html>
