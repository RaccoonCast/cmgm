<?php
if (!empty($region_lte) && !isset($new)) {
$base = "https://api.cellmapper.net/v6/getTowerInformation";
if($carrier == "T-Mobile") $base = $base . "?MCC=310&MNC=260";
if($carrier == "ATT") $base = $base . "?MCC=310&MNC=410";
if($carrier == "Sprint") $base = $base . "?MCC=310&MNC=120";
if($carrier == "Verizon") $base = $base . "?MCC=311&MNC=480";
if($carrier == "Dish") $base = $base . "?MCC=313&MNC=34";


$link_lte = $base . "&Region=" . $region_lte . "&RAT=LTE&Site=" . $LTE_1; }
if (isset($link_lte)) {?>
<a class="pad-small-link" rel="noreferrer noopener" target="_blank" href="<?php if (isset($link_lte)) echo $link_lte; ?>" >LTE</a> <?php } ?>
