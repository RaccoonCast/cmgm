<?php
   if(empty($zip)) $zip = null;
   if(empty($city)) $city = null;
   if(empty($state)) $state = null;
   if(empty($address)) $address = null;
   @$latitude = substr("$latitude", 0, 9);
   @$longitude = substr("$longitude", 0, 10);
   $pmlink = "../Home.php?latitude=$latitude&longitude=$longitude&address=$address&zip=$zip&city=$city&state=$state&permit_redirect=true";
   if (!isset($carrier)) $carrier = $default_carrier;
   if ($carrier == "Unknown") { $cm_carrier = $default_carrier; } else { $cm_carrier = $carrier; }
   function cellmapperLink2 ($cm_latitude,$cm_longitude,$cm_zoom,$cm_carrier,$cm_netType,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$ppT,$ppL) {
     if ("$cm_carrier" == "T-Mobile") $beginning = "MCC=310&MNC=260&";
     if ("$cm_carrier" == "Sprint") $beginning = "MCC=310&MNC=120&";
     if ("$cm_carrier" == "ATT") $beginning = "MCC=310&MNC=410&";
     if ("$cm_carrier" == "Verizon") $beginning = "MCC=311&MNC=480&";
     if ("$cm_carrier" == "Dish") $beginning = "MCC=313&MNC=340&";
     if ("$cm_carrier" == "Unknown") return $ppT;
     if (empty($cm_netType)) $cm_netType = "LTE";
     return 'https://www.cellmapper.net/map?'.$beginning.'type='.$cm_netType.'&latitude='.$cm_latitude.'&longitude='.$cm_longitude.'&zoom='.$cm_zoom.'&clusterEnabled='.$cm_groupTowers.'&showTowerLabels='.$cm_showLabels.'&showOrphans='.$cm_showLowAcc;
   }
   if ($carrier == "Dish") { $footer_rat = "NR"; } else { $footer_rat = "LTE"; }
   $cmlink = cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,$footer_rat,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,@$LTE_1,@$region_lte);
   $db_map_link = "Map.php?latitude=" . $latitude . "&longitude=" . $longitude . "&zoom=18&carrier=" . @$carrier;
   $db_list_link = "DB.php?latitude=$latitude&longitude=$longitude&limit=500";
   $gmlink = "https://www.google.com/maps/@?api=1&map_action=map&center=$latitude,$longitude";
   $uplink = "Upload.php?latitude=$latitude&longitude=$longitude";
   ?>
   <div id="footerContainer">
   <footer>
     <a class="footerlink footer_link_first" target="_blank" href="<?php echo $domain_with_http?>">Home</a>
     <a class="footerlink" target="_blank" href="<?php echo $db_map_link?>">Map</a>
     <a class="footerlink" target="_blank" href="<?php echo $cmlink?>">CellMapper</a>
     <a class="footerlink" target="_blank" href="<?php echo $uplink?>">Upload</a>
     <!-- <a class="footerlink" target="_blank" href="<?php // echo $gm2link?>">Permits Map</a> -->
  </footer>
  </div>
