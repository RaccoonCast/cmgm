<?php
// REQUIRE \/ BEFORE RUNNING
if(!isset($delete) && !isset($new)) {

// multiplier
$multiplier = substr_replace($cm_pin_distance ,"", -1);
$modDigit = 0.000095;
$modDigit = $modDigit * $multiplier;

$pin_lat = ($multiplier >= 2.0) ? ($latitude - $modDigit/2) : $latitude;
$pin_lng = $longitude;

if ((!empty($NR_1) OR !empty($NR_2) OR !empty($NR_3)) or @$ignore_checks == "true") {

// Carrier
$base = "https://api.cellmapper.net/v6/overrideData";
if($carrier == "T-Mobile") $base = $base . "?MCC=310&MNC=260";
if($carrier == "ATT") $base = $base . "?MCC=310&MNC=410";
if($carrier == "Sprint") $base = $base . "?MCC=310&MNC=120";
if($carrier == "Verizon") $base = $base . "?MCC=311&MNC=480";

if ((!empty($region_nr) && !(isset($new))) or @$ignore_checks = "true") {

if ($cm_pin_inverted == "false" OR empty($cm_pin_inverted)) {
  if (!empty($NR_1)) $NR_1_mv = $base . "&Region=" . $region_nr . "&RAT=NR&Site=" . $NR_1 . "&CellID=&Latitude=" . $pin_lat + $modDigit . "&Longitude=" . $pin_lng + $modDigit; // Right
  if (!empty($NR_2)) $NR_2_mv = $base . "&Region=" . $region_nr . "&RAT=NR&Site=" . $NR_2 . "&CellID=&Latitude=" . $pin_lat + $modDigit . "&Longitude=" . $pin_lng - $modDigit; // Left
  if (!empty($NR_3)) $NR_3_mv = $base . "&Region=" . $region_nr . "&RAT=NR&Site=" . $NR_3 . "&CellID=&Latitude=" . $pin_lat . "&Longitude=" . $pin_lng; // Base

  $NR_1_coordinates_set = $pin_lat + $modDigit . "," . $pin_lng + $modDigit; // Right
  $NR_2_coordinates_set = $pin_lat + $modDigit . "," . $pin_lng - $modDigit; // Left
  $NR_3_coordinates_set = $pin_lat . "," . $pin_lng; // Base

} else {
  if (!empty($NR_1)) $NR_1_mv = $base . "&Region=" . $region_nr . "&RAT=NR&Site=" . $NR_1 . "&CellID=&Latitude=" . $pin_lat - $modDigit . "&Longitude=" . $pin_lng + $modDigit; // Right
  if (!empty($NR_2)) $NR_2_mv = $base . "&Region=" . $region_nr . "&RAT=NR&Site=" . $NR_2 . "&CellID=&Latitude=" . $pin_lat - $modDigit . "&Longitude=" . $pin_lng - $modDigit; // Left
  if (!empty($NR_3)) $NR_3_mv = $base . "&Region=" . $region_nr . "&RAT=NR&Site=" . $NR_3 . "&CellID=&Latitude=" . $pin_lat . "&Longitude=" . $pin_lng; // Base

  $NR_1_coordinates_set = $pin_lat - $modDigit . "," . $pin_lng + $modDigit; // Right
  $NR_2_coordinates_set = $pin_lat - $modDigit . "," . $pin_lng - $modDigit; // Left
  $NR_3_coordinates_set = $pin_lat . "," . $pin_lng; // Base

}
}
}
}
