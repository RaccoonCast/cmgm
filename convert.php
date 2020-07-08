<?php
$data = $_GET['data'];

// CellMapper URL Conversionhttps://www.cellmapper.net/map?MCC=310&MNC=260&type=LTE&latitude=40.13653501758051&longitude=-118.8975396188031&zoo.. > 40.136535,-118.897539)
if (substr("$data", 0, 26) === 'https://www.cellmapper.net') {
    $latitude = explode('latitude=', $data, 2)[1];
    $longitude = explode('longitude=', $data, 2)[1];
    $mcc = explode('MCC=', $data, 2)[1];
    $mcc = substr($mcc,0,3);
    $mnc = explode('MNC=', $data, 2)[1];
    $mnc = substr($mnc,0,3);
    $network = "$mcc$mnc";
    if('310260' == '' . $network . '') {$carrier = "T-Mobile";}
    if('310120' == '' . $network . '') {$carrier = "Sprint";}
    if('310410' == '' . $network . '') {$carrier = "ATT";}
    if('311480' == '' . $network . '') {$carrier = "Verizon";}
}
// Google Maps URL Conversion (https://www.google.com/maps/@35.2820911,-111.0069811,15.21z > 35.2820911,-111.0069811 )
elseif(substr("$data", 0, 28) === 'https://www.google.com/maps/') {
  $first = substr($data, strpos($data, "@") + 1);
  $arr = explode("/", $first, 2);
  $second = $arr[0];
  $str_arr = preg_split ("/\,/", $second);
  $latitude = $str_arr[0];
  $longitude = $str_arr[1];
} elseif( strpos($data, ',') !== false ) {
// Comma Seperator (-50.45894508,-100.3848 > [-50.45894508] [-100.3848] )
$input_data = str_replace(' ', '', $data);
$str_explode = explode(",", $input_data);
$latitude = $str_explode[0];
$longitude = $str_explode[1];
} elseif(!empty($data)) {
// Google Maps search for the entered data (Burger King -> find closest burger king's LAT,LONG (from favorite location))
 $data = str_replace(' ', '+', $data);
 $url = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query=' . $data . '&location=' . $_COOKIE["latitude"] . ',' . $_COOKIE["longitude"] . '&radius=190000&key=' . $_COOKIE["api_key"] . '';
 $ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $url); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); curl_setopt($ch, CURLOPT_PROXYPORT, 3128); curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
 $response = curl_exec($ch);
 curl_close($ch);

 $response = json_decode($response);
 $latitude = $response->results[0]->geometry->location->lat;
 $longitude = $response->results[0]->geometry->location->lng;
}
if(!isset($latitude)) $latitude = $_COOKIE["latitude"];
if(!isset($longitude)) $longitude = $_COOKIE["longitude"];
if(!isset($carrier)) $carrier = $_COOKIE["carrier"];
$latitude = substr($latitude,0,10);
$longitude = substr($longitude,0,10);
echo '<meta http-equiv="refresh" content="0; url=Hub.php?latitude=' . $latitude . '&longitude=' . $longitude . '&carrier=' . $carrier . '">';
