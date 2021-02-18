<?php
   $latitude = substr("$latitude", 0, 9);
   $longitude = substr("$longitude", 0, 10);
   $pmlink = "../Home.php?latitude=$latitude&longitude=$longitude&address=$address&zip=$zip&city=$city&state=$state&permit_redirect=true";
   $dblink = "DatabaseMap.php?latitude=$latitude&longitude=$longitude&zoom=19";
   $gm2link = "https://www.google.com/maps/d/u/0/viewer?hl=en&mid=1_69xTUt-g1MITj7lMs1oaCwKl1YuQ4nh&ll=$latitude%2C$longitude&z=19";
   $gmlink = "../gmaps.php?latitude=$latitude&longitude=$longitude";
   ?>
   <script src="../js/permit-copy.js"></script>

   <footer>
     <a class="footerlink" onclick="copyToClipboard('<?php echo $address;?>')" href="<?php echo $pmlink?>">Permits</a>
     <a class="footerlink" target="_blank" href="<?php echo $dblink?>">Maps</a>
     <a class="footerlink" target="_blank" href="<?php echo $gmlink?>">GMaps</a>
     <a class="footerlink" target="_blank" href="<?php echo $gm2link?>">Permits Map</a>
  </footer>
