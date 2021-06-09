<?php
include SITE_ROOT . "/includes/home-functions/convert.php";
if (!isset($carrier)) $carrier = $default_latitude;

echo $_POST['rerunData'];
echo $data;

// IF DATA HAS BEEN ENTERED IN THE FIELD - we need to A) convert & redirect to page
if (@$_POST['rerunData'] == "true") {
  if ($debug_flag != "off") echo "locfinder: 1";
  [$latitude,$longitude,$carrier,$address,$zip,$city,$state,$goto,$conv_type] = convert("defaultLoc","HomeSmart",$default_latitude,$default_longitude,$maps_api_key,$userIP,$default_carrier);
}
?>
