<?php
$latitude = $_GET['latitude'];
$longitude $_GET['longitude'];
$end = "map?latitude=$latitude&longitude=$longitude&zoom=18&showTowerLabels=false";

header('Location: http://www.cellmapper.net/' . $end);
?>
