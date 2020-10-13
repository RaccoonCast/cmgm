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

$deg1 = substr($data,0,3);
$deg1 = str_replace("-","", $deg1);
$min1 = substr($data,3,2);
$sec1 = substr($data,6,2);

$deg2 = substr($data,13,3);
$deg2 = str_replace("-","", $deg2);
$min2 = substr($data,17,2);
$sec2 = substr($data,20,2);

$latConv = DMStoDec($deg1,$min1,$sec1);
$longConv = DMStoDec($deg2,$min2,$sec2);

if(strpos($data, "S") !== false) $latConv = "-".$latConv;
if(strpos($data, "W") !== false) $longConv = "-".$longConv;

$latitude = $latConv;
$longitude = $longConv;
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
    $directionCheck_a = explode(' ',trim($short_street_name));
    $directionCheck_b = explode(' ',trim($long_street_name));

    // Remove - North / East / South / West & N / E / S / W
    if ("$directionCheck_a[0]" == "N" && "$directionCheck_b[0]" == "North") {$long_street_name = substr($long_street_name,6); }
    if ("$directionCheck_a[0]" == "E" && "$directionCheck_b[0]" == "East")  {$long_street_name = substr($long_street_name,5); }
    if ("$directionCheck_a[0]" == "S" && "$directionCheck_b[0]" == "South") {$long_street_name = substr($long_street_name,6); }
    if ("$directionCheck_a[0]" == "W" && "$directionCheck_b[0]" == "West")  {$long_street_name = substr($long_street_name,5); }

    $address = "$number $long_street_name";
 ?>
