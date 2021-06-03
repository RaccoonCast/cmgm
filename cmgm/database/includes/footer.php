<?php
   if(empty($zip)) $zip = null;
   if(empty($city)) $city = null;
   if(empty($state)) $state = null;
   if(empty($address)) $address = null;
   $latitude = substr("$latitude", 0, 9);
   $longitude = substr("$longitude", 0, 10);
   $pmlink = "../Home.php?latitude=$latitude&longitude=$longitude&address=$address&zip=$zip&city=$city&state=$state&permit_redirect=true";
   $cmlink = "../goto.php?goto_page=CellMapper&latitude=$latitude&longitude=$longitude";
   $db_map_link = "../goto.php?goto_page=Map&latitude=$latitude&longitude=$longitude";
   $db_list_link = "../goto.php?goto_page=DB&latitude=$latitude&longitude=$longitude";
   // $gm2link = "../goto.php?goto_page=LA Permit Map&latitude=$latitude&longitude=$longitude";
   $gmlink = "../goto.php?goto_page=Google%20Maps&latitude=$latitude&longitude=$longitude";
   $uplink = "../goto.php?goto_page=Upload&latitude=$latitude&longitude=$longitude";
   ?>
   <div id="footerContainer">
   <footer>
     <a class="footerlink footer_link_first" target="_blank" href="<?php echo $cmlink?>">CellMapper</a>
     <a class="footerlink" target="_blank" href="<?php echo $db_list_link?>">DB</a>
     <a class="footerlink" target="_blank" href="<?php echo $db_map_link?>">Map</a>
     <a class="footerlink" target="_blank" href="<?php echo $gmlink?>">Google Maps</a>
     <a class="footerlink" target="_blank" href="<?php echo $uplink?>">Upload</a>
     <!-- <a class="footerlink" target="_blank" href="<?php // echo $gm2link?>">Permits Map</a> -->
  </footer>
  </div>
