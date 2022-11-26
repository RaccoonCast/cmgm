<?php
function convert($data,$goto,$default_latitude,$default_longitude,$maps_api_key,$userID,$default_carrier,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom) {
include SITE_ROOT . "/includes/functions/getGetVars.php";
include SITE_ROOT . "/includes/functions/sqlpw.php";

//file_put_contents("log.log", substr("$data", 0, 31));

// CellMapper URL Conversion
if(substr("$data", 0, 30) == 'https://www.cellmapper.net/map' && !isset($conv_type)) include "convert/cellmapper.net.php";
// CellMapper Testmap URL Conversion
if(substr("$data", 0, 34) == 'https://www.cellmapper.net/testmap/' && !isset($conv_type)) include "convert/cm-testmap.php";
// Google Maps URL Conversion
if(substr("$data", 0, 28) == 'https://www.google.com/maps/' && !isset($conv_type)) include "convert/google-maps-url-conversion.php";
// Comma Seperator
if(strpos($data, 'Latitude') !== false && strpos($data, 'Longitude') !== false && !isset($conv_type)) include "convert/lat,long-mod.php";
// Comma Seperator
if(strpos($data, ',') !== false && !isset($conv_type)) include "convert/lat,long.php";
// DMS TO DEC
if (!isset($conv_type)) include "convert/dmstodec.php";
// NOTHING? Google Maps search for the entered data
if (!isset($conv_type)) include "convert/google-maps-conversion.php";
// Get address info for location
if ($goto != "HomeWOAddr") include "convert/get-address-for-loc.php";

$latitude = substr($latitude,0,12);
$longitude = substr($longitude,0,12);

if (($goto == "HomeWOAddr") OR ($goto == "HomeWAddr")) return [$latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,$goto,@$conv_type,@$url_1,@$url_2];

if (($goto == "pciplus")) return [@$address,@$city,@$zip,@$state];

include_once "function_goto.php";
return function_goto($latitude,$longitude,$carrier,@$address,@$zip,@$city,@$state,@$goto,@$conv_type,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType);
}
