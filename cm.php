<?php
if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];
$end = "map?latitude=$latitude&longitude=$longitude&zoom=18&showTowerLabels=false";

header('Location: http://www.cellmapper.net/' . $end);
?>
