<?php

$latitude = $_GET['latitude'];
$longitude = $_GET['longitude'];
$carrier = $_GET['carrier'];
if ("$carrier" == "T-Mobile") {
  $beginning = "MCC=310&MNC=260&";
} elseif ("$carrier" == "Sprint") {
  $beginning = "MCC=310&MNC=120&";
} elseif ("$carrier" == "ATT") {
  $beginning = "MCC=310&MNC=410&";
} elseif ("$carrier" == "Verizon&") {
  $beginning = "MCC=311&MNC=480&";
}
$end = "map?$beginning" . "latitude=$latitude&longitude=$longitude&zoom=18&showTowerLabels=false";
header('Location: https://www.cellmapper.net/' . $end);
?>
