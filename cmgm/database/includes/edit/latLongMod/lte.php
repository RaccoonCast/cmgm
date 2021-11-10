<?php
// REQUIRE \/ BEFORE RUNNING
if($isMobile == "false" && !isset($delete) && !isset($_GET['new'])) {
if (!empty($LTE_1) OR !empty($LTE_2) OR !empty($LTE_3) OR !empty($LTE_4)) {

// Carrier
$base = "https://api.cellmapper.net/v6/overrideData";
if($carrier == "T-Mobile") $base = $base . "?MCC=310&MNC=260";
if($carrier == "AT&T") $base = $base . "?MCC=310&MNC=410";
if($carrier == "Sprint") $base = $base . "?MCC=310&MNC=120";
if($carrier == "Sprint_keep") $base = $base . "?MCC=312&MNC=250";
if($carrier == "Verizon") $base = $base . "?MCC=311&MNC=480";

if (!empty($region_lte)) {
if (!empty($LTE_1)) $LTE_1_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_1 . "&CellID=&Latitude=" . $latitude . "&Longitude=" . $longitude; // Base
if (!empty($LTE_2)) $LTE_2_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_2 . "&CellID=&Latitude=" . $latitude + $modDigit . "&Longitude=" . $longitude + $modDigit; // Right
if (!empty($LTE_3)) $LTE_3_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_3 . "&CellID=&Latitude=" . $latitude + $modDigit . "&Longitude=" . $longitude - $modDigit; // Left
if (!empty($LTE_4)) $LTE_4_mv = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_4 . "&CellID=&Latitude=" . $latitude + $modDigit . "&Longitude=" . $longitude; // Middle
} ?>



<!-- BASE -->  <?php if (isset($LTE_1_mv)) { ?> <a target="_blank" href="<?php if (isset($LTE_1_mv)) echo $LTE_1_mv; ?>" >1</a> <?php } ?>
<!-- RIGHT --> <?php if (isset($LTE_2_mv)) { ?> <a target="_blank" href="<?php if (isset($LTE_2_mv)) echo $LTE_2_mv; ?>" >2</a> <?php } ?>
<!-- LEFT -->  <?php if (isset($LTE_3_mv)) { ?> <a target="_blank" href="<?php if (isset($LTE_3_mv)) echo $LTE_3_mv; ?>" >3</a> <?php } ?>
<!-- MIDDLE --><?php if (isset($LTE_4_mv)) { ?> <a target="_blank" href="<?php if (isset($LTE_4_mv)) echo $LTE_4_mv; ?>" class="pad-small-link-right">4</a>  <?php } ?>
<?php }} ?>
