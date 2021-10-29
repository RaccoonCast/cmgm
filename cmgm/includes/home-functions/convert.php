<?php
function convert($data,$goto,$default_latitude,$default_longitude,$maps_api_key,$userID,$default_carrier,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom) {
include SITE_ROOT . "/includes/functions/getGetVars.php";
include SITE_ROOT . "/includes/functions/sqlpw.php";
include_once SITE_ROOT . "/includes/home-functions/goto.php";

// If not set get location from DB
if ($data == "defaultLoc") {
  $latitude = $default_latitude;
  $longitude = $default_longitude;
  $conv_type = "Default Location";
}

// CellMapper URL Conversion
if(substr("$data", 0, 26) == 'https://www.cellmapper.net' && !isset($conv_type)) include SITE_ROOT . "/includes/convert/cellmapper.net.php";
// Google Maps URL Conversion
if(substr("$data", 0, 28) == 'https://www.google.com/maps/' && !isset($conv_type)) include SITE_ROOT . "/includes/convert/google-maps-url-conversion.php";
// Comma Seperator
if(strpos($data, 'Latitude') !== false && strpos($data, 'Longitude') !== false && !isset($conv_type)) include SITE_ROOT . "/includes/convert/lat,long-mod.php";
// Comma Seperator
if(strpos($data, ',') !== false && !isset($conv_type)) include SITE_ROOT . "/includes/convert/lat,long.php";
// DMS TO DEC
if (!isset($conv_type)) include SITE_ROOT . "/includes/convert/dmstodec.php";
// NOTHING? Google Maps search for the entered data
if (!isset($conv_type)) include SITE_ROOT . "/includes/convert/google-maps-conversion.php";
// Get address info for location
include SITE_ROOT . "/includes/convert/get-address-for-loc.php";

$latitude = substr($latitude,0,10);
$longitude = substr($longitude,0,10);

if ($goto == "HomeSmart") return [$latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,$goto,@$conv_type,@$url_1,@$url_2];

return function_goto($latitude,$longitude,$carrier,@$address,@$zip,@$city,@$state,@$goto,@$conv_type,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,$cm_netType);
}
