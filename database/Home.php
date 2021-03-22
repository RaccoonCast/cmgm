<!DOCTYPE html>
<html lang="en">
   <head>
     <?php include "../functions.php"; ?>
   </head>
   <body>
     <div class="flex">
   <?php
     include "../includes/functions/prettyInfoDisplay.php";
     //The buttons
     hubLatLong("Form.php","#F80000","Form","_self");
     hubLatLong("Search.php","#e31bdc","Search","_self");
     hubLatLong("Map.php","#E9A623","Map","_blank");
     // hubLatLong("database/CLI.php","#29b30b","CLI Beta","_blank");
     // hubLatLong("..\comparison-mode/index.php","#6aa3a3","Comparison Mode","_self");
     hubLatLong("..\Home.php","#00000","Back","_self");
     echo '</div>';
     include "includes/footer.php";
?>
   </body>
</html>
