<?php
   $latitude = substr("$latitude", 0, 9);
   $longitude = substr("$longitude", 0, 10);
   $pmlink = "../Hub.php?latitude=$latitude&longitude=$longitude&address=$address&zip=$zip&city=$city&state=$state&permit_redirect=true";
   $dblink = "DatabaseMap.php?latitude=$latitude&longitude=$longitude&zoom=19";
   $gmlink = "../gmaps.php?latitude=$latitude&longitude=$longitude";
   ?>
   <div class="footer">
   <script src="../js/permit-copy.js"></script>
   <a target="_blank" onclick="copyToClipboard('<?php echo $address;?>')" href="<?php echo $pmlink?>">Permits</a>
   <a target="_blank" href="<?php echo $dblink?>" target="_blank">DatabaseMap</a>
   <a target="_blank" href="<?php echo $gmlink?>" target="_blank">Google Maps</a>
 </div>
