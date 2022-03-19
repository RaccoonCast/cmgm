<?php
// REQUIRE \/ BEFORE RUNNING
if($isMobile == "false" && !isset($delete) && !isset($_GET['new'])) {

// multiplier
$multiplier = substr_replace($cm_pin_distance ,"", -1);
$modDigit = 0.00006;
$modDigit = $modDigit * $multiplier;

if (!empty($LTE_1) OR !empty($LTE_2) OR !empty($LTE_3) OR !empty($LTE_4)) {

// Carrier
$base = "https://api.cellmapper.net/v6/overrideData";
if($carrier == "T-Mobile") $base = $base . "?MCC=310&MNC=260";
if($carrier == "ATT") $base = $base . "?MCC=310&MNC=410";
if($carrier == "Sprint") $base = $base . "?MCC=310&MNC=120";
if($carrier == "Verizon") $base = $base . "?MCC=311&MNC=480";

if (!empty($region_lte) && !(isset($_GET['new']))) {

if ($cm_pin_inverted == "false" OR empty($cm_pin_inverted)) {
  if (!empty($LTE_1)) $LTE_1_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_1 . "&CellID=&Latitude=" . $latitude . "&Longitude=" . $longitude; // Base
  if (!empty($LTE_2)) $LTE_2_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_2 . "&CellID=&Latitude=" . $latitude + $modDigit . "&Longitude=" . $longitude + $modDigit; // Right
  if (!empty($LTE_3)) $LTE_3_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_3 . "&CellID=&Latitude=" . $latitude + $modDigit . "&Longitude=" . $longitude - $modDigit; // Left
  if (!empty($LTE_4)) $LTE_4_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_4 . "&CellID=&Latitude=" . $latitude + $modDigit . "&Longitude=" . $longitude; // Middle
} else {
  if (!empty($LTE_1)) $LTE_1_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_1 . "&CellID=&Latitude=" . $latitude . "&Longitude=" . $longitude; // Base
  if (!empty($LTE_2)) $LTE_2_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_2 . "&CellID=&Latitude=" . $latitude - $modDigit . "&Longitude=" . $longitude + $modDigit; // Right
  if (!empty($LTE_3)) $LTE_3_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_3 . "&CellID=&Latitude=" . $latitude - $modDigit . "&Longitude=" . $longitude - $modDigit; // Left
  if (!empty($LTE_4)) $LTE_4_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_4 . "&CellID=&Latitude=" . $latitude - $modDigit . "&Longitude=" . $longitude; // Middle
}

} ?>


<!-- BASE -->  <?php if (isset($LTE_1_mv) && ($other_user_map_primary == "true")) { ?> <a rel="noreferrer noopener" class="pad-small-link error" target="_blank" href="<?php if (isset($LTE_1_mv)) echo $LTE_1_mv; ?>" >1</a> <?php } ?>
<!-- BASE -->  <?php if (isset($LTE_1_mv) && ($other_user_map_primary != "true")) { ?> <a rel="noreferrer noopener" class="pad-small-link" target="_blank" href="<?php if (isset($LTE_1_mv)) echo $LTE_1_mv; ?>" >1</a> <?php } ?>
<!-- RIGHT --> <?php if (isset($LTE_2_mv)) { ?> <a class="pad-small-link" rel="noreferrer noopener" target="_blank" href="<?php if (isset($LTE_2_mv)) echo $LTE_2_mv; ?>" >2</a> <?php } ?>
<!-- LEFT -->  <?php if (isset($LTE_3_mv)) { ?> <a class="pad-small-link" rel="noreferrer noopener" target="_blank" href="<?php if (isset($LTE_3_mv)) echo $LTE_3_mv; ?>" >3</a> <?php } ?>
<!-- MIDDLE --><?php if (isset($LTE_4_mv)) { ?> <a class="pad-small-link" rel="noreferrer noopener" target="_blank" href="<?php if (isset($LTE_4_mv)) echo $LTE_4_mv; ?>" >4</a>  <?php } ?>
<?php }} ?>
