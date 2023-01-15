<?php
// REQUIRE \/ BEFORE RUNNING
if(!isset($delete) && !isset($new)) {

// multiplier
$multiplier = substr_replace($cm_pin_distance ,"", -1);
$modDigit = 0.00006;
$modDigit = $modDigit * $multiplier;

if (!empty($NR_1) OR !empty($NR_2) OR !empty($NR_3)) {

// Carrier
$base = "https://api.cellmapper.net/v6/overrideData";
if($carrier == "T-Mobile") $base = $base . "?MCC=310&MNC=260";
if($carrier == "AT&T") $base = $base . "?MCC=310&MNC=410";
if($carrier == "Sprint") $base = $base . "?MCC=310&MNC=120";
if($carrier == "Verizon") $base = $base . "?MCC=311&MNC=480";

if (!empty($region_nr) && !(isset($new))) {

if ($cm_pin_inverted == "false" OR empty($cm_pin_inverted)) {
  if (!empty($NR_1)) $NR_1_mv = $base . "&Region=" . $region_nr . "&RAT=NR&Site=" . $NR_1 . "&CellID=&Latitude=" . $latitude + $modDigit . "&Longitude=" . $longitude + $modDigit; // Right
  if (!empty($NR_2)) $NR_2_mv = $base . "&Region=" . $region_nr . "&RAT=NR&Site=" . $NR_2 . "&CellID=&Latitude=" . $latitude + $modDigit . "&Longitude=" . $longitude - $modDigit; // Left
  if (!empty($NR_3)) $NR_3_mv = $base . "&Region=" . $region_nr . "&RAT=NR&Site=" . $NR_3 . "&CellID=&Latitude=" . $latitude . "&Longitude=" . $longitude; // Base
} else {
  if (!empty($NR_1)) $NR_1_mv = $base . "&Region=" . $region_nr . "&RAT=NR&Site=" . $NR_1 . "&CellID=&Latitude=" . $latitude - $modDigit . "&Longitude=" . $longitude + $modDigit; // Right
  if (!empty($NR_2)) $NR_2_mv = $base . "&Region=" . $region_nr . "&RAT=NR&Site=" . $NR_2 . "&CellID=&Latitude=" . $latitude - $modDigit . "&Longitude=" . $longitude - $modDigit; // Left
  if (!empty($NR_3)) $NR_3_mv = $base . "&Region=" . $region_nr . "&RAT=NR&Site=" . $NR_3 . "&CellID=&Latitude=" . $latitude . "&Longitude=" . $longitude; // Base
}
}
if (isset($NR_1_mv) OR isset($NR_2_mv) OR isset($NR_3_mv)) { ?><p class="pad-small-link pad-small-link-mobile3">-</p><?php }
if (isset($NR_1_mv)) { ?><a class="pad-small-link pad-small-link-mobile2" rel="noreferrer noopener" target="_blank" href="<?php if (isset($NR_1_mv)) { echo $NR_1_mv; }?>"><?php if (isMobile()) echo "NR_"?>1</a><?php }
if (isset($NR_2_mv)) { ?><a class="pad-small-link pad-small-link-mobile2" rel="noreferrer noopener" target="_blank" href="<?php if (isset($NR_2_mv)) { echo $NR_2_mv; }?>"><?php if (isMobile()) echo "NR_"?>2</a><?php }
if (isset($NR_3_mv)) { ?><a class="pad-small-link pad-small-link-mobile2" rel="noreferrer noopener" target="_blank" href="<?php if (isset($NR_3_mv)) { echo $NR_3_mv; }?>"><?php if (isMobile()) echo "NR_"?>3</a><?php }}} ?>
