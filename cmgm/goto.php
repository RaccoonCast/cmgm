<?php
if(empty($type)) $type = "LTE";
if(empty($carrier)) $carrier = null;
if(empty($zip)) $zip = null;
if(empty($city)) $city = null;
if(empty($state)) $state = null;
if(empty($address)) $address = null;
if (isset($_GET['goto_page'])) $goto_page = $_GET['goto_page'];
if (!isset($latitude) && !isset($longitude)) foreach($_GET as $key => $value) ${$key} = $value;

if (empty($goto_page)) {
  echo 'ERROR: $goto_page variable was not set.';
  die();
}

if ($goto_page == "CellMapper") {
  $beginning = null;
  if (!isset($carrier)) $carrier = null;
  if ("$carrier" == "T-Mobile") $beginning = "MCC=310&MNC=260&";
  if ("$carrier" == "Sprint") $beginning = "MCC=310&MNC=120&";
  if ("$carrier" == "ATT") $beginning = "MCC=310&MNC=410&";
  if ("$carrier" == "Verizon") $beginning = "MCC=311&MNC=480&";
  if ("$type" == "LTE") $beginning = $beginning . "type=LTE&";
  if ("$type" == "NR") $beginning = $beginning . "type=NR&";
  $the_URL = "https://www.cellmapper.net/map?$beginning" . "latitude=$latitude&longitude=$longitude&zoom=18&showTowerLabels=false";
}
if ($goto_page == "Beta") {
  $beginning = null;
  if (!isset($carrier)) $carrier = null;
  if ("$carrier" == "T-Mobile") $beginning = "310/260";
  if ("$carrier" == "Sprint") $beginning = "310/120";
  if ("$carrier" == "ATT") $beginning = "310/410";
  if ("$carrier" == "Verizon") $beginning = "311/480";
  if ("$type" == "LTE") $beginning = $beginning . "/LTE";
  if ("$type" == "NR") $beginning = $beginning . "/NR";
  $the_URL = "https://www.cellmapper.net/testmap/map/$beginning" . "?lat=$latitude&lng=$longitude&z=18";
}
if ($goto_page == "Google Maps") $the_URL = "https://www.google.com/maps/@?api=1&map_action=map&center=$latitude,$longitude&zoom=20&basemap=satellite";
if ($goto_page == "Street View") $the_URL = "https://www.google.com/maps?layer=c&cbll=$latitude,$longitude";
if ($goto_page == "Database") $goto_page_URL = "database/Home.php?";
if ($goto_page == "Map") $goto_page_URL = "database/Map.php?";
if ($goto_page == "Form") $goto_page_URL = "database/Form.php?";
if ($goto_page == "DB") $goto_page_URL = "database/DB.php?";
if ($goto_page == "Search") $goto_page_URL = "database/Search.php?";
if ($goto_page == "Back" OR $goto_page == "Home") $goto_page_URL = "Home.php?";
if ($goto_page == "Permits") $goto_page_URL = "permits.php?";
if ($goto_page == "Upload") $goto_page_URL = "database/Upload.php?";
if ($goto_page == "Settings") $goto_page_URL = "includes/useridsys/Settings.php?";

$suffix_part_c = "&address=$address&zip=$zip&city=$city&state=$state";
$suffix_part_b = "&carrier=$carrier";
$suffix_part_a = "latitude=$latitude&longitude=$longitude";

if ($goto_page == "DB") $suffix_part_c = null;
if ($goto_page == "Map") { $suffix_part_c = null; }
if ($goto_page == "Search") $suffix_part_c = null;
if ($goto_page == "Upload") { $suffix_part_b = null; $suffix_part_c = null; }

if (isset($goto_page_URL)) {
$the_URL = "$goto_page_URL" . "$suffix_part_a" . "$suffix_part_b" . "$suffix_part_c";
echo '<meta http-equiv="refresh" content="0; url=' . $the_URL . '">';
} elseif(isset($the_URL)) {
  echo '<meta http-equiv="refresh" content="0; url=' . $the_URL . '">';
} else {
  echo 'ERROR: "' . $goto_page . '" does not have a specified redirect page.';
  die();
}
