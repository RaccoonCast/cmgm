<?php
   if(empty($zip)) $zip = null;
   if(empty($city)) $city = null;
   if(empty($state)) $state = null;
   if(empty($address)) $address = null;
   @$latitude = substr("$latitude", 0, 9);
   @$longitude = substr("$longitude", 0, 10);
   $pmlink = "../Home.php?latitude=$latitude&longitude=$longitude&address=$address&zip=$zip&city=$city&state=$state&permit_redirect=true";
   if (!isset($carrier)) $carrier = $default_carrier;
   if ("$carrier" == "T-Mobile") $beginning = "MCC=310&MNC=260&";
   if ("$carrier" == "Sprint") $beginning = "MCC=310&MNC=120&";
   if ("$carrier" == "ATT") $beginning = "MCC=310&MNC=410&";
   if ("$carrier" == "Verizon") $beginning = "MCC=311&MNC=480&";
   $cmlink = "https://www.cellmapper.net/map?$beginning"  . "type=LTE&latitude=$latitude&longitude=$longitude&zoom=18&showTowerLabels=false";
   $db_map_link = "Map.php?latitude=" . $latitude . "&longitude=" . $longitude . "&zoom=18&carrier=" . @$carrier;
   $db_list_link = "DB.php?latitude=$latitude&longitude=$longitude&limit=500";
   // $gm2link = "../goto.php?goto_page=LA Permit Map&latitude=$latitude&longitude=$longitude";
   $gmlink = "https://www.google.com/maps/@?api=1&map_action=map&center=$latitude,$longitude";
   $uplink = "Upload.php?latitude=$latitude&longitude=$longitude";
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
