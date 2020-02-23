<?php
$latitude = file_get_contents('dustbin\latitude.txt');
$longitude = file_get_contents('dustbin\longitude.txt');
$end = "map?latitude=$latitude&longitude=$longitude&zoom=18&showTowerLabels=false";

header('Location: http://www.cellmapper.net/' . $end);
?>
