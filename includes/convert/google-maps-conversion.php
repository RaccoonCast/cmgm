<?php

if (!isset($conv_type)) {
// Google Maps search for the entered data (Burger King -> find closest burger king's LAT,LONG (from favorite location))
$data = str_replace(' ', '+', $data);
$data = str_replace('#', '', $data);
$url = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query=' . $data . '&location=' . $_COOKIE["latitude"] . ',' . $_COOKIE["longitude"] . '&radius=1000&key=' . $_COOKIE["api_key"] . '';
$gjson_url_1 = $url;
$ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $url); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); curl_setopt($ch, CURLOPT_PROXYPORT, 3128); curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$response = json_decode(curl_exec($ch));
$latitude = $response->results[0]->geometry->location->lat;
$longitude = $response->results[0]->geometry->location->lng;
$conv_type = "Google Search";
curl_close($ch);

if (!isset($latitude) OR !isset($longitude)) include "includes/convert/dmstodec.php";
}

include "includes/convert/get-address-for-loc.php";
 ?>
