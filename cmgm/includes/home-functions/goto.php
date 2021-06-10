<?php
function function_goto($latitude,$longitude,$carrier,$address,$zip,$city,$state,$goto_page,$conv_type) {
if(empty($type)) $type = "LTE";
if(empty($carrier)) $carrier = null;
if(empty($zip)) $zip = null;
if(empty($city)) $city = null;
if(empty($state)) $state = null;
if(empty($address)) $address = null;

if (isset($_GET['goto'])) $goto = @$_GET['goto'];
if (isset($_POST['goto'])) $goto = @$_POST['goto'];
if (isset($_GET['data'])) $data = @$_GET['data'];
if (isset($_POST['data'])) $data = @$_POST['data'];

if ($goto_page == "CellMapper") {
  if ("$carrier" == "T-Mobile") $beginning = "MCC=310&MNC=260&";
  if ("$carrier" == "Sprint") $beginning = "MCC=310&MNC=120&";
  if ("$carrier" == "ATT") $beginning = "MCC=310&MNC=410&";
  if ("$carrier" == "Verizon") $beginning = "MCC=311&MNC=480&";
  if ("$type" == "LTE") $beginning = $beginning . "type=LTE&";
  if ("$type" == "NR") $beginning = $beginning . "type=NR&";
  return "https://www.cellmapper.net/map?$beginning" . "latitude=$latitude&longitude=$longitude&zoom=18&showTowerLabels=false";
}
if ($goto_page == "Beta") {
  if ("$carrier" == "T-Mobile") $beginning = "310/260";
  if ("$carrier" == "Sprint") $beginning = "310/120";
  if ("$carrier" == "ATT") $beginning = "310/410";
  if ("$carrier" == "Verizon") $beginning = "311/480";
  if ("$type" == "LTE") $beginning = $beginning . "/LTE";
  if ("$type" == "NR") $beginning = $beginning . "/NR";
  return "https://www.cellmapper.net/testmap/map/$beginning" . "?lat=$latitude&lng=$longitude&z=18";
}
if ($goto_page == "Google Maps") return "https://www.google.com/maps/@?api=1&map_action=map&center=$latitude,$longitude&zoom=20&basemap=satellite";
if ($goto_page == "Street View") return "https://www.google.com/maps?layer=c&cbll=$latitude,$longitude";
if ($goto_page == "Database") $goto_page_URL = "/database/Home.php?";
if ($goto_page == "Map") $goto_page_URL = "/database/Map.php?back=Home&";
if ($goto_page == "Form") $goto_page_URL = "/database/Form.php?";
if ($goto_page == "DB") $goto_page_URL = "/database/DB.php?";
if ($goto_page == "Search") $goto_page_URL = "/database/Search.php?";
if ($goto_page == "Back" OR $goto_page == "Home") $goto_page_URL = "/?";
if ($goto_page == "Permits") $goto_page_URL = "permits.php?";
if ($goto_page == "Upload") $goto_page_URL = "/database/Upload.php?";
if ($goto_page == "Settings") $goto_page_URL = "includes/useridsys/Settings.php?";

$suffix_part_c = "&address=$address&zip=$zip&city=$city&state=$state";
$suffix_part_b = "&carrier=$carrier";
$suffix_part_a = "latitude=$latitude&longitude=$longitude";

if ($goto_page == "DB") $suffix_part_c = null;
if ($goto_page == "Map") { $suffix_part_c = null; }
if ($goto_page == "Search") $suffix_part_c = null;
if ($goto_page == "Upload") { $suffix_part_b = null; $suffix_part_c = null; }

return "$goto_page_URL" . "$suffix_part_a" . "$suffix_part_b" . "$suffix_part_c";
}
?>
