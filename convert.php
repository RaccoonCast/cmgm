<?php

$data = $_GET['data'];
if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];

// CellMapper URL Conversionhttps://www.cellmapper.net/map?MCC=310&MNC=260&type=LTE&latitude=40.13653501758051&longitude=-118.8975396188031&zoo.. > 40.136535,-118.897539)
if (substr("$data", 0, 26) === 'https://www.cellmapper.net') {
    $input = substr("$data", 31, 97);
    $str_explode = explode("&", $input);
    $str1_explode = explode("=", $str_explode[0]);
    $str2_explode = explode("=", $str_explode[1]);
    $network = "$str1_explode[1]$str2_explode[1]";
    if('310260' == '' . $network . '') {$carrier = "T-Mobile";}
    if('310120' == '' . $network . '') {$carrier = "Sprint";}
    if('310410' == '' . $network . '') {$carrier = "ATT";}
    if('311480' == '' . $network . '') {$carrier = "Verizon";}
    echo '<meta http-equiv="refresh" content="0; url=Hub.php?' . $str_explode[3] . '&' . $str_explode[4] . '&carrier=' . $carrier . '">';
}
// Google Maps URL Conversion (https://www.google.com/maps/@35.2820911,-111.0069811,15.21z > 35.2820911,-111.0069811 )
elseif(substr("$data", 0, 28) === 'https://www.google.com/maps/') {
  $first = substr($data, strpos($data, "@") + 1);
  $arr = explode("/", $first, 2);
  $second = $arr[0];
  $str_arr = preg_split ("/\,/", $second);
  $latitude = $str_arr[0];
  $longitude = $str_arr[1];
  echo '<meta http-equiv="refresh" content="0; url=Hub.php?latitude=' . $latitude . '&longitude=' . $longitude . '">';
}elseif (empty($_GET['data'])) {
// Cookie latlong source
echo '<meta http-equiv="refresh" content="0; url=Hub.php">';
} elseif( strpos($data, ',') !== false ) {
// Comma Seperator (-50.45894508,-100.3848 > [-50.45894508] [-100.3848] )
$input_data = str_replace(' ', '', $data);
$str_explode = explode(",", $input_data);
$latitude = $str_explode[0];
$longitude = $str_explode[1];
} else {
// Google Maps search for the entered data (Burger King -> find closest burger king's LAT,LONG (from favorite location))
 $data = str_replace(' ', '+', $data);
 $url = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query=' . $data . '&location=' . $_COOKIE["latitude"] . ',' . $_COOKIE["longitude"] . '&radius=190000&key=' . $_COOKIE["api_key"] . '';
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
 $response = curl_exec($ch);
 curl_close($ch);

 $response = json_decode($response);
 $latitude = $response->results[0]->geometry->location->lat;
 $longitude = $response->results[0]->geometry->location->lng;
}
$latitude = substr($latitude,0,10);
$longitude = substr($longitude,0,10);
echo '<meta http-equiv="refresh" content="0; url=Hub.php?latitude=' . $latitude . '&longitude=' . $longitude . '">';
