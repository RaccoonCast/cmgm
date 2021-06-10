<?php
include SITE_ROOT . "/includes/home-functions/convert.php";
if (!isset($carrier)) $carrier = $default_latitude;

// IF DATA HAS BEEN ENTERED IN THE FIELD - we need to A) convert & redirect to page
if (@$_POST['rerunData'] == "true" && $goto != "HomeSmart") {
  if ($debug_flag != "off") echo "locfinder: 1";
  redir(convert($data,$goto,$default_latitude,$default_longitude,$maps_api_key,$userIP,$default_carrier),"0");
}

// IF DATA HAS NOT BEEN ENTERED IN THE FIELD - we need to A) find the proper redirect page and go to it with whatever location data we already have/had
if (@$_POST['rerunData'] == "false" && $goto != "HomeSmart") {
  if ($debug_flag != "off") echo "locfinder: 2";
  include SITE_ROOT . "/includes/home-functions/goto.php";
 redir(function_goto($latitude,$longitude,$carrier,@$address,@$zip,@$city,@$state,@$goto,NULL),"0");
}

// General shortlink
if (isset($_GET['q'])) {
  if ($debug_flag != "off") echo "locfinder: direct-search";
  [$latitude,$longitude,$carrier,$address,$zip,$city,$state,$goto,$conv_type,$url_1,$url_2] = convert($_GET['q'],"HomeSmart",$default_latitude,$default_longitude,$maps_api_key,$userIP,$default_carrier);
}

// No location specifeid? Use GPS
if ($prefLocType == "gps" && !isset($latitude) && !isset($data)) {
 if ($debug_flag != "off") echo "locfinder: $" . "data variable not specified, attempting gps";
 include "js/locationNotKnown.js.php";
}

// No location specifeid? Use default lat,long.
if ($prefLocType == "settings" && !isset($data) && !isset($goto)) {
  if ($debug_flag != "off") echo "locfinder: $" . "data variable not specified, defaulting to default_lat/long";
  [$latitude,$longitude,$carrier,$address,$zip,$city,$state,$goto,$conv_type,$url_1,$url_2] = convert("defaultLoc","HomeSmart",$default_latitude,$default_longitude,$maps_api_key,$userIP,$default_carrier);
}

// Still no location? Let's assume GPS failed...
if (!isset($latitude)) {
  if ($debug_flag != "off") echo "locfinder: failed to get gps location, retrieving default loc";
  [$latitude,$longitude,$carrier,$address,$zip,$city,$state,$goto,$conv_type,$url_1,$url_2] = convert("defaultLoc","HomeSmart",$default_latitude,$default_longitude,$maps_api_key,$userIP,$default_carrier);
}
?>
