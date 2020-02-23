<?php
$latitude = file_get_contents('dustbin\latitude.txt');
$longitude = file_get_contents('dustbin\longitude.txt');
$url = "http://www.google.com/maps/@?api=1&map_action=map&center=$latitude,$longitude&zoom=20&basemap=satellite";

header('Location: ' . $url);
?>
