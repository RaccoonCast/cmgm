<?php
// include "../../functions.php";
// $latitude = "34.0342";
// $longitude = "-118.343";
// $cm_zoom = "18";
// $cm_netType = "LTE";
function cellmapperLink ($cm_latitude,$cm_longitude,$cm_zoom,$cm_carrier,$cm_netType,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$item = "0",$tac = "0",$map_popup_link = false) {
  if (empty($tac)) $tac = "0";
  if ("$cm_carrier" == "T-Mobile") $beginning = "MCC=310&MNC=260&";
  if ("$cm_carrier" == "Sprint") $beginning = "MCC=310&MNC=120&";
  if ("$cm_carrier" == "ATT") $beginning = "MCC=310&MNC=410&";
  if ("$cm_carrier" == "Verizon") $beginning = "MCC=311&MNC=480&";
  if ("$cm_carrier" == "Dish") $beginning = "MCC=313&MNC=340&";
  if (empty($cm_carrier) OR $cm_carrier == "Unknown") $beginning = NULL;
  if (empty($cm_netType)) $cm_netType = "LTE";
  if ($map_popup_link == "true") return '<a target="_blank" href="https://www.cellmapper.net/map?' . $beginning . 'type=' . $cm_netType . '&latitude=' . $cm_latitude . '&longitude=' . $cm_longitude . '&zoom=' . $cm_zoom . '&clusterEnabled=' . $cm_groupTowers . '&showTowerLabels=' . $cm_showLabels . '&showOrphans=' . $cm_showLowAcc . '&ppT=' . $item . '&ppL=' . $tac . '">' . $item . '</a>';
  return "https://www.cellmapper.net/map?$beginning"  . "type=$cm_netType&latitude=$cm_latitude&longitude=$cm_longitude&zoom=$cm_zoom&clusterEnabled=$cm_groupTowers&showTowerLabels=$cm_showLabels&showOrphans=$cm_showLowAcc&ppT=$item&ppL=$tac";
}
// $var = cellmapperLink($latitude,$longitude,"18","T-Mobile","LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc);
// echo $var;
