<?php
include SITE_ROOT . "/includes/link-conversion-and-handling/convert.php";

// IF DATA HAS BEEN ENTERED IN THE FIELD - we need to A) convert & redirect to page
if (@$_POST['goto'] != "HomeSmart" && isset($_POST['data'])) {
  if ($debug_flag != "0") echo "locfinder: $" . "data variable search <br>";
  if (@$_POST['goto'] != "Edit") redir(convert($data,@$_POST['goto'],$default_latitude,$default_longitude,$maps_api_key,$userID,$default_carrier,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom),"0");
  if (@$_POST['goto'] == "Edit") redir("database\Edit.php?id=$data","0");
}

// General shortlink
// cmgm.ml/?q=McDonalds, utilize google maps API search for "McDonalds"
if (isset($_GET['q'])) {
  if ($debug_flag != "0") echo "locfinder: direct-search <br>";
  [$latitude,$longitude,$carrier,$address,$zip,$city,$state,$goto,$conv_type,$url_1,$url_2] = convert($_GET['q'],"HomeSmart",$default_latitude,$default_longitude,$maps_api_key,$userID,$default_carrier,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom);
  $data = $_GET['q'];
}

// No location specified? Attempt to use GPS, fallback on default lat/long.
if (!isset($latitude) && !isset($data)) {
  if ($debug_flag != "0") echo "locfinder: $" . "data variable not specified, attempting gps <br>";
  include "js/locationNotKnown.js.php";
  die();
}
?>
