<?php

if (!isset($latitude) OR !isset($longitude) OR !is_numeric($latitude) or !is_numeric($longitude)) {
// Google Maps search for the entered data (Burger King -> find closest burger king's LAT,LONG (from favorite location))
$data = str_replace(' ', '+', $data);
$url = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query=' . $data . '&location=' . $_COOKIE["latitude"] . ',' . $_COOKIE["longitude"] . '&radius=1000&key=' . $_COOKIE["api_key"] . '';
$gjson_url_1 = $url;
$ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $url); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); curl_setopt($ch, CURLOPT_PROXYPORT, 3128); curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$response = curl_exec($ch);
curl_close($ch);

$response = json_decode($response);
$latitude = $response->results[0]->geometry->location->lat;
$longitude = $response->results[0]->geometry->location->lng;
$conv_type = "Google Search";

if (!isset($latitude) OR !isset($longitude)) {
//if latitude and longitude still not set try DMS to LatLong conversion
function DMStoDEC($deg,$min,$sec) {
  return $deg+((($min*60)+($sec))/3600);
}
if (substr("$data", 11, 1) === 'N') {
// 40-40-54.4 N (40) (40) (54.4) (N) AND 34-05-50.9 N (34) (05) (50.9) (N)
$deg1 = substr($data,0,2);
$min1 = substr($data,3,2);
$sec1 = substr($data,6,4);

// 73-59-36.3 W (77) (59) (36.3) (W) AND 118-15-15.7 W (118) (15) (51.7) (W)
// check for 3 digit $deg2
if (substr("$data", 16, 1) === '-') {
  $deg2 = substr($data,13,3);
  $min2 = substr($data,17,2);
  $sec2 = substr($data,20,4);
} else {
  $deg2 = substr($data,13,2);
  $min2 = substr($data,16,2);
  $sec2 = substr($data,19,4);
}
} else {
  // 40-40-54.4N (40) (40) (54.4) (N) AND 34-05-50.9N (34) (05) (50.9) (N)
  $deg1 = substr($data,0,2);
  $min1 = substr($data,3,2);
  $sec1 = substr($data,6,4);

  // 73-59-36.3W (77) (59) (36.3) (W) AND 118-15-15.7W (118) (15) (51.7) (W)
  // check for 3 digit $deg2
  if (substr("$data", 15, 1) === '-') {
    $deg2 = substr($data,12,3);
    $min2 = substr($data,16,2);
    $sec2 = substr($data,19,4);
  } else {
    $deg2 = substr($data,13,2);
    $min2 = substr($data,16,2);
    $sec2 = substr($data,19,4);
  }
}
$latitude = DMStoDec($deg1,$min1,$sec1);
$longitude = DMStoDec($deg2,$min2,$sec2);
if(strpos($data, "S") !== false) $latitude = "-".$latitude;
if(strpos($data, "W") !== false) $longitude = "-".$longitude;

$conv_type = "DMS Coordinates";
}
}

$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&key=' . $_COOKIE["api_key"] . '';
$gjson_url_2 = $url;
$ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $url); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); curl_setopt($ch, CURLOPT_PROXYPORT, 3128); curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); $response = curl_exec($ch); curl_close($ch);

// Parse the json output
$response = json_decode($response);

 // Retrieve important information from JSON and set the data to a corresponding variable
 $addressComponents = $response->results[0]->address_components;
 foreach ($addressComponents as $addrComp) {
     if ($addrComp->types[0] == 'postal_code') $zip = $addrComp->long_name;
     if ($addrComp->types[0] == 'street_number') $number = $addrComp->short_name;
     if ($addrComp->types[0] == 'route') $short_street_name = $addrComp->short_name;
     if ($addrComp->types[0] == 'route') $long_street_name = $addrComp->long_name;
     if ($addrComp->types[0] == 'locality') $city = $addrComp->short_name;
     if ($addrComp->types[0] == 'administrative_area_level_1') $state = $addrComp->short_name;
     }

    // Remove blank spaces
    if(isset($short_street_name) AND isset($short_street_name)) {
    $directionCheck_a = explode(' ',trim($short_street_name));
    $directionCheck_b = explode(' ',trim($long_street_name));

    // Remove - North / East / South / West & N / E / S / W
    if ("$directionCheck_a[0]" == "N" && "$directionCheck_b[0]" == "North") {$long_street_name = substr($long_street_name,6); }
    if ("$directionCheck_a[0]" == "E" && "$directionCheck_b[0]" == "East")  {$long_street_name = substr($long_street_name,5); }
    if ("$directionCheck_a[0]" == "S" && "$directionCheck_b[0]" == "South") {$long_street_name = substr($long_street_name,6); }
    if ("$directionCheck_a[0]" == "W" && "$directionCheck_b[0]" == "West")  {$long_street_name = substr($long_street_name,5); }
    }

    if(!isset($number)) $number = null;
    if(!isset($long_street_name)) $long_street_name = null;
    $address = "$number $long_street_name";
 ?>
