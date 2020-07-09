<?php
// Google Maps search for the entered data (Burger King -> find closest burger king's LAT,LONG (from favorite location))
$data = str_replace(' ', '+', $data);
$url = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query=' . $data . '&location=' . $_COOKIE["latitude"] . ',' . $_COOKIE["longitude"] . '&radius=1000&key=' . $_COOKIE["api_key"] . '';
$ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $url); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); curl_setopt($ch, CURLOPT_PROXYPORT, 3128); curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$response = curl_exec($ch);
curl_close($ch);

$response = json_decode($response);
$latitude = $response->results[0]->geometry->location->lat;
$longitude = $response->results[0]->geometry->location->lng;
 ?>
