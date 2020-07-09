<?php
   $latitude = substr("$latitude", 0, 9);
   $longitude = substr("$longitude", 0, 10);
   $pmlink = "../Permits.php?latitude=$latitude&longitude=$longitude";
   $dblink = "DatabaseMap.php?latitude=$latitude&longitude=$longitude";
   ?>
   <div class="footer">
   <a href="<?php echo $pmlink?>" target="_blank">Permits</a>
   <a href="<?php echo $dblink?>" target="_blank">DatabaseMap</a>
 </div>
