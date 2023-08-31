<?php

// Google Maps search for the entered data (Burger King -> find closest burger king's LAT,LONG (from favorite location))
$data = str_replace(' ', '+', $data);
$data = str_replace('#', '', $data);
$url_1 = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query=' . $data . '&location=' . $default_latitude . ',' . $default_longitude . '&radius=1000&key=' . $maps_api_key . '';
$ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $url_1); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); curl_setopt($ch, CURLOPT_PROXYPORT, 3128); curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$response = json_decode(curl_exec($ch));
@$latitude = $response->results[0]->geometry->location->lat;
@$longitude = $response->results[0]->geometry->location->lng;
mysqli_query($conn, "UPDATE userID SET gmaps_util = gmaps_util + 1 WHERE userID = '$userID'");
$conv_type = "Google Search";
curl_close($ch);
?>
