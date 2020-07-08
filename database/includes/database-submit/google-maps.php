<?php
// Call google to convert latitude & longitude
$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&key=' . $api_key . '';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$response = curl_exec($ch);
curl_close($ch);

// Parse the json output

 $response = json_decode($response);

 $addressComponents = $response->results[0]->address_components;
 foreach ($addressComponents as $addrComp) {
     if ($addrComp->types[0] == 'postal_code') $zip = $addrComp->long_name;
     if ($addrComp->types[0] == 'street_number') $number = $addrComp->short_name;
     if ($addrComp->types[0] == 'route') $street_name = $addrComp->short_name;
     if ($addrComp->types[0] == 'locality') $city = $addrComp->short_name;
     if ($addrComp->types[0] == 'administrative_area_level_1') $state = $addrComp->short_name;
     }
     $address = "$number $street_name";
?>
