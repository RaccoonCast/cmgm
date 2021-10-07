<?php
   if(empty($zip)) $zip = null;
   if(empty($city)) $city = null;
   if(empty($state)) $state = null;
   if(empty($address)) $address = null;
   @$latitude = substr("$latitude", 0, 9);
   @$longitude = substr("$longitude", 0, 10);
   $pmlink = "../Home.php?latitude=$latitude&longitude=$longitude&address=$address&zip=$zip&city=$city&state=$state&permit_redirect=true";
   if (!isset($carrier)) $carrier = $default_carrier;
   $cmlink = cellmapperLink($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc);
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
