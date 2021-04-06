<?php
if (isset($maps_api_key)) {
$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&key=' . $maps_api_key . '';
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
    }
?>
