<?php
include 'functions.php';
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
    $latitude = substr($latitude,0,10);
    $longitude = substr($longitude,0,10);
    $conv_type = "CellMapper";
}
// Google Maps URL Conversion (https://www.google.com/maps/@35.2820911,-111.0069811,15.21z > 35.2820911,-111.0069811 )
elseif(substr("$data", 0, 28) === 'https://www.google.com/maps/') {
  if(substr("$data", 0, 33) === 'https://www.google.com/maps/d/u/0') {
    $latitude = explode('ll=', $data, 2)[1];
    $longitude = explode('%2C', $data, 2)[1];
    $latitude = substr($latitude,0,10);
    $longitude = substr($longitude,0,10);
        $conv_type = "Google Maps";
  } else {
    $first = substr($data, strpos($data, "@") + 1);
    $arr = explode("/", $first, 2);
    $second = $arr[0];
    $str_arr = preg_split ("/\,/", $second);
    $latitude = $str_arr[0];
    $longitude = $str_arr[1];
    $conv_type = "Google My Maps";
  }
} elseif( strpos($data, ',') !== false ) {
// Comma Seperator (-50.45894508,-100.3848 > [-50.45894508] [-100.3848] )
$input_data = str_replace(' ', '', $data);
$str_explode = explode(",", $input_data);
$latitude = $str_explode[0];
$longitude = $str_explode[1];
$conv_type = "Latitude & Longitude";
}
if (empty($data)) {
if(!isset($latitude)) $latitude = $_COOKIE["latitude"];
if(!isset($longitude)) $longitude = $_COOKIE["longitude"];
$conv_type = "Cookie Location";
}

// Google Maps search for the entered data (Burger King -> find closest burger king's LAT,LONG (from favorite location)
include 'includes/convert/google-maps-conversion.php';

if(!isset($carrier)) $carrier = $_COOKIE["carrier"];

$latitude = substr($latitude,0,10);
$longitude = substr($longitude,0,10);
echo '<meta http-equiv="refresh" content="0; url=Hub.php
?latitude=' . $latitude .
'&longitude=' . $longitude .
'&carrier=' . $carrier .
'&address=' . $address .
'&zip=' . $zip .
'&city=' . $city .
'&state=' . $state .
'&gjson_url_1=' . $gjson_url_1 .
'&gjson_url_2=' . $gjson_url_2 .
'&data=' . $data .
'&conv_type=' . $conv_type .
'">';
