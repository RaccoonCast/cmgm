<?php
include SITE_ROOT . "/includes/home-functions/convert.php";
if (!isset($carrier)) $carrier = $default_latitude;

// IF DATA HAS BEEN ENTERED IN THE FIELD - we need to A) convert & redirect to page
if (@$_POST['rerunData'] == "true" && @$_POST['goto'] != "HomeSmart") {
  if ($debug_flag != "off") echo "locfinder: $" . "data variable search <br>";
  if (@$_POST['goto'] != "DB-Edit") {
    redir(convert($data,@$_POST['goto'],$default_latitude,$default_longitude,$maps_api_key,$userIP,$default_carrier),"0");
  } else {
    redir("database\Edit.php?id=$data","0");
  }
}

// IF DATA HAS NOT BEEN ENTERED IN THE FIELD - we need to A) find the proper redirect page and go to it with whatever location data we already have/had
if (@$_POST['rerunData'] == "false" && @$_POST['goto'] != "HomeSmart") {
  if ($debug_flag != "off") echo "locfinder: $" . "redriect only... <br>";
  include_once SITE_ROOT . "/includes/home-functions/goto.php";
 redir(function_goto($latitude,$longitude,$carrier,@$address,@$zip,@$city,@$state,@$_POST['goto'],NULL),"0");
}

// General shortlink
if (isset($_GET['q'])) {
  if ($debug_flag != "off") echo "locfinder: direct-search <br>";
  [$latitude,$longitude,$carrier,$address,$zip,$city,$state,$goto,$conv_type,$url_1,$url_2] = convert($_GET['q'],"HomeSmart",$default_latitude,$default_longitude,$maps_api_key,$userIP,$default_carrier);
  $data = $_GET['q'];
}

// No location specifeid? Use GPS
if ($prefLocType == "gps" && !isset($latitude) && !isset($data)) {
  if ($debug_flag != "off") echo "locfinder: $" . "data variable not specified, attempting gps <br>";
  ?> <script src="js/locationNotKnown.js"></script> <?php
}


// No location specifeid? Use default lat,long.
if ($prefLocType == "settings" && !isset($data)) {
  if ($debug_flag != "off") echo "locfinder: $" . "data variable not specified, defaulting to default_lat/long <br>";
  [$latitude,$longitude,$carrier,$address,$zip,$city,$state,$goto,$conv_type,$url_1,$url_2] = convert("defaultLoc","HomeSmart",$default_latitude,$default_longitude,$maps_api_key,$userIP,$default_carrier);
}

// Still no location? Let's assume GPS failed...
if (!isset($latitude) OR !isset($longitude)) {
  if ($debug_flag != "off") echo "locfinder: failed to get gps location, retrieving default loc <br>";
  [$latitude,$longitude,$carrier,$address,$zip,$city,$state,$goto,$conv_type,$url_1,$url_2] = convert("defaultLoc","HomeSmart",$default_latitude,$default_longitude,$maps_api_key,$userIP,$default_carrier);
}
?>
