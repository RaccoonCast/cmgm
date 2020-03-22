<?php

$data = $_GET['data'];
if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];

// LAT & LONG (comma conversion)
if (substr("$data", 0, 26) === 'https://www.cellmapper.net') {
    $input = substr("$data", 31, 97);
    $str_explode = explode("&", $input);
    $str1_explode = explode("=", $str_explode[0]);
    $str2_explode = explode("=", $str_explode[1]);
    $network = "$str1_explode[1]$str2_explode[1]";
    if('310260' == '' . $network . '') {$carrier = "T-Mobile";}
    if('310120' == '' . $network . '') {$carrier = "Sprint";}
    if('310410' == '' . $network . '') {$carrier = "AT&T";}
    if('311480' == '' . $network . '') {$carrier = "Verizon";}
    echo '<meta http-equiv="refresh" content="0; url=hub.php?' . $str_explode[3] . '&' . $str_explode[4] . '&carrier=' . $carrier . '">';
}

elseif(substr("$data", 0, 29) === 'https://www.google.com/maps/@') {
  $str_explode = explode(",", $data);
  $latitude = $str_explode[0];
  $latitude = trim($latitude, 'https://www.google.com/maps/@');
  $longitude = $str_explode[1];
  $latitude = substr($latitude,0,10);
  $longitude = substr($longitude,0,10);
  echo '<meta http-equiv="refresh" content="0; url=hub.php?latitude=' . $latitude . '&longitude=' . $longitude . '">';
}
else {
$str_explode = explode(",", $data);
$latitude = $str_explode[0];
$longitude = $str_explode[1];
$latitude = substr($latitude,0,10);
$longitude = substr($longitude,0,10);
echo '<meta http-equiv="refresh" content="0; url=hub.php?latitude=' . $latitude . '&longitude=' . $longitude . '">';
}
