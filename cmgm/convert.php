<?php
function convert($data,$goto,$default_latitude,$default_longitude,$maps_api_key,$userIP,$default_carrier) {
include SITE_ROOT . "/includes/functions/getGetVars.php";
include SITE_ROOT . "/includes/functions/sqlpw.php";
if (!isset($goto_defined)) include SITE_ROOT . "/includes/misc-functions/goto.php";

// If not set get location from DB
if ($data == "defaultLoc") {
  $conv_type = "Default Location";
  $latitude = $default_latitude;
  $longitude = $default_longitude;
  include SITE_ROOT . "/includes/convert/get-address-for-loc.php";
}

if (!isset($conv_type)) {
  // CellMapper URL Conversion
  if(substr("$data", 0, 26 === 'https://www.cellmapper.net') && !isset($conv_type)) include "includes/convert/cellmapper.net.php";
  // Google Maps URL Conversion
  elseif(substr("$data", 0, 28 === 'https://www.google.com/maps/') && !isset($conv_type)) include "includes/convert/google-maps-url-conversion.php";
  // Comma Seperator
  elseif(strpos($data, ',') !== false && !isset($conv_type)) include "includes/convert/lat,long.php";
  // NOTHING? Google Maps search for the entered data // GET ADDRESS INFO FOR LOCATION
  include 'includes/convert/google-maps-conversion.php';

  $latitude = substr($latitude,0,10);
  $longitude = substr($longitude,0,10);
}
if ($goto == "HomeSmart") return [$latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,$goto,@$conv_type];

return function_goto($latitude,$longitude,$carrier,@$address,@$zip,@$city,@$state,@$goto,@$conv_type);
}
