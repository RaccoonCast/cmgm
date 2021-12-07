<?php
function function_goto($latitude,$longitude,$carrier,$address,$zip,$city,$state,$goto_page,$conv_type,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,$cm_netType) {
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
  $var = cellmapperLink($latitude,$longitude,$cm_zoom,$carrier,$cm_netType,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc);
  return $var;
}
if ($goto_page == "Beta") {
  if ("$carrier" == "T-Mobile") $beginning = "310/260";
  if ("$carrier" == "Sprint") $beginning = "310/120";
  if ("$carrier" == "ATT") $beginning = "310/410";
  if ("$carrier" == "Verizon") $beginning = "311/480";
  if ("$cm_netType" == "LTE") $beginning = $beginning . "/LTE";
  if ("$cm_netType" == "NR") $beginning = $beginning . "/NR";
  return "https://www.cellmapper.net/testmap/map/$beginning" . "?lat=$latitude&lng=$longitude&z=18";
}
if ($goto_page == "Google Maps") return "https://www.google.com/maps/@?api=1&map_action=map&center=$latitude,$longitude&zoom=20&basemap=satellite";
if ($goto_page == "Street View") return "https://www.google.com/maps?layer=c&cbll=$latitude,$longitude";
if ($goto_page == "Maprad") return "https://maprad.io/us/search/coordinates/200/$latitude,$longitude?source=US";

if ($goto_page == "Map") $goto_page_URL = "/database/Map.php?back=Home&";
if ($goto_page == "Form") $goto_page_URL = "/database/Edit.php?new&";
if ($goto_page == "Search") $goto_page_URL = "/database/Search.php?";
if ($goto_page == "Back" OR $goto_page == "Home") $goto_page_URL = "/?";
if ($goto_page == "Permits") $goto_page_URL = "../permits.php?";
if ($goto_page == "Upload") $goto_page_URL = "/database/Upload.php?";
if ($goto_page == "Settings") $goto_page_URL = "settings/";
if ($goto_page == "AntennaSearch") return "http://www.antennasearch.com/HTML/search/search.php?address=$latitude,$longitude";

if (!isset($suffix_part_a)) $suffix_part_a = "latitude=$latitude&longitude=$longitude";
if (!isset($suffix_part_b)) $suffix_part_b = "&carrier=$carrier";
if (!isset($suffix_part_c)) $suffix_part_c = "&address=$address&zip=$zip&city=$city&state=$state";

if ($goto_page == "Map") $suffix_part_c = null;
if ($goto_page == "Search") $suffix_part_c = null;
if ($goto_page == "Upload") { $suffix_part_b = null; $suffix_part_c = null; }

return "$goto_page_URL" . "$suffix_part_a" . "$suffix_part_b" . "$suffix_part_c";
}
?>
