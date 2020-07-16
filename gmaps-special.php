<?php
if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];
//$url = "https://www.google.com/maps/@?api=1&map_action=map&center=$latitude,$longitude&zoom=20&basemap=satellite";
$url = "https://www.google.com/maps/d/u/0/viewer?hl=en&mid=1_69xTUt-g1MITj7lMs1oaCwKl1YuQ4nh&ll=$latitude%2C$longitude&z=18";

header('Location: ' . $url);
?>
