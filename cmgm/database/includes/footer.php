<?php
   include "../js/getCmLink.js.php";
   include_once "../includes/misc-functions/cm_linkgen.php";
   if(empty($zip)) $zip = null;
   if(empty($city)) $city = null;
   if(empty($county)) $county = null;
   if(empty($state)) $state = null;
   if(empty($address)) $address = null;
   @$latitude = substr("$latitude", 0, 9);
   @$longitude = substr("$longitude", 0, 10);
   $pmlink = "../Home.php?latitude=$latitude&longitude=$longitude&address=$address&zip=$zip&city=$city&county=$county&state=$state&permit_redirect=true";
   if (!isset($carrier)) $carrier = $default_carrier;
   if ($carrier == "Unknown") { $cm_carrier = $default_carrier; } else { $cm_carrier = $carrier; }
   if ($carrier == "Dish") { $footer_rat = "NR"; } else { $footer_rat = "LTE"; }
   $cmlink = cellmapperLink($latitude,$longitude,$cm_zoom,$carrier,$footer_rat,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,@$LTE_1,@$region_lte);
   $db_map_link = "Map.php?latitude=" . $latitude . "&longitude=" . $longitude . "&zoom=18&carrier=" . @$carrier;
   $db_list_link = "DB.php?latitude=$latitude&longitude=$longitude&limit=500";
   $gmlink = "https://www.google.com/maps/@?api=1&map_action=map&center=$latitude,$longitude";
   $uplink = "https://upload.cmgm.us";
   $funlink = "/fun/?limit=15";
   ?>
   <div id="footerContainer">
   <footer>
     <a class="footerlink" target="_blank" href="<?php echo $domain_with_http?>">Home</a>
     <a class="footerlink" target="_blank" href="<?php echo $db_map_link?>">Map</a>
     <a class="footerlink" href="#" onclick="window.open(getCmLink(), '_blank')">CellMapper</a>
     <a class="footerlink" target="_blank" href="<?php echo $uplink?>">Upload</a>
     <a class="footerlink" target="_blank" href="<?php echo $funlink?>">Stats</a>
     <!-- <a class="footerlink" target="_blank" href="<?php // echo $gm2link?>">Permits Map</a> -->
  </footer>
  </div>
