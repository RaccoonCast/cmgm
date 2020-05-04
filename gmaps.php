<?php
if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];
$url = "https://www.google.com/maps/@?api=1&map_action=map&center=$latitude,$longitude&zoom=20&basemap=satellite";

header('Location: ' . $url);
?>
