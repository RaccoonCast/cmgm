<?php
$goto_page = $_GET['goto_page'];
if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];

if ($goto_page == 'gm') {
$url = "https://www.google.com/maps/@?api=1&map_action=map&center=$latitude,$longitude&zoom=20&basemap=satellite";
} elseif($goto_page == "cm") {
  $carrier = $_GET['carrier'];
  if ("$carrier" == "T-Mobile") $beginning = "MCC=310&MNC=260&";
  if ("$carrier" == "Sprint") $beginning = "MCC=310&MNC=120&";
  if ("$carrier" == "ATT") $beginning = "MCC=310&MNC=410&";
  if ("$carrier" == "Verizon&") $beginning = "MCC=311&MNC=480&";
  $url = "https://www.cellmapper.net/map?$beginning" . "latitude=$latitude&longitude=$longitude&zoom=18&showTowerLabels=false";
} elseif($goto_page == "permit-map") {
  $url = "https://www.google.com/maps/d/u/0/viewer?hl=en&mid=1_69xTUt-g1MITj7lMs1oaCwKl1YuQ4nh&ll=$latitude%2C$longitude&z=18";
}
header('Location: ' . $url);
?>
