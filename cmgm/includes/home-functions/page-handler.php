<?php
include SITE_ROOT . "/includes/home-functions/convert.php";

// IF DATA HAS BEEN ENTERED IN THE FIELD - we need to A) convert & redirect to page OR B) convert & stay here.
if (@$_POST['rerunData'] == "true" && $goto != "HomeSmart") {
  if ($debug_flag != "off") echo "Page handler type: 1";
  redir(convert($data,$goto,$default_latitude,$default_longitude,$maps_api_key,$userIP,$default_carrier),"0");
}

// IF DATA HAS NOT BEEN ENTERED IN THE FIELD - we need to A) find the proper redirect page and go to it with whatever location data we already have/had
if (@$_POST['rerunData'] == "false" && $goto != "HomeSmart") {
  if ($debug_flag != "off") echo "Page handler type: 2";
  include SITE_ROOT . "/includes/home-functions/goto.php";
  redir(function_goto($latitude,$longitude,$carrier,@$address,@$zip,@$city,@$state,@$goto,NULL),"0");
}

// Has data & goto been specified in the URL Bar via GET?
if (isset($data) && isset($goto) && !isset($latitude)) {
  if ($debug_flag != "off") echo "Page handler type: 3";
  [$latitude,$longitude,$carrier,$address,$zip,$city,$state,$goto,$conv_type] = convert("$data","HomeSmart",$default_latitude,$default_longitude,$maps_api_key,$userIP,$default_carrier);
}

// No location specifeid? Use GPS
if ($prefLocType == "gps" && !isset($latitude)) {
  if ($debug_flag != "off")echo "Page handler type: 4";
  include "js/locationNotKnown.js.php";
}

// No location specifeid? Use default lat,long.
if ($prefLocType == "settings") if (!isset($data) && !isset($goto)) {
  if ($debug_flag != "off") echo "Page handler type: 5";
  [$latitude,$longitude,$carrier,$address,$zip,$city,$state,$goto,$conv_type] = convert("defaultLoc","HomeSmart",$default_latitude,$default_longitude,$maps_api_key,$userIP,$default_carrier);
}
?>
