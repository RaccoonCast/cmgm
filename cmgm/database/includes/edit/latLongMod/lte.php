<?php
// REQUIRE \/ BEFORE RUNNING
if(!isset($delete) && !isset($new)) {
if (!isset($cm_pin_distance)) $cm_pin_distance = $tmp_cm_pin_distance;
if (!isset($other_user_map_primary)) $other_user_map_primary = $tmp_other_user_map_primary;
if (!isset($cm_pin_inverted)) $cm_pin_inverted = $tmp_cm_pin_inverted;

// multiplier
$multiplier = substr_replace($cm_pin_distance ,"", -1);
$modDigit = 0.000095;
$modDigit = $modDigit * $multiplier;

$pin_lat = ($multiplier >= 2.0) ? ($latitude - $modDigit/2) : $latitude;
$pin_lng = $longitude;

if ((!empty($LTE_1) OR !empty($LTE_2) OR !empty($LTE_3) OR !empty($LTE_4)) or @$ignore_checks == "true") {

// Carrier
$base = "https://api.cellmapper.net/v6/overrideData";
if($carrier == "T-Mobile") $base = $base . "?MCC=310&MNC=260";
if(isset($_GET['fn'])) $base = $base . "?MCC=313&MNC=100";
elseif($carrier == "ATT") $base = $base . "?MCC=310&MNC=410";
if($carrier == "Sprint") $base = $base . "?MCC=310&MNC=120";
if($carrier == "Verizon") $base = $base . "?MCC=311&MNC=480";
if($carrier == "Dish") $base = $base . "?MCC=313&MNC=340";

if ((!empty($region_lte) && !(isset($new))) or @$ignore_checks = "true") {

if ($cm_pin_inverted == "false" OR empty($cm_pin_inverted)) {
  if (!empty($LTE_1)) $LTE_1_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_1 . "&CellID=&Latitude=" . $pin_lat . "&Longitude=" . $pin_lng; // Base
  if (!empty($LTE_2)) $LTE_2_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_2 . "&CellID=&Latitude=" . $pin_lat + $modDigit . "&Longitude=" . $pin_lng + $modDigit; // Right
  if (!empty($LTE_3)) $LTE_3_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_3 . "&CellID=&Latitude=" . $pin_lat + $modDigit . "&Longitude=" . $pin_lng - $modDigit; // Left
  if (!empty($LTE_4)) $LTE_4_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_4 . "&CellID=&Latitude=" . $pin_lat + $modDigit . "&Longitude=" . $pin_lng; // Middle

  @$LTE_1_coordinates_set = $pin_lat . "," . $pin_lng; // Base
  @$LTE_2_coordinates_set = $pin_lat + $modDigit . "," . $pin_lng + $modDigit; // Right
  @$LTE_3_coordinates_set = $pin_lat + $modDigit . "," . $pin_lng - $modDigit; // Left
  @$LTE_4_coordinates_set = $pin_lat + $modDigit . "," . $pin_lng; // Middle
} else {
  if (!empty($LTE_1)) $LTE_1_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_1 . "&CellID=&Latitude=" . $pin_lat . "&Longitude=" . $pin_lng; // Base
  if (!empty($LTE_2)) $LTE_2_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_2 . "&CellID=&Latitude=" . $pin_lat - $modDigit . "&Longitude=" . $pin_lng + $modDigit; // Right
  if (!empty($LTE_3)) $LTE_3_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_3 . "&CellID=&Latitude=" . $pin_lat - $modDigit . "&Longitude=" . $pin_lng - $modDigit; // Left
  if (!empty($LTE_4)) $LTE_4_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_4 . "&CellID=&Latitude=" . $pin_lat - $modDigit . "&Longitude=" . $pin_lng; // Middle

  @$LTE_1_coordinates_set = $pin_lat . "," . $pin_lng; // Base
  @$LTE_2_coordinates_set = $pin_lat - $modDigit . "," . $pin_lng + $modDigit; // Right
  @$LTE_3_coordinates_set = $pin_lat - $modDigit . "," . $pin_lng - $modDigit; // Left
  @$LTE_4_coordinates_set = $pin_lat - $modDigit . "," . $pin_lng; // Middle
}

}}} ?>
