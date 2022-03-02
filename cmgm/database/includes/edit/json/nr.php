<?php
if (!empty($region_nr) && !(isset($_GET['new']))) {
$base = "https://api.cellmapper.net/v6/getTowerInformation";
if($carrier == "T-Mobile") $base = $base . "?MCC=310&MNC=260";
if($carrier == "ATT") $base = $base . "?MCC=310&MNC=410";
if($carrier == "Sprint") $base = $base . "?MCC=310&MNC=120";
if($carrier == "Verizon") $base = $base . "?MCC=311&MNC=480";


$link_nr = $base . "&Region=" . $region_nr . "&RAT=NR&Site=" . $NR_1; }
if (isset($link_nr)) {?>
<a class="pad-small-link" target="_blank" href="<?php if (isset($link_nr)) echo $link_nr; ?>" >NR_1</a> <?php } ?>
