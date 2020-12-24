<?php
   $latitude = substr("$latitude", 0, 9);
   $longitude = substr("$longitude", 0, 10);
   $pmlink = "../Hub.php?latitude=$latitude&longitude=$longitude&address=$address&zip=$zip&city=$city&state=$state&permit_redirect=true";
   $dblink = "DatabaseMap.php?latitude=$latitude&longitude=$longitude&zoom=19";
   $gmlink = "../gmaps.php?latitude=$latitude&longitude=$longitude";
   ?>
   <script src="../js/permit-copy.js"></script>

   <footer>
     <a class="footerlink" onclick="copyToClipboard('<?php echo $address;?>')" href="<?php echo $pmlink?>">Permits</a>
     <a class="footerlink" target="_blank" href="<?php echo $dblink?>">Database Map</a>
     <a class="footerlink" target="_blank" href="<?php echo $gmlink?>">Google Maps</a>
  </footer>
 </div>
